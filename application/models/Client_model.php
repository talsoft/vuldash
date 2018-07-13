<?php
Class Client_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array('User_model'));
    }

    function get($id)
    {
      $query = $this->db->get_where('clients', array('id' => $id))->result('Client');
      return $query[0];      
    }

    function getList()
    {
      $query = $this->db->order_by('name ASC')->get('clients')->result('Client');
      return $query;      
    } 

    function getListByTester($userId)
    {
      $this->db->select('clients.*');
      $this->db->from('clients');
      $this->db->join('projects', 'projects.clientId = clients.id');
      $this->db->join('projectstesters', 'projectstesters.projectId = projects.id');
      $this->db->where(array('projectstesters.userId' => $userId, 'projectstesters.active' => 1));
      $this->db->order_by('clients.name ASC');

      $query = $this->db->get()->result('Client');  
      return $query;      
    }

    function save($client)
    {
      $res = false;
      if($client->id == 0)
      {
        $this->db->insert('clients', $client);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $client->id);        
        $this->db->update('clients', $client); 
        $res = true;
      }
      return $res;      
    }

    function delete($id)
    {
      //Projects, Incidents, Logs, Users
      $this->db->where('id', $id);
      $this->db->delete('clients');
      return true;
    }
}

CLass Client 
{
    public $id = 0;
    public $name = null;
    public $contact = null;
    public $address = null;
    public $city = null;
    public $phone1 = null;
    public $phone2 = null;    
}
