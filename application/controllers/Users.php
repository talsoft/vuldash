<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model', 'Project_model', 'Notification_model', 'Projectstate_model'));
    $this->load->library(array('session'));
    $this->load->helper(array('form_helper'));
  } 

  public function index($type)
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
    $data['type'] = $type;

    $this->load->view('users', $data);
  }

  public function getList()
  {
    $list = $this->User_model->getListInternal();

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

  public function getListByClient($clientId)
  {
    $list = $this->User_model->getListByClient($clientId);

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

  public function create($clientId)
  {
    $data['title'] = 'New User';
    $data['user'] = new User();

    if($clientId == '0')
    {
      $this->load->view('partials/adminsForm', $data);
    }
    else
    {
      $data['clients'] = $this->Client_model->getList();
      $data['clientId'] = $clientId;
      $this->load->view('partials/usersForm', $data);
    }
  }

  public function edit()
  {
    $user = $this->User_model->get($this->input->get('id', TRUE));

    $data['title'] = 'Edit User';
    $data['user'] = $user;

    if($user->profileId == 'G' || $user->profileId == 'E')
    {
      $data['clients'] = $this->Client_model->getList();
      $data['clientId'] = $user->clientId;      
      $this->load->view('partials/usersForm', $data);
    }
    else
    {
      $this->load->view('partials/adminsForm', $data);
    }
  }

  public function save()
  {
    //Validate data
    $this->load->library('form_validation');

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('mail', 'Mail', 'required');
    $this->form_validation->set_rules('profile', 'Profile', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $result = array('status' => 'error', 'message' => validation_errors());
    }
    else
    {       
      if($this->User_model->getByUsername($this->security->xss_clean($this->input->post("mail"))) && (int)$this->security->xss_clean($this->input->post("id")) == 0)
      {
        $result = array('status' => 'error', 'message' => 'The mail is already registered');
      }
      else
      {
        $user = new User();
        $id = (int)$this->security->xss_clean($this->input->post("id"));
        if($id != 0)
          $user = $this->User_model->get($id);

        $user->name = $this->security->xss_clean($this->input->post("name"));
        $user->username = $this->security->xss_clean($this->input->post("mail"));
        $user->profileId = $this->security->xss_clean($this->input->post("profile"));

        if($this->security->xss_clean($this->input->post("type")) == 'A')
          $user->clientId = 0;
        else
          $user->clientId = $this->security->xss_clean($this->input->post("clientId"));

        if($id == 0)
        {
          $user->password = sha1($this->randomPassword(8));
          $user->hash = sha1(rand(0,100000));
          $user->active = false;
        }

        if($this->User_model->save($user))
          if($id == 0)
          {
            if($this->sendmail2($user))
              $result = array('status' => 'ok', 'message' => 'The data was saved and a mail was sent to the user');
            else
              $result = array('status' => 'ok', 'message' => 'The data was saved but the mail could not be sent to the user');
          }
          else
            $result = array('status' => 'ok', 'message' => 'The data was saved'); 
        else 
          $result = array('status' => 'error', 'message' => 'Failed to save data');
      }    
    }
    $this->output->set_content_type('application/json');
    echo json_encode($result);    
  }

  public function delete()
  {
    if(!$this->security->xss_clean($this->input->post("delete")))
    {
      $user = $this->User_model->get($this->input->get('id', TRUE));
      
      $data['title'] = 'Delete User';
      $data['user'] = $user;
      $data['delete'] = true;

      if($user->profileId == 'G' || $user->profileId == 'E')
      {
        $this->load->view('partials/usersForm', $data);
      } 
      else
      {
        $this->load->view('partials/adminsForm', $data);
      }
    }
    else
    {
      if($this->User_model->delete($this->security->xss_clean($this->input->post("id"))))
        $result = array('status' => 'ok', 'message' => 'User was been deleted');
      else
        $result = array('status' => 'error', 'message' => 'Failed to delete');

      $this->output->set_content_type('application/json');
      echo json_encode($result);    
    }
  } 

  public function sendmail() {
    $user = $this->User_model->get($this->input->get('id', TRUE));

    if($this->sendmail2($user))
        $result = array('status' => 'ok', 'message' => 'Email Send Successfully.');
    else
        $result = array('status' => 'error', 'message' => 'Send failed');

    $this->output->set_content_type('application/json/');
    echo json_encode($result);          
  }  

  public function activate($user, $pass) {
    $user = $this->User_model->getByHashAndPass($user, $pass);

    if($user)      
      $data['user'] = $user;
    else
      $data['user'] = '';

    $this->load->view('activate', $data);
  }

  public function doactivate()
  {
    $user = $this->User_model->get((int)$this->security->xss_clean($this->input->post("id")));

    $data['user'] = $user;
    if($user->username !== $this->security->xss_clean($this->input->post("username")))
    {
      $this->session->set_flashdata('incorrect_username', 'The email account is not valid');
    }
    else
    {
      $pass1 = $this->security->xss_clean($this->input->post("password1"));
      $pass2 = $this->security->xss_clean($this->input->post("password2"));
      $passwordErr = '';

      if(!empty($pass1) && ($pass1 == $pass2)) 
      {
        if (strlen($pass1) <= '8' || 
            !preg_match("#[0-9]+#", $pass1) ||
            !preg_match("#[A-Z]+#",$pass1) ||
            !preg_match("#[a-z]+#",$pass1))   
        {
          $passwordErr = "Your Password must contain at least 8 characters, 1 number, 1 Capital Letter and 1 Lowercase Letter";
        }
      }
      elseif(!empty($pass1)) 
      {
        $passwordErr = "Please Check You've Entered Or Confirmed Your Password!";
      } 
      else 
      {
        $passwordErr = "Please enter password   ";
      }

      if($passwordErr)
      {
        $this->session->set_flashdata('incorrect_password', $passwordErr);
      }
      else
      {
        $user->password = sha1($pass1);
        $user->active = true;
        $this->User_model->save($user);

        $this->session->set_flashdata('correct_activate', 'The account has been activated');
      }
    }
    $this->load->view('activate', $data);
  }

  public function forgetpass()
  {
    $data['return'] = 0;
    $this->load->view('forgetpass', $data);
  }

  public function recover() 
  {
    $user = $this->User_model->getByUsername($this->security->xss_clean($this->input->post("username")));
    if($user)
    {
      $this->load->library('email');

      $from_email = $this->config->item('sendmail_from');
      $to_email = $user->username;

      $config = array(
          'protocol' => $this->config->item('sendmail_protocol'),
          'smtp_host' => $this->config->item('sendmail_smtp_host'),
          'smtp_port' => $this->config->item('sendmail_smtp_port'),
          'smtp_user' => $this->config->item('sendmail_smtp_user'),
          'smtp_pass' => $this->config->item('sendmail_smtp_pass'),
          'mailtype' => $this->config->item('sendmail_mailtype'),
          'charset' => $this->config->item('sendmail_charset'),
          'newline' => $this->config->item('sendmail_newline'),
          'crlf' => $this->config->item('sendmail_crlf')
      );
      $this->email->initialize($config);

      $this->email->from($from_email, 'Vuldash');
      $this->email->to($to_email);
      $this->email->subject($this->config->item('forgetpass_subject'));

      $message = $this->config->item('forgetpass_message');
      $message = str_replace("@link", base_url('users/change/' .  $user->hash . '/' . $user->password), $message);
      $this->email->message($message);    
      var_dump($message);

      //Send mail
      $res = $this->email->send();
      if(!$res)        
        $this->session->set_flashdata('incorrect_recover', 'There was an error sending the mail');
      else
        $this->session->set_flashdata('correct_recover', 'The email has been sent');
    }
    else
    {
      $this->session->set_flashdata('incorrect_recover', 'The email account is not valid');
    }
    $data['return'] = 1;
    $this->load->view('forgetpass', $data);
  }

  public function change($user, $pass) {
    $user = $this->User_model->getByHashAndPass($user, $pass);

    if($user)      
      $data['user'] = $user;
    else
      $data['user'] = '';

    $this->load->view('changepass', $data);
  }

  function sendmail2($user) {    
    $this->load->library('email');

    $from_email = $this->config->item('sendmail_from');
    $to_email = $user->username;

    $config = array(
        'protocol' => $this->config->item('sendmail_protocol'),
        'smtp_host' => $this->config->item('sendmail_smtp_host'),
        'smtp_port' => $this->config->item('sendmail_smtp_port'),
        'smtp_user' => $this->config->item('sendmail_smtp_user'),
        'smtp_pass' => $this->config->item('sendmail_smtp_pass'),
        'mailtype' => $this->config->item('sendmail_mailtype'),
        'charset' => $this->config->item('sendmail_charset'),
        'newline' => $this->config->item('sendmail_newline'),
        'crlf' => $this->config->item('sendmail_crlf')
    );
    $this->email->initialize($config);

    $this->email->from($from_email, 'Vuldash');
    $this->email->to($to_email);
    $this->email->subject($this->config->item('confirmation_subject'));

    $message = $this->config->item('confirmation_message');
    $message = str_replace("@link", base_url('users/activate/' .  $user->hash . '/' . $user->password), $message);
    $this->email->message($message);    

    //Send mail
    $res = $this->email->send();
    //if(!$res)
    //  echo $this->email->print_debugger();
    return $res;
  }

  function randomPassword($len) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $len; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }  

  function profile() 
  {
    if($this->session->userdata('is_logged_in') == FALSE)
      redirect(base_url().'login');

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
    
    $data['user'] = $this->User_model->get($this->session->userdata('userId'));
    $projects = $this->Project_model->getListByUser($this->session->userdata('userId'));
    $aprojects = array();
    foreach($projects as $r)
    {
      $s = $this->Projectstate_model->get($r->stateId);

      $aprojects[] = array('projectId'    => $r->id,
                           'projectName'  => $r->name,
                           'initDate'     => date("m-d-Y", strtotime($r->initDate)),
                           'state'        => $s->name);
    }
    $data['projects'] = $aprojects;

    $notLis = $this->Notification_model->getListByUser($this->session->userdata('userId'));
    $notif = array();
    foreach($notLis as $r)
    {
      $p = $this->Project_model->get($r->projectId);
      $u = $this->User_model->get($r->fromUserId);

      $notif[] = array('projectId'    => $r->projectId,
                       'projectName'  => $p->name,
                       'userName'     => $u->name,
                       'event'        => $r->event,
                       'readed'       => $r->readed,
                       'date'         => $r->date);
    }
    $data['notifications'] = $notif;    
    $this->load->view('userprofile', $data);
  }
}
