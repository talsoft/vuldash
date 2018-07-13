<?php
Class Objectivetype_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
      $query = $this->db->get_where('objectivestype', array('id' => $id))->result('ObjectiveType');
      return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('objectivestype')->result('ObjectiveType');
      return $query;      
    } 

    function save($rec)
    {
      $res = false;
      if($rec->id == 0)
      {
        $this->db->insert('objectivestype', $rec);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $rec->id);        
        $this->db->update('objectivestype', $rec); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      $this->db->where('id', $id);        
      $this->db->delete('objectivestype'); 

      return true;
    } 

}

CLass ObjectiveType
{
    public $id = 0;
    public $name = null;
}
