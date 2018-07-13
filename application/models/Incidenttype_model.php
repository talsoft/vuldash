<?php
Class Incidenttype_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
      $query = $this->db->get_where('incidentstype', array('id' => $id))->result('IncidentType');
      if($query)
        return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('incidentstype')->result('IncidentType');
      return $query;      
    } 

    function save($rec)
    {
      $res = false;
      if($rec->id == 0)
      {
        $this->db->insert('incidentstype', $rec);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $rec->id);        
        $this->db->update('incidentstype', $rec); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      $this->db->where('id', $id);        
      $this->db->delete('incidentstype'); 

      return true;
    }     
}

CLass IncidentType
{
    public $id = 0;
    public $name = null;
    public $description = null;
    public $solution = null;
    public $reference = null;
}
