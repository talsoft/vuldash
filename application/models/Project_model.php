<?php
Class Project_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model(array('User_model', 'Incident_model'));
    }

    function get($id)
    {
      $query = $this->db->get_where('projects', array('id' => $id))->result('Project');
      return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('projects')->result('Project');
      return $query;      
    } 

    function getListByClient($clientId)
    {
      $query = $this->db->order_by('name ASC')->get_where('projects', array('clientId' => $clientId, 'stateId <>' => 4))->result('Project');
      return $query;      
    } 

    function getListByTester($userId)
    {
      $this->db->select('projects.*');
      $this->db->from('projects');
      $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
      $this->db->where(array('projectstesters.userId' => $userId, 'projectstesters.active' => 1, 'stateId <>' => 4));
      $this->db->order_by('name ASC');

      $query = $this->db->get()->result('Project');      
      return $query;      
    }

    function getListByClientAndTester($clientId, $userId)    
    {
      $this->db->select('projects.*');
      $this->db->from('projects');
      $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
      $this->db->where(array('clientId' => $clientId, 'projectstesters.userId' => $userId, 'projectstesters.active' => 1, 'stateId <>' => 4));
      $this->db->order_by('name ASC');

      $query = $this->db->get()->result('Project');      
      return $query;            
    }

    function getCountActive($userId = null)
    {
      if($userId)
      {
        $user = $this->User_model->get($userId);
        if($user->profileId == 'T')
        {
          $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
          $this->db->where('projectstesters.userId', $userId);
          $this->db->where('projectstesters.active', 1);
        }
        else
        {
          $this->db->join('clients', 'clients.id = projects.clientId');
          $this->db->join('users', 'users.clientId = clients.id');
          $this->db->where('users.id', $userId);
        }
      }
      $this->db->where('stateId', 2);
      $this->db->from('projects');
      $query = $this->db->count_all_results();
      return $query;       
    }

    function getListByUser($userId = null)
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
            $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
            $this->db->where('projectstesters.userId', $userId);
            $this->db->where('projectstesters.active', 1);
          }
          else
          {
            $this->db->join('clients', 'clients.id = projects.clientId');
            $this->db->join('users', 'users.clientId = clients.id');
            $this->db->where('users.id', $userId);
          }
        }
      }
      $this->db->from('projects');
      $query = $this->db->get()->result('Project');
      return $query;       
    }

    function save($project)
    {
      $res = false;
      if($project->id == 0)
      {
        $this->db->insert('projects', $project);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $project->id);        
        $this->db->update('projects', $project); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      $this->db->where('projectId', $id);
      $query = $this->db->get('incidents')->result('Incident');
      foreach($query as $r) {
        //Delete log of incident
        $this->db->where('incidentId', $r->id);
        $this->db->delete('logs');

        //Delete incident
        $this->db->where('id', $r->id);
        $this->db->delete('incidents');
      }

      //Delete project
      $this->db->where('id', $id);
      $this->db->delete('projects');      
      return true;
    }    
}

CLass Project 
{
    public $id = 0;
    public $name = null;
    public $clientId = 0;
    public $initDate = null;
    public $endDate = null;
    public $stateId = null;
    public $description = null;  
    public $scope  = null;
    public $typeId = null;
    public $stageId = null;
    public $services = null;
    public $templateReport = null;
    public $reportName = null;
}
