<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model', 'Project_model', 'Projectstate_model', 'Projecttype_model', 'Projecttester_model', 'Log_model'));

    $this->load->library(array('session', 'word'));
    $this->load->helper(array('form_helper'));
  } 

  public function index()
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('ATGE', $this->session->userdata('profileId')) === false)
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

    if($this->session->userdata('profileId') == 'G' || $this->session->userdata('profileId') == 'E')
    {
      $data['navbar'] = $this->load->view('partials/user/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/user/menubar', '', true);
    }    
    $data['profileId'] = $this->session->userdata('profileId');

    $this->load->view('projects', $data);
  }

  public function getList()
  {
    if($this->session->userdata('profileId') == 'A')
      $list = $this->Project_model->getList();
    else
      if($this->session->userdata('profileId') == 'T')
        $list = $this->Project_model->getListByTester($this->session->userdata('userId'));
      else
        $list = $this->Project_model->getListByClient($this->session->userdata('clientId'));

    $res = [];
    foreach($list as $r) {
      $c = $this->Client_model->get($r->clientId);
      $s = $this->Projectstate_model->get($r->stateId);
      
      $res[] = array('id'           => $r->id,
                     'clientId'     => $r->clientId,
                     'clientName'   => ($c ? $c->name : ''),
                     'name'         => $r->name,
                     'stateId'      => $r->stateId,
                     'state'        => $s->name,
                     'report'       => ($r->reportName ? $r->reportName : ''),
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function getListByClient($clientId)
  {
    if($this->session->userdata('profileId') == 'A')
      $list = $this->Project_model->getListByClient($clientId);
    else
      $list = $this->Project_model->getListByClientAndTester($clientId, $this->session->userdata('userId'));

    $res = [];
    foreach($list as $r) {
      $c = $this->Client_model->get($r->clientId);
      $s = $this->Projectstate_model->get($r->stateId);

      $res[] = array('id'           => $r->id,
                     'clientId'     => $r->clientId,
                     'clientName'   => ($c ? $c->name : ''),
                     'name'         => $r->name,
                     'stateId'      => $r->stateId,
                     'state'        => $s->name,
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function getIncidents($projectId)
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('ATGE', $this->session->userdata('profileId')) === false)
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

    if($this->session->userdata('profileId') == 'G' || $this->session->userdata('profileId') == 'E')
    {
      $data['navbar'] = $this->load->view('partials/user/navbar', '', true);
      $data['menubar'] = $this->load->view('partials/user/menubar', '', true);
    }    
    $data['project'] = $this->Project_model->get($projectId);
    $data['profileId'] = $this->session->userdata('profileId');

    $this->load->view('incidents', $data);    
  }  

  public function create()
  {
    $data['title'] = 'New Project';
    $data['project'] = new Project();
    $data['clients'] = $this->Client_model->getList();
    $data['states'] = $this->Projectstate_model->getList();
    $data['types'] = $this->Projecttype_model->getList();

    $this->load->view('partials/projectsForm', $data);
  }

  public function edit()
  {
    $data['title'] = 'Edit Project';
    $project = $this->Project_model->get($this->input->get('id', TRUE));
    
    $data['project'] = $project;    
    $data['clients'] = $this->Client_model->getList();
    $data['states'] = $this->Projectstate_model->getList();
    $data['types'] = $this->Projecttype_model->getList();

    $type = $this->Projecttype_model->get($project->typeId);
    $stages = explode(';', trim($type->stages));
    $retStages = array();
    foreach ($stages as $stage)
      if($stage != '')
        $retStages[] = (object)array('id' => $stage, 'name' => $stage);

    $data['stages'] = $retStages;
    $this->load->view('partials/projectsForm', $data);
  }

  public function save()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('initDate', 'Init date', 'required');
    $this->form_validation->set_rules('stateId', 'State', 'required|greater_than[0]');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('typeId', 'Type', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $result = array('status' => 'error', 'message' => validation_errors());
    }
    else
    {        
      $project = new Project();
      $id = (int)$this->security->xss_clean($this->input->post("id"));
      if($id != 0)
        $project = $this->Project_model->get($id);
      
      $project->name = $this->security->xss_clean($this->input->post("name"));
      $project->clientId = $this->security->xss_clean($this->input->post("clientId"));
      $project->initDate = date('Y-m-d H:i:s',strtotime($this->security->xss_clean($this->input->post("initDate"))));
      $project->stateId = $this->security->xss_clean($this->input->post("stateId"));
      $project->description = $this->security->xss_clean($this->input->post("description"));
      $project->scope = $this->security->xss_clean($this->input->post("scope"));
      $project->services = $this->security->xss_clean($this->input->post("services"));
      $project->typeId = $this->security->xss_clean($this->input->post("typeId"));
      $project->templateReport = $this->security->xss_clean($this->input->post("reportTemplate"));

      if($id != 0)
      {
        if($project->stageId != $this->security->xss_clean($this->input->post("stageId")))
        {
          $project->stageId = $this->security->xss_clean($this->input->post("stageId"));
          
          $log = new Log();   
          $log->projectId = $id;  
          $log->incidentId = 0;
          $log->date = date('Y-m-d H:i:s');
          $log->stageId = $project->stageId;
          $log->stateId = $this->security->xss_clean($this->input->post("stateId"));
          $log->detail = 'Change Project Stage';
          $log->userId = $this->session->userdata('userId');
          $this->Log_model->save($log);
        }
      }
      else
      {
        $type = $this->Projecttype_model->get($project->typeId);
        $stages = explode(';', $type->stages);
        if($stages)
          $project->stageId = $stages[0];
      }

      if($this->Project_model->save($project))
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
      $data['title'] = 'Delete Project';
      $data['project'] = $this->Project_model->get($this->input->get('id', TRUE));
      $data['delete'] = true;

      $this->load->view('partials/projectsForm', $data);
    }
    else
    {
      if($this->Project_model->delete($this->security->xss_clean($this->input->post("id"))))
        $result = array('status' => 'ok', 'message' => 'Project was been deleted');
      else
        $result = array('status' => 'error', 'message' => 'Failed to delete');

      $this->output->set_content_type('application/json');
      echo json_encode($result);    
    }
  }

  public function reportupload()
  {
    $data['title'] = 'Report Upload';
    $project = $this->Project_model->get($this->input->get('id', TRUE));
    
    $data['project'] = $project;    
    $this->load->view('partials/projectsUpload', $data);
  }

  public function reportUpload2()
  {
    $project = $this->Project_model->get($this->security->xss_clean($this->input->post("id")));
    $file_element_name = 'upload';
   
    $this->load->helper('file');

    $allowed_mime_type_arr = array('application/pdf');
    $mime = get_mime_by_extension($_FILES[$file_element_name]['name']);

    if(isset($_FILES[$file_element_name]['name']) && $_FILES[$file_element_name]['name'] != "")
    {
      if(in_array($mime, $allowed_mime_type_arr))
      {
        $config['upload_path'] = './uploads/reports/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 1024 * 8;
        $config['file_name'] = $this->security->xss_clean($this->input->post("id")) . '_' . time() . '.pdf';    
   
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_element_name))
        {
          $data = $this->upload->data();
          $project->reportName = $data['file_name'];
          $this->Project_model->save($project);
          $result = array('status' => 'ok', 'message' => 'Report uploaded.');
        }
        else 
        {
          $result = array('status' => 'error', 'message' => 'Failed to upload report file. ' . $this->upload->display_errors('',''));
        }
        @unlink($_FILES[$file_element_name]);
      }
      else
      {
        $result = array('status' => 'error', 'message' => 'Please select only pdf file.');
      }
    }
    else
    {
      $result = array('status' => 'error', 'message' => 'Please choose a file to upload.');      
    }
    $this->output->set_content_type('application/json');
    echo json_encode($result);        
  }

  public function testers()
  {
    $data['title'] = 'Testers Assignment';
    $data['project'] = $this->Project_model->get($this->input->get('id', TRUE));
    
    $list = $this->User_model->getListTesters();
    $res = '{'; //{ "1": "test 1", "2": "test 2" };
    foreach($list as $r) {
      $res .= '"' . $r->id . '": "' . $r->name . '",';
    }
    $res .= '}';
    $data['users'] = $res;

    $list = $this->Projecttester_model->getListByProject($this->input->get('id', TRUE));
    $res = [];
    foreach($list as $r) {

      $res[] = array('id'           => $r->userId,
                     'name'         => $this->User_model->get($r->userId)->name,
                     'active'       => $r->active,
                     'DT_RowId'     => $r->userId);
    }            
    $data['projectusers'] = json_encode($res);

    $this->load->view('partials/projectsTestersForm', $data);
  }  

  public function testerssave()
  {
    $data = json_decode(trim(file_get_contents('php://input')), true);
    $projectId = $data[0];
    foreach($data[1] as $i => $item) 
    {
      $tester = new ProjectTester();
      $tester->projectId = $projectId;
      $tester->userId = $data[1][$i][0];
      $tester->active = ($data[1][$i][1] == 1 ? true : false);
      $this->Projecttester_model->save($tester);
    }

    $result = array('status' => 'ok', 'message' => 'Data was saved');
    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function importhosts()
  {
    $file_element_name = 'upload';

    $config['upload_path'] = './tmp/';
    $config['allowed_types'] = 'xml|txt';
    $config['max_size'] = 1024 * 8;
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);

    if(!empty($_FILES["upload"]["name"]))
    {
      if ($this->upload->do_upload($file_element_name))
      {
        $data = $this->upload->data();        

        //should detect plugin to use
        $this->load->library('nmapimport');
        $result = $this->nmapimport->parsexml($data['full_path']);

        @unlink($_FILES[$file_element_name]);
        @unlink($data['full_path']);

        $result = array('status' => 'ok', 'message' => 'Host loaded', 'result' => $result);
        $this->output->set_content_type('application/json');
        echo json_encode($result);        
        exit;        
      }
      else 
      {
        $result = array('status' => 'error', 'message' => 'Failed to upload attach file. ' . $this->upload->display_errors());
        $this->output->set_content_type('application/json');
        echo json_encode($result);        
        exit;
      }
    }
    else
    {
      $result = array('status' => 'error', 'message' => 'File is empty');
      $this->output->set_content_type('application/json');
      echo json_encode($result);        
      exit;      
    }
  }
}
