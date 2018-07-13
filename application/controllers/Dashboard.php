<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
		
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Project_model', 'Incident_model', 'Notification_model'));
    $this->load->library(array('session'));
    $this->load->helper(array('form_helper'));
  }	

	public function index()
	{
		if($this->session->userdata('is_logged_in') == FALSE)
			redirect(base_url().'login');

		if($this->session->userdata('profileId') == 'A')
		{
			$data['navbar'] = $this->load->view('partials/admin/navbar', '', true);
			$data['menubar'] = $this->load->view('partials/admin/menubar', '', true);
      $data['activeprojects'] = $this->Project_model->getCountActive();
      $data['lowincidents'] = $this->Incident_model->getCountLow();
      $data['modincidents'] = $this->Incident_model->getCountMod();
      $data['higincidents'] = $this->Incident_model->getCountHig();
		}

		if($this->session->userdata('profileId') == 'T')
		{
			$data['navbar'] = $this->load->view('partials/tester/navbar', '', true);
			$data['menubar'] = $this->load->view('partials/tester/menubar', '', true);
      $data['activeprojects'] = $this->Project_model->getCountActive($this->session->userdata('userId'));
      $data['lowincidents'] = $this->Incident_model->getCountLow($this->session->userdata('userId'));
      $data['modincidents'] = $this->Incident_model->getCountMod($this->session->userdata('userId'));
      $data['higincidents'] = $this->Incident_model->getCountHig($this->session->userdata('userId'));      
		}

		if($this->session->userdata('profileId') == 'G' || $this->session->userdata('profileId') == 'E')
		{
			$data['navbar'] = $this->load->view('partials/user/navbar', '', true);
			$data['menubar'] = $this->load->view('partials/user/menubar', '', true);
      $data['activeprojects'] = $this->Project_model->getCountActive($this->session->userdata('userId'));
      $data['lowincidents'] = $this->Incident_model->getCountLow($this->session->userdata('userId'));
      $data['modincidents'] = $this->Incident_model->getCountMod($this->session->userdata('userId'));
      $data['higincidents'] = $this->Incident_model->getCountHig($this->session->userdata('userId')) + $this->Incident_model->getCountCrit($this->session->userdata('userId'));            
		}

    $notLis = $this->Notification_model->getListByUser($this->session->userdata('userId'), 10, true);
    $notif = array();
    foreach($notLis as $r)
    {
      $p = $this->Project_model->get($r->projectId);
      $u = $this->User_model->get($r->fromUserId);

      $notif[] = array('projectId'    => $r->projectId,
                       'projectName'  => $p->name,
                       'userName'     => ($u ? $u->name : ''),
                       'event'        => $r->event,
                       'date'         => $r->date);
    }
    $data['notifications'] = $notif;

    $last = $this->Incident_model->getLastActivity($this->session->userdata('userId'), 10);   
    $alast = array();
    foreach($last as $r)
    {
      $p = $this->Project_model->get($r->projectId);
      $u = $this->User_model->get($r->userId);

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

      $alast[] = array('projectId'    => $r->projectId,
                       'projectName'  => $p->name,
                       'userName'     => $u->name,
                       'description'  => $r->description,
                       'cvss'         => $r->cvss,
                       'risk'         => $risk,
                       'date'         => $r->date);
    }

    $data['lastactivity'] = $alast;
    
		$this->load->view('dashboard', $data);
	}
}
