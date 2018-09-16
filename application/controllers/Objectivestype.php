<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Objectivestype extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('Objectivetype_model'));

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

    $this->load->view('objectivestype', $data);
  }

  public function getList()
  {
    $list = $this->Objectivetype_model->getList();

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

  public function edit()
  {
    $this->output->set_content_type('application/json');
    echo json_encode($this->Objectivetype_model->get($this->input->get('id', TRUE)));
  }

  public function save()
  {
    $rec = new ObjectiveType();
    $id = (int)$this->security->xss_clean($this->input->post("id"));
    if($id != 0)
      $rec = $this->Objectivetype_model->get($id);
    
    $rec->name = $this->security->xss_clean($this->input->post("name"));

    if($this->Objectivetype_model->save($rec))
      $result = array('status' => 'ok', 'message' => 'Data was saved');
    else 
      $result = array('status' => 'error', 'message' => 'Failed to save data');

    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    $this->Objectivetype_model->delete($this->input->get('id', TRUE));
  }  
}
