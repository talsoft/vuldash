<?php
Class Log_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array('Incident_model'));
    }

    function get($id)
    {
      $query = $this->db->get_where('logs', array('id' => $id))->result('Log');
      return $query[0];      
    }

    function getListByIncident($incidentId)
    {
      $this->db->from('logs');      
      $this->db->where(array('incidentId' => $incidentId));
      $this->db->order_by('date', 'desc');
      $query = $this->db->get()->result('Log');
      return $query;      
    } 

    function getStageChange($projectId, $stageId)
    {
      $this->db->from('logs');
      $this->db->where(array('projectId' => $projectId, 'stageId' => $stageId));
      $this->db->order_by('date', 'desc');
      $query = $this->db->get()->result('Log');

      if(sizeof($query) > 0)
        return $query[0];
      else
        return null;
    }

    function save($log)
    {
      $res = false;
      if($log->id == 0)
      {
        $this->db->insert('logs', $log);
        $res = $this->db->affected_rows();

        if($log->incidentId != 0)
        {
          //Update incident state
          $incident = $this->Incident_model->get($log->incidentId);
          if($incident->stateId != $log->stateId)
          {
            $oldState = $incident->stateId;

            $incident->stateId = $log->stateId;
            $this->Incident_model->save($incident);

            if($oldState == 1 && $log->stateId == 2)
              $this->Notification_model->addNewNotification($incident->projectId, $log->userId, $log->incidentId, 2);
            else
              $this->Notification_model->addNewNotification($incident->projectId, $log->userId, $log->incidentId, 3);          
          }      
        }
      }
      else 
      {
        $this->db->where('id', $log->id);        
        $this->db->update('logs', $log); 
        $res = true;
      }
      return $res;      
    }
}

CLass Log 
{
    public $id = 0;
    public $projectId = null;
    public $incidentId = null;
    public $date = null;
    public $stageId = null;
    public $stateId = null;
    public $userId = null;
    public $detail = null;
}
