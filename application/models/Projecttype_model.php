<?php
Class Projecttype_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
      $query = $this->db->get_where('projectstype', array('id' => $id))->result('ProjectType');
      return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('projectstype')->result('ProjectType');
      return $query;      
    } 

    function save($rec)
    {
      $res = false;
      if($rec->id == 0)
      {
        $this->db->insert('projectstype', $rec);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $rec->id);        
        $this->db->update('projectstype', $rec); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      $this->db->where('id', $id);        
      $this->db->delete('projectstype'); 

      return true;
    }     
}

CLass ProjectType
{
    public $id = 0;
    public $name = null;
    public $stages = null;
    public $metodology = null;
}
