<?php
Class Notification_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array('User_model', 'Project_model', 'Incident_model'));
    }

    function get($id)
    {
      $query = $this->db->get_where('notifications', array('id' => $id))->result('Notification');
      return $query[0];      
    }

    function getListByUser($userId, $cant = 0, $notReaded = false)
    {
      $this->db->from('notifications');      
      $this->db->where(array('userId' => $userId));
      if($notReaded)
        $this->db->where(array('readed' => false));
      $this->db->order_by('date', 'desc');
      if($cant != 0)
        $this->db->limit($cant);
      $query = $this->db->get()->result('Notification');
      return $query;      
    } 

    function addNewNotification($projectId, $userId, $incidentId, $event)
    {
      $p = $this->Project_model->get($projectId);
      $i = $this->Incident_model->get($incidentId);

      $datetime = date('Y-m-d H:i:s');
      switch ($event) {
        case 1:
          $eventText = "Added an incident";
          break;

        case 2:
          $eventText = "Published an incident";
          break;

        case 3:
          $eventText = "An incident changed status";
          break;

      }

      //Get Admins
      $admins = $this->User_model->getListAdmins();
      foreach ($admins as $r) 
      {
        if($r->id != $userId)
        {
          $not = new Notification();
          $not->date = $datetime;
          $not->userId = $r->id;
          $not->fromUserId = $userId;
          $not->projectId = $projectId;
          $not->incidentId = $incidentId;
          $not->readed = false;
          $not->event = $eventText;
          $this->save($not);            
        }
      }
      
      //Get Testers for project
      $testers = $this->User_model->getListTestersByProject($projectId);
      foreach ($testers as $r) 
      {
        if($r->id != $userId)
        {
          $not = new Notification();
          $not->date = $datetime;
          $not->userId = $r->id;
          $not->fromUserId = $userId;
          $not->projectId = $projectId;
          $not->incidentId = $incidentId;
          $not->readed = false;
          $not->event = $eventText;
          $this->save($not);            
        }
      }

      //Get Users for project client
      if($i->stateId != 1)
      {
        $client = $this->User_model->getListByClient($p->clientId);
        foreach ($client as $r) 
        {
          if($r->id != $userId)
          {
            $not = new Notification();
            $not->date = $datetime;
            $not->userId = $r->id;
            $not->fromUserId = $userId;
            $not->projectId = $projectId;
            $not->incidentId = $incidentId;
            $not->readed = false;
            $not->event = $eventText;
            $this->save($not);            
          }
        }
      }
    }

    function save($notification)
    {
      $res = false;
      if($notification->id == 0)
      {
        $this->db->insert('notifications', $notification);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $notification->id);        
        $this->db->update('notifications', $notification); 
        $res = true;
      }
      return $res;      
    }
}

CLass Notification 
{
    public $id = 0;
    public $date = null;
    public $userId = null;
    public $fromUserId = null;
    public $projectId = null;
    public $incidentId = null;
    public $readed = null;
    public $event = null;
}
