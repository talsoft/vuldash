<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
  
  function __construct() 
  {
    parent::__construct();

    $this->load->library(array('session', 'form_validation'));
    $this->load->model('user_model');
    $this->load->helper('form_helper');
  } 

  public function index()
  {
    $data['token'] = $this->token();  
    $data['googlesitekey'] = $this->config->item('google_site_key'); 
    $data['use_googlecaptcha'] = $this->config->item('use_googlecaptcha');
    $this->load->view('login', $data);
  }

  public function new_user()
  {
    if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token'))
    {
      $this->form_validation->set_rules('username', 'Username', 'required');
      $this->form_validation->set_rules('password', 'Password', 'required');
      if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) && $this->config->item('use_googlecaptcha'))
        $this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');

      if($this->form_validation->run() == FALSE)
      {
        $this->index();        
      }
      else
      {
        $username = $this->input->post('username');
        $password = sha1($this->input->post('password'));

        $check_user = $this->user_model->login_user($username, $password);
        if($check_user == TRUE)
        {
          $data = array(
                        'is_logged_in' => TRUE,
                        'userId' => $check_user->id,
                        'profileId' => $check_user->profileId,
                        'userName' => $check_user->username,
                        'name' => $check_user->name,
                        'clientId' => $check_user->clientId,
                        );
          $this->session->set_userdata($data);
          redirect(base_url().'dashboard');
        }
      }
    }
    else
    {
      redirect(base_url().'login');
    }
  }

  public function recaptcha($str)
  {
    $google_url = "https://www.google.com/recaptcha/api/siteverify";
    $secret = $this->config->item('google_secret_key');
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = $google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
    $res = curl_exec($curl);
    curl_close($curl);
    $res= json_decode($res, true);
    //reCaptcha success check
    if($res['success'])
    {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
      return FALSE;
    }
  }

  public function token()
  {
    $token = md5(uniqid(rand(), true));
    $this->session->set_userdata('token', $token);
    return $token;
  }

  public function logout()
  {
    $this->session->sess_destroy();
    $this->index();
  }
}
