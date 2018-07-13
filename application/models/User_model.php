<?php
Class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

    }

    function get($id)
    {
      $query = $this->db->get_where('users', array('id' => $id))->result('User');
      if($query)
        return $query[0];
    }

    function getByUsername($username) 
    {
      $query = $this->db->get_where('users', array('username' => $username))->result('User');
      return $query[0];
    }

    function getByHashAndPass($user, $pass)
    {
      $query = $this->db->get_where('users', array('hash' => $user, 'password' => $pass))->result('User');
      if($query)
        return $query[0];
    }

    function getListByClient($clientId)
    {
      $query = $this->db->order_by('name ASC')->get_where('users', array('clientId' => $clientId))->result('User');
      return $query;      
    } 

    function getListInternal()
    {
      $profiles = array('T', 'A');
      $query = $this->db->where_in('profileId', $profiles)->order_by('name ASC')->get('users')->result('User');
      return $query;
    } 

    function getListTesters()
    {
      $profiles = array('T');
      $query = $this->db->where_in('profileId', $profiles)->order_by('name ASC')->get('users')->result('User');
      return $query;
    } 

    function getListAdmins()
    {
      $profiles = array('A');
      $query = $this->db->where_in('profileId', $profiles)->order_by('name ASC')->get('users')->result('User');
      return $query;
    }

    function getListTestersByProject($projectId)
    {
      $this->db->select('users.*');
      $this->db->from('users');
      $this->db->join('projectstesters', 'projectstesters.userId = users.id');
      $this->db->where(array('projectstesters.projectId' => $projectId, 'projectstesters.active' => 1));

      $query = $this->db->get()->result('User');  
      return $query;
    } 

    function save($user)
    {
      $res = false;
      if($user->id == 0)
      {
        $this->db->insert('users', $user);
        $res = $this->db->affected_rows();
      }
      else 
      {
        $this->db->where('id', $user->id);        
        $this->db->update('users', $user); 
        $res = true;
      }
      return $res;
    }

    function delete($id)
    {
      $this->db->where('id', $id);
      $this->db->delete('users');      
      return true;
    }
    
    function login_user($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            $this->session->set_flashdata('incorrect_user', 'The entered data is incorrect');
            redirect(base_url().'login', 'refresh');
        }
    } 
}

CLass User 
{
    public $id = 0;
    public $username = null;
    public $password = null;
    public $profileId = null;
    public $name = null;
    public $clientId = null;
    public $hash = null;
    public $active = null;
}
