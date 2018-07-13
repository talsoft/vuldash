<?php
Class Projectstate_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
      $query = $this->db->get_where('projectsstate', array('id' => $id))->result('ProjectState');
      return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('projectsstate')->result('ProjectState');
      return $query;      
    } 

    function save($rec)
    {
      $res = false;
      if($rec->id == 0)
      {
        $this->db->insert('projectsstate', $rec);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $rec->id);        
        $this->db->update('projectsstate', $rec); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      $this->db->where('id', $id);        
      $this->db->delete('projectsstate'); 

      return true;
    }     
}

CLass ProjectState
{
    public $id = 0;
    public $name = null;
}
