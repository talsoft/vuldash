<?php
Class Incidentstate_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
      $query = $this->db->get_where('incidentsstate', array('id' => $id))->result('IncidentState');
      if($query)
        return $query[0];
    }

    function getNext($stateId)
    {
      $query = $this->db->get_where('incidentsstate', array('id' => $stateId))->result('IncidentState');
      if($query)
        $state = $query[0];
      
      if($state)
      {
        $query = $this->db->get_where('incidentsstate', array('listOrder >' => $state->listOrder))->result('IncidentState');
        if($query)
          return $query[0];
      }     
    }

    function getList()
    {
      $query = $this->db->order_by('listOrder ASC')->get('incidentsstate')->result('IncidentState');
      return $query;      
    } 

    function save($rec)
    {
      $res = false;
      if($rec->id == 0)
      {
        $this->db->insert('incidentsstate', $rec);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $rec->id);        
        $this->db->update('incidentsstate', $rec); 
        $res = true;
      }
      return $res;      
    }  

    function delete($id)
    {
      $this->db->where('id', $id);        
      $this->db->delete('incidentsstate'); 

      return true;
    } 
}

CLass IncidentState
{
    public $id = 0;
    public $name = null;
    public $listOrder = 0;
}
