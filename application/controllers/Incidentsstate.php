<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incidentsstate extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('Incidentstate_model'));

    $this->load->library(array('session'));
    $this->load->helper(array('form_helper'));

    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if($this->session->userdata('profileId') != 'A')
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }    
  } 

  public function index()
  {
    $data['navbar'] = $this->load->view('partials/admin/navbar', '', true);
    $data['menubar'] = $this->load->view('partials/admin/menubar', '', true);
    $data['profileId'] = $this->session->userdata('profileId');

    $this->load->view('incidentsstate', $data);
  }

  public function getList()
  {
    $list = $this->Incidentstate_model->getList();

    $res = [];
    foreach($list as $r) 
    {
      $res[] = array('id'           => $r->id,
                     'name'         => $r->name,
                     'order'        => $r->listOrder,
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function edit()
  {
    $this->output->set_content_type('application/json');
    echo json_encode($this->Incidentstate_model->get($this->input->get('id', TRUE)));
  }

  public function save()
  {
    $rec = new IncidentState();
    $id = (int)$this->security->xss_clean($this->input->post("id"));
    if($id != 0)
      $rec = $this->Incidentstate_model->get($id);
    
    $rec->name = $this->security->xss_clean($this->input->post("name"));
    $rec->listOrder = $this->security->xss_clean($this->input->post("order"));

    if($this->Incidentstate_model->save($rec))
      $result = array('status' => 'ok', 'message' => 'Data was saved');
    else 
      $result = array('status' => 'error', 'message' => 'Failed to save data');

    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    $this->Incidentstate_model->delete($this->input->get('id', TRUE));
  }
}
