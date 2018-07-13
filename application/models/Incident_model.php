<?php
Class Incident_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array('Log_model', 'User_model', 'Notification_model'));
    }

    function get($id)
    {
      $query = $this->db->get_where('incidents', array('id' => $id))->result('Incident');
      return $query[0];      
    }

    function getList()
    {     
    } 

    function getListByProject($projectId)
    {
      $this->db->from('incidents');      
      $this->db->where(array('projectId' => $projectId));
      $this->db->order_by('cvss', 'desc');
      $query = $this->db->get()->result('Incident');
      return $query;      
    } 

    function getListConfirmedByProject($projectId)
    {
      $this->db->from('incidents');      
      $this->db->where(array('projectId' => $projectId));
      $this->db->where(array('stateId != ' => 1));
      $this->db->order_by('cvss', 'desc');
      $query = $this->db->get()->result('Incident');
      return $query;      
    } 

    function getLastActivity($userId, $count = 0)
    {
      $user = $this->User_model->get($userId);
      if($user->profileId == 'A')
      {
      }
      else 
      {
        if($user->profileId == 'T')
        {
          $this->db->join('projects', 'projects.id = incidents.projectId');
          $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
          $this->db->where('projectstesters.userId', $userId);
          $this->db->where('projectstesters.active', 1);          
        }
        else
        { 
          $this->db->join('projects', 'projects.id = incidents.projectId');
          $this->db->join('clients', 'clients.id = projects.clientId');
          $this->db->join('users', 'users.clientId = clients.id');
          $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                 
        }
      }

      if($count != 0)
        $this->db->limit($count);  

      $this->db->from('incidents');
      $this->db->order_by('date DESC');
      $query = $this->db->get()->result('Incident');
      return $query;      
    }

    function getCountInfo($userId = null, $projectId = null)
    {      
      if($userId)
      {        
        $user = $this->User_model->get($userId);
        if($user->profileId == 'A')
        {
        }
        else 
        {
          if($user->profileId == 'T')
          {
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);
          }        
          else
          {        
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                 
          }
        }
      }      
      $this->db->where('cvss <=', 0.9);      
      if($projectId)
        $this->db->where('projectId', $projectId);      

      $this->db->from('incidents');
      $query = $this->db->count_all_results();
      return $query;      
    }

    function getCountLow($userId = null, $projectId = null)
    {      
      if($userId)
      {      
        $user = $this->User_model->get($userId);
        if($user->profileId == 'A')
        {
        }
        else 
        {        
          if($user->profileId == 'T')
          {
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);
          }        
          else
          {        
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                 
          }
        }
      }      
      $this->db->where('cvss >=', 1);      
      $this->db->where('cvss <=', 3.9);      
      if($projectId)
        $this->db->where('projectId', $projectId);      

      $this->db->from('incidents');
      $query = $this->db->count_all_results();
      return $query;      
    }

    function getCountMod($userId = null, $projectId = null)
    {
      if($userId)
      {
        $user = $this->User_model->get($userId);
        if($user->profileId == 'A')
        {
        }
        else 
        {        
          if($user->profileId == 'T')
          {
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);
          }
          else
          {  
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                 
          }
        }
      }      
      $this->db->where('cvss >=', 4);
      $this->db->where('cvss <=', 6.9);
      if($projectId)
        $this->db->where('projectId', $projectId);      

      $this->db->from('incidents');
      $query = $this->db->count_all_results();
      return $query;      
    }

    function getCountHig($userId = null, $projectId = null)
    {
      if($userId)
      {
        $user = $this->User_model->get($userId);
        if($user->profileId == 'A')
        {
        }
        else 
        {        
          if($user->profileId == 'T')
          {
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);          
          }
          else
          { 
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                               
          }
        }
      }      
      $this->db->where('cvss >=', 7);      
      $this->db->where('cvss <=', 8.9);
      if($projectId)
        $this->db->where('projectId', $projectId);      

      $this->db->from('incidents');
      $query = $this->db->count_all_results();
      return $query;      
    }

    function getCountCrit($userId = null, $projectId = null)
    {
      if($userId)
      {
        $user = $this->User_model->get($userId);
        if($user->profileId == 'A')
        {
        }
        else 
        {        
          if($user->profileId == 'T')
          {
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);          
          }
          else
          { 
            $this->db->join('projects', 'projects.id = incidents.projectId');
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where(Array('users.id' => $userId, 'incidents.stateId !=' => 1));                               
          }
        }
      }      
      $this->db->where('cvss >=', 9);
      $this->db->from('incidents');
      if($projectId)
        $this->db->where('projectId', $projectId);      

      $query = $this->db->count_all_results();
      return $query;      
    }

    function getCountByLevel($projectId)
    {
      $query = $this->db->query('SELECT date, ' .
                                'sum(case when cvss >= 9 then 1 else 0 end) crit, ' .
                                'sum(case when cvss >= 7 and cvss < 8.9 then 1 else 0 end) high, ' .
                                'sum(case when cvss >= 4 and cvss < 6.9 then 1 else 0 end) med, ' .
                                'sum(case when cvss >= 1 and cvss < 3.9 then 1 else 0 end) low, ' .
                                'sum(case when cvss <= 0.9 then 1 else 0 end) info ' .
                                'FROM incidents ' . 
                                'WHERE projectId = ' . $projectId . ' ' . 
                                'GROUP BY date');


      $result = $query->result_array();  
      return $result;    
    }

    function save($incident)
    {
      $res = false;
      if($incident->id == 0)
      {
        $this->db->insert('incidents', $incident);
        $res = $this->db->affected_rows();

        $newId = $this->db->insert_id();

        //if it's new, add tracking start
        $log = new Log();
        $log->id = 0;
        $log->projectId = $incident->projectId;
        $log->incidentId = $newId;
        $log->date = $incident->date;
        $log->stageId = $incident->stageId;
        $log->stateId = $incident->stateId;
        $log->userId = $incident->userId;
        $log->detail = '';
        $this->Log_model->save($log);  

        $this->Notification_model->addNewNotification($incident->projectId, $incident->userId, $newId, 1);
      }
      else 
      {
        $this->db->where('id', $incident->id);        
        $this->db->update('incidents', $incident); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      //Delete log for incident
      $this->db->where('incidentId', $id);
      $this->db->delete('logs');      

      $this->db->where('id', $id);
      $this->db->delete('incidents');      
      return true;
    }    
}

CLass Incident 
{
    public $id = 0;
    public $projectId = null;
    public $date = null;
    public $typeId = null;
    public $cvss = null;
    public $objectiveTypeId = null;
    public $objective = null;
    public $description = null;
    public $stateId = null;
    public $abstract = null;
    public $detail = null;
    public $suggestion = null;
    public $userId = null;
    public $stageId = null;
    public $attach = null;
}
