<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model', 'Project_model', 'Incidentstate_model',
                             'Incident_model', 'Log_model'));
    $this->load->library(array('session'));
    $this->load->helper(array('form_helper'));
  } 

  public function index()
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

    $this->load->view('logs', $data);
  }

  public function getListByIncident($incidentId)
  {
    $list = $this->Log_model->getListByIncident($incidentId);
    $res = [];
    foreach($list as $r) {
      $state = $this->Incidentstate_model->get($r->stateId);
      $user  = $this->User_model->get($r->userId);

      $res[] = array('id'           => $r->id,
                     'date'         => date("m-d-Y", strtotime($r->date)),
                     'user'         => $user->name,
                     'state'        => $state->name,
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function create($incidentId)
  {
    $log = new Log();
    $log->incidentId = $incidentId;

    $incident = $this->Incident_model->get($incidentId);
    $project = $this->Project_model->get($incident->projectId);
    $state = $this->Incidentstate_model->getNext($project->stateId);

    $data['title'] = 'New Track';
    $data['log'] = $log;  
    $data['projectState'] = ($state ? $state->id : 1);
    $data['states'] = $this->Incidentstate_model->getList();

    $this->load->view('partials/logsForm', $data);
  }

  public function save()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('date', 'Date', 'required');
    $this->form_validation->set_rules('stateId', 'State', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $result = array('status' => 'error', 'message' => validation_errors());
    }
    else
    {           
      $incident = $this->Incident_model->get($this->security->xss_clean($this->input->post("incidentId")));
      $project = $this->Project_model->get($incident->projectId);

      $log = new Log();      
      $id = (int)$this->security->xss_clean($this->input->post("id"));
      if($id != 0)
        $log = $this->Log_model->get($id);
      
      $log->projectId = $project->id;
      $log->incidentId = $this->security->xss_clean($this->input->post("incidentId"));
      $log->date = date('Y-m-d H:i:s', strtotime($this->security->xss_clean($this->input->post("date"))));
      $log->stageId = $incident->stageId;
      $log->stateId = $this->security->xss_clean($this->input->post("stateId"));
      $log->detail = $this->security->xss_clean($this->input->post("detail"));
      $log->userId = $this->session->userdata('userId');

      if($this->Log_model->save($log))
        $result = array('status' => 'ok', 'message' => 'Data was saved');
      else 
        $result = array('status' => 'error', 'message' => 'Failed to save data');
    }
    $this->output->set_content_type('application/json');
    echo json_encode($result);        
  }
}
