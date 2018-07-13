<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incidents extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model', 'Project_model', 'Projectstate_model', 'Projecttype_model', 
      'Incident_model', 'Incidenttype_model', 'Incidentstate_model', 'Objectivetype_model', 'Log_model'));
    
    $this->load->library(array('session', 'word'));
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

    $this->load->view('projects', $data);
  }

  public function getListByProject($projectId)
  {
    if(strpos('AT', $this->session->userdata('profileId')) === false)
      $list = $this->Incident_model->getListConfirmedByProject($projectId);      
    else
      $list = $this->Incident_model->getListByProject($projectId);

    $res = [];
    foreach($list as $r) {
      $type = $this->Incidenttype_model->get($r->typeId);
      $state = $this->Incidentstate_model->get($r->stateId);
      
      $risk = '';
      if($r->cvss >= 9)
        $risk = 'C';
      else
        if($r->cvss >= 7 && $r->cvss <= 8.9)
          $risk = 'H';        
        else
          if($r->cvss >= 4 && $r->cvss <= 6.9)
            $risk = 'M';
          else
            if($r->cvss >= 1 && $r->cvss <= 3.9)
              $risk = 'L';
            else
              $risk = 'I';

      $res[] = array('id'           => $r->id,                      
                     'date'         => date("m-d-Y", strtotime($r->date)),
                     'type'         => ($type ? $type->name : ''),
                     'description'  => $r->description,
                     'state'        => ($state ? $state->name : ''),
                     'risk'         => $risk,
                     'cvss'         => $r->cvss,                    
                     'DT_RowId'     => $r->id);
    }            

    $result = array ('sEcho'                => 1,                          
                     'iTotalRecords'        => sizeof($list),                          
                     'iTotalDisplayRecords' => sizeof($list),
                     'aaData'               => $res);              

    $this->output->set_content_type('application/json');
    echo json_encode($result);
  }

  public function chart($projectId)
  {
    $res = [];
    $res[] = array('label'     => 'Critical',
                   'value'     => $this->Incident_model->getCountCrit($this->session->userdata('userId'), $projectId));    
    $res[] = array('label'     => 'High',
                   'value'     => $this->Incident_model->getCountHig($this->session->userdata('userId'), $projectId));
    $res[] = array('label'     => 'Medium',
                   'value'     => $this->Incident_model->getCountMod($this->session->userdata('userId'), $projectId));
    $res[] = array('label'     => 'Low',
                   'value'     => $this->Incident_model->getCountLow($this->session->userdata('userId'), $projectId));
    $res[] = array('label'     => 'Info',
                   'value'     => $this->Incident_model->getCountInfo($this->session->userdata('userId'), $projectId));

    $this->output->set_content_type('application/json');
    echo json_encode($res); 
  }

  public function moneychart($projectId)
  {
    $res = [];
    $res[] = array('y' => '$', 
                        'a' => $this->Incident_model->getCountCrit($this->session->userdata('userId'), $projectId) * 5000, 
                        'b' => $this->Incident_model->getCountHig($this->session->userdata('userId'), $projectId) * 3000, 
                        'c' => $this->Incident_model->getCountMod($this->session->userdata('userId'), $projectId) * 1000, 
                        'd' => $this->Incident_model->getCountLow($this->session->userdata('userId'), $projectId) * 500, 
                        'e' => $this->Incident_model->getCountInfo($this->session->userdata('userId'), $projectId) * 0
                      );

    $this->output->set_content_type('application/json');
    echo json_encode($res); 
  }

  public function incidentschart($projectId)
  {
    $counts = $this->Incident_model->getCountByLevel($projectId);

    $res = [];
    foreach($counts as $r) {
      $res[] = array('y' => $r['date'], 
                        'a' => $r['crit'], 
                        'b' => $r['high'], 
                        'c' => $r['med'], 
                        'd' => $r['low'], 
                        'e' => $r['info']
                      );
    }      

    $this->output->set_content_type('application/json');
    echo json_encode($res); 
  }

  public function getLogs($incidentId)
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('ATE', $this->session->userdata('profileId')) === false)
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }

    $data['title'] = 'Incident Tracing';
    $data['incident'] = $this->Incident_model->get($incidentId);  

    $this->load->view('partials/incidentsLog', $data);    
  }

  public function getLogDetail($logId)
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

    if(strpos('ATE', $this->session->userdata('profileId')) === false)
    {
      $this->session->set_flashdata('incorrect_credentials', 'You do not have permissions for the requested action');
      redirect(base_url());
    }

    $data['title'] = 'Incident Log Detail';
    $data['log'] = $this->Log_model->get($logId);  

    $this->load->view('partials/incidentsLogDetail', $data);    
  }

  public function create($projectId)
  {
    $incident = new Incident();    
    $incident->projectId = $projectId;
    $incident->stateId = 1;

    $data['title'] = 'New Incident';
    $data['incident'] = $incident;    
    $data['types'] = $this->Incidenttype_model->getList();
    $data['states'] = $this->Incidentstate_model->getList();
    $data['objectiveTypes'] = $this->Objectivetype_model->getList();

    $this->load->view('partials/incidentsForm', $data);
  }

  public function view($incidentId)
  {
    $incident = $this->Incident_model->get($incidentId);

    $list = $this->Log_model->getListByIncident($incidentId);
    $res = [];
    foreach($list as $r) {
      //preg_match("/.*<body[^>]*>(.*)<\/body>.*/is", $r->detail, $x);      
      //$htmltoconvert = $body[1];      
      //var_dump($x);
      $res[] = array('date'         => date("m-d-Y", strtotime($r->date)),
                     'detail'       => $r->detail,
                     'DT_RowId'     => $r->id);
    }            

    $data['title'] = "Incident view";
    $data['incident'] = $incident;
    $data['incidenttype'] = $this->Incidenttype_model->get($incident->typeId);
    $data['log'] = json_encode($res);

    $this->load->view('partials/incidentsView', $data); 
  }

  public function edit()
  {
    $data['title'] = 'Edit Incident';
    $data['incident'] = $this->Incident_model->get($this->input->get('id', TRUE));
    $data['types'] = $this->Incidenttype_model->getList();    
    $data['states'] = $this->Incidentstate_model->getList();
    $data['objectiveTypes'] = $this->Objectivetype_model->getList();

    $this->load->view('partials/incidentsForm', $data);
  }

  public function save()
  {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('date', 'Date', 'required');
    $this->form_validation->set_rules('typeId', 'Type', 'required|greater_than[0]');
    $this->form_validation->set_rules('objective', 'Objective', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $result = array('status' => 'error', 'message' => validation_errors());
    }
    else
    {    
      $incident = new Incident();
      $id = (int)$this->security->xss_clean($this->input->post("id"));
      if($id != 0)
        $incident = $this->Incident_model->get($id);

      $incident->projectId = $this->security->xss_clean($this->input->post("projectId"));
      $incident->date = date('Y-m-d H:i:s', strtotime($this->security->xss_clean($this->input->post("date"))));
      $incident->typeId = $this->security->xss_clean($this->input->post("typeId"));
      $incident->cvss = number_format($this->security->xss_clean($this->input->post("cvss")), 1, '.', '');
      $incident->objectiveTypeId = $this->security->xss_clean($this->input->post("objectiveTypeId"));
      $incident->objective = $this->security->xss_clean($this->input->post("objective"));
      $incident->description = $this->security->xss_clean($this->input->post("description"));    
      $incident->stateId = $this->security->xss_clean($this->input->post("stateId2"));
      $incident->detail = $this->input->post("detail");
      $incident->abstract = $this->input->post("abstract");
      $incident->suggestion = $this->input->post("suggestion");
      $incident->userId = $this->session->userdata('userId');

      $file_element_name = 'upload';

      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png|doc|txt';
      $config['max_size'] = 1024 * 8;
      $config['encrypt_name'] = TRUE;
 
      $this->load->library('upload', $config);

      if(!empty($_FILES["upload"]["name"]))
      {
        if ($this->upload->do_upload($file_element_name))
        {
          $data = $this->upload->data();
          $incident->attach = $data['file_name'];
        }
        else 
        {
          $result = array('status' => 'error', 'message' => 'Failed to upload attach file. ' . $this->upload->display_errors());
          $this->output->set_content_type('application/json');
          echo json_encode($result);        
          exit;
        }
        @unlink($_FILES[$file_element_name]);
      }

      if($id == 0)
      {
        $project = $this->Project_model->get($this->security->xss_clean($this->input->post("projectId")));
        $incident->stageId = $project->stageId;
      }

      if($this->Incident_model->save($incident))
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
      $data['title'] = 'Delete Incident';
      $data['incident'] = $this->Incident_model->get($this->input->get('id', TRUE));
      $data['delete'] = true;

      $this->load->view('partials/incidentsForm', $data);
    }
    else
    {
      $incident = $this->Incident_model->get($this->input->post("id"));
      if($incident)
        if($incident->attach)
          unlink('./uploads/' . $incident->attach);
        
      if($this->Incident_model->delete($this->security->xss_clean($this->input->post("id"))))
        $result = array('status' => 'ok', 'message' => 'Incident was been deleted');
      else
        $result = array('status' => 'error', 'message' => 'Failed to delete');

      $this->output->set_content_type('application/json');
      echo json_encode($result);    
    }
  }

  public function zapplugin($projectId, $plugin)
  {
    $config = array('url'         => 'incidents/zapplugin/' . $projectId . '/' . $plugin, 
                    'upload_path' => './tmp/' );
    $this->load->library($plugin, $config, 'myplugin');

    if(!$this->security->xss_clean($this->input->post("process")))
    {
      $this->output->set_output($this->myplugin->show());    
    }
    else 
    {
      $result = $this->myplugin->process();
      if($result['status'] == 'ok')
      {
        $project = $this->Project_model->get($projectId);

        $data = json_decode($result['json'], true);
        for ($i=0; $i < sizeof($data); $i++) 
        { 
          for ($j=0; $j < sizeof($data[$i]['alerts']); $j++) 
          {
            $risk = 0;
            switch ($data[$i]['alerts'][$j]['riskcode']) {
              case 0:
                $risk = 0;
                break;
              case 1:
                $risk = 1;
                break;
              case 2:
                $risk = 4;
                break;
              case 3:
                $risk = 7;
                break;
              case 4:
                $risk = 9;
                break;
            }  

            $incident = new Incident();
            $incident->projectId = $projectId;
            $incident->date = date('Y-m-d H:i:s');
            $incident->typeId = 0;
            $incident->cvss = $risk;
            $incident->objectiveTypeId = 0;
            $incident->objective = $data[$i]['site'];
            $incident->description = $data[$i]['alerts'][$j]['alert'];    
            $incident->stateId = 1;
            $incident->detail = $data[$i]['alerts'][$j]['desc'];
            $incident->abstract = $data[$i]['alerts'][$j]['desc'];
            $incident->suggestion = $data[$i]['alerts'][$j]['solution'];
            $incident->userId = $this->session->userdata('userId');
            $incident->stageId = $project->stageId;
            $this->Incident_model->save($incident);
          }
        }
      }
      $this->output->set_content_type('application/json');
      echo json_encode($result); 
    }
  }
}