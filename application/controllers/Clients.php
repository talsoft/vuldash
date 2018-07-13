<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model'));
    $this->load->library(array('session'));
    $this->load->helper(array('form_helper'));
  } 

  public function index()
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('AT', $this->session->userdata('profileId')) === false)
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }

    if($this->session->userdata('profileId') == 'A')
    {
      $data['navbar'] = $this->load->view('partials/admin/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/admin/menubar', '', true);
    }

    if($this->session->userdata('profileId') == 'T')
    {
      $data['navbar'] = $this->load->view('partials/tester/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/tester/menubar', '', true);
    }
    $data['profileId'] = $this->session->userdata('profileId');

    $this->load->view('clients', $data);    
  }

  public function getList()
  {
    if($this->session->userdata('profileId') == 'A')    
      $list = $this->Client_model->getList();
    else
      $list = $this->Client_model->getListByTester($this->session->userdata('userId'));

    $res = [];
    foreach($list as $r) {
      $res[] = array('id'       => $r->id,
                     'name'     => $r->name,
                     'DT_RowId' => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function getProjects($clientId)
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('AT', $this->session->userdata('profileId')) === false)
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }

    if($this->session->userdata('profileId') == 'A')
    {
      $data['navbar'] = $this->load->view('partials/admin/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/admin/menubar', '', true);
    }

    if($this->session->userdata('profileId') == 'T')
    {
      $data['navbar'] = $this->load->view('partials/tester/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/tester/menubar', '', true);
    }
    $data['client'] = $this->Client_model->get($clientId);
    $data['profileId'] = $this->session->userdata('profileId');

    $this->load->view('projects', $data);    
  }

  public function getUsers($clientId)
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if($this->session->userdata('profileId') != 'A')
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }

    $data['navbar'] = $this->load->view('partials/admin/navbar', '', true);
    $data['menubar'] = $this->load->view('partials/admin/menubar', '', true);
    $data['client'] = $this->Client_model->get($clientId);

    $this->load->view('users', $data);    
  }

  public function create()
  {
    $data['title'] = 'New Client';
    $data['client'] = new Client();

    $this->load->view('partials/clientsForm', $data);
  }

  public function edit()
  {
    $data['title'] = 'Edit Client';
    $data['client'] = $this->Client_model->get($this->input->get('id', TRUE));

    $this->load->view('partials/clientsForm', $data);
  }

  public function save()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('name', 'Name', 'required');
    if ($this->form_validation->run() == FALSE)
    {
      $result = array('status' => 'error', 'message' => validation_errors());
    }
    else
    {
      $client = new Client();
      $id = (int)$this->security->xss_clean($this->input->post("id"));
      if($id != 0)
        $client = $this->Client_model->get($id);
    
      $client->name = $this->security->xss_clean($this->input->post("name"));
      $client->contact = $this->security->xss_clean($this->input->post("contact"));
      $client->address = $this->security->xss_clean($this->input->post("address"));
      $client->city = $this->security->xss_clean($this->input->post("city"));
      $client->phone1 = $this->security->xss_clean($this->input->post("phone1"));
      $client->phone2 = $this->security->xss_clean($this->input->post("phone2"));

      if($this->Client_model->save($client))
        $result = array('status' => 'ok', 'message' => 'Data was saved');
      else 
        $result = array('status' => 'error', 'message' => 'Failed to save data');
    }    

    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    if(!$this->security->xss_clean($this->input->post("delete")))
    {
      $data['title'] = 'Delete Client';
      $data['client'] = $this->Client_model->get($this->input->get('id', TRUE));
      $data['delete'] = true;

      $this->load->view('partials/clientsForm', $data);
    }
    else
    {
      if($this->Client_model->delete($this->security->xss_clean($this->input->post("id"))))
        $result = array('status' => 'ok', 'message' => 'Client was been deleted');
      else
        $result = array('status' => 'error', 'message' => 'Failed to delete');

      $this->output->set_content_type('application/json');
      echo json_encode($result);    
    }
  }  
}
