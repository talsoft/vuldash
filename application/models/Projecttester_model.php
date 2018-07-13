<?php
Class Projecttester_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

    }

    function get($projectId, $userId)
    {
      $conditions = array('projectId' => $projectId, 'userId' => $userId);
      $query = $this->db->where($conditions)->get()->result('ProjectTester');
      return $query[0];      
    }

    function getListByProject()
    {
      $query = $this->db->get('projectstesters')->result('ProjectTester');
      return $query;      
    } 

    function save($projecttester)
    {      
      $res = false;
      $this->db->where('projectId', $projecttester->projectId);        
      $this->db->where('userId', $projecttester->userId);        
      $query = $this->db->get('projectstesters')->result('ProjectTester');      
      $rec = $query[0]; 
      if(!$rec)
      {
        $this->db->insert('projectstesters', $projecttester);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('projectId', $projecttester->projectId);        
        $this->db->where('userId', $projecttester->userId);        
        $this->db->update('projectstesters', $projecttester); 
        $res = true;
      }
      return $res;      
    }
}

CLass ProjectTester
{
    public $projectId = 0;
    public $userId = 0;    
    public $active = false;
}
