<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incidentstype extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('Incidenttype_model'));

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

    $this->load->view('incidentstype', $data);
  }

  public function getList()
  {
    $list = $this->Incidenttype_model->getList();

    $res = [];
    foreach($list as $r) 
    {
      $res[] = array('id'           => $r->id,
                     'name'         => $r->name,
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function create()
  {
    $incidenttype = new IncidentType();    

    $data['title'] = 'New Incident type';
    $data['incidenttype'] = $incidenttype;    

    $this->load->view('partials/incidentstypeForm', $data);
  }

  public function edit()
  {
    $data['title'] = 'Edit Incident type';
    $data['incidenttype'] = $this->Incidenttype_model->get($this->input->get('id', TRUE));

    $this->load->view('partials/incidentstypeForm', $data);
  }

  public function save()
  {
    $rec = new IncidentType();
    $id = (int)$this->security->xss_clean($this->input->post("id"));
    if($id != 0)
      $rec = $this->Incidenttype_model->get($id);
    
    $rec->name = $this->security->xss_clean($this->input->post("name"));
    $rec->description = $this->security->xss_clean($this->input->post("description"));
    $rec->solution = $this->security->xss_clean($this->input->post("solution"));
    $rec->reference = $this->security->xss_clean($this->input->post("reference"));

    if($this->Incidenttype_model->save($rec))
      $result = array('status' => 'ok', 'message' => 'Data was saved');
    else 
      $result = array('status' => 'error', 'message' => 'Failed to save data');

    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    $this->Incidenttype_model->delete($this->input->get('id', TRUE));
  }

}
