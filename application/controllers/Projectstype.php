<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectstype extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('Projecttype_model'));

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

    $this->load->view('projectstype', $data);
  }

  public function getList()
  {
    $list = $this->Projecttype_model->getList();

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
    $projecttype = new ProjectType();    

    $data['title'] = 'New Project type';
    $data['projecttype'] = $projecttype;    

    $this->load->view('partials/projectstypeForm', $data);
  }

  public function edit()
  {
    $data['title'] = 'Edit Project type';
    $data['projecttype'] = $this->Projecttype_model->get($this->input->get('id', TRUE));

    $this->load->view('partials/projectstypeForm', $data);
  }

  public function save()
  {
    $rec = new ProjectType();
    $id = (int)$this->security->xss_clean($this->input->post("id"));
    if($id != 0)
      $rec = $this->Projecttype_model->get($id);
    
    $rec->name = $this->security->xss_clean($this->input->post("name"));
    $rec->stages = implode(';', $this->security->xss_clean($this->input->post("stagename")));
    $rec->metodology = $this->security->xss_clean($this->input->post("metodology"));
  
    if($this->Projecttype_model->save($rec))
      $result = array('status' => 'ok', 'message' => 'Data was saved');
    else 
      $result = array('status' => 'error', 'message' => 'Failed to save data');

    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    $this->Projecttype_model->delete($this->input->get('id', TRUE));
  }  
}
