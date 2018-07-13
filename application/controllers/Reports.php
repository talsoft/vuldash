<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    
  function __construct() 
  {
    parent::__construct();

    $this->load->model(array('User_model', 'Client_model', 'Project_model', 'Projectstate_model', 'Projecttype_model', 
      'Incident_model', 'Incidenttype_model', 'Incidentstate_model', 'Objectivetype_model', 'Log_model', 'Projecttester_model'));
    
    $this->load->library(array('session', 'word'));
    $this->load->helper(array('form_helper'));
  } 

  public function incident($incidentId)
  {        
    $toDelete = array();

    $incident = $this->Incident_model->get($incidentId);
    $project = $this->Project_model->get($incident->projectId);
    $client = $this->Client_model->get($project->clientId);
    $type = $this->Incidenttype_model->get($incident->typeId);
    $objectivetype = $this->Objectivetype_model->get($incident->objectiveTypeId);
    $incidentStates = $this->Log_model->getListByIncident($incidentId);
    $incidenttype = $this->Incidenttype_model->get($incident->typeId);

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $phpWord->getCompatibility()->setOoxmlVersion(14);
    $phpWord->getCompatibility()->setOoxmlVersion(15);
    
    $filename = 'report' . uniqid(rand(), true) . '.docx';

    $docname = 'assets/odt-templates/template_incident_sp.docx';
    if(trim($project->templateReport) != '')
      $docname = 'assets/odt-templates/template_incident_' . trim($project->templateReport) . '.docx';

    $document = $phpWord->loadTemplate($docname);

    $document->setValue('project_name', $project->name);
    $document->setValue('client_name', $client->name);

    $dataIncidentType = $this->parse($incidenttype->description);
    $document->setValue('incident_description', $dataIncidentType['text']);

    $dataIncidentDetail = $this->parse($incident->detail);
    $document->setValue('incident_detail', $dataIncidentDetail['text']);

    $dataSuggestion = $this->parse($incident->suggestion);
    $document->setValue('incident_suggestion', $dataSuggestion['text']);

    $dataSolution = $this->parse($incidenttype->solution);
    $document->setValue('incident_solution', $dataSolution['text']);

    $dataReference = $this->parse($incidenttype->reference);
    $document->setValue('incident_reference', $dataReference['text']);

    //Proccess images tags Incident Type
    foreach ($dataIncidentType['images'] as $i) {
      $document->setImg( $i, array('src' => 'tmp/' . $i, 'swh'=>'600'));
      $toDelete[] = 'tmp/' . $i;
    }

    //Proccess images tags Incident Detail
    foreach ($dataIncidentDetail['images'] as $i) {
      $document->setImg( $i, array('src' => 'tmp/' . $i, 'swh'=>'600'));
      $toDelete[] = 'tmp/' . $i;
    }

    //Proccess images tags Incident Suggestion
    foreach ($dataSuggestion['images'] as $i) {
      $document->setImg( $i, array('src' => 'tmp/' . $i, 'swh'=>'600'));
      $toDelete[] = 'tmp/' . $i;
    }

    //Proccess images tags Incident Solution
    foreach ($dataSolution['images'] as $i) {
      $document->setImg( $i, array('src' => 'tmp/' . $i, 'swh'=>'600'));
      $toDelete[] = 'tmp/' . $i;
    }

    //Proccess images tags Incident Reference
    foreach ($dataReference['images'] as $i) {
      $document->setImg( $i, array('src' => 'tmp/' . $i, 'swh'=>'600'));
      $toDelete[] = 'tmp/' . $i;
    }

    $list = $this->Log_model->getListByIncident($incidentId);
    $document->cloneRow('rowDate', sizeof($list));
    $pos = 1;
    foreach($list as $r) {
      $state = $this->Incidentstate_model->get($r->stateId);
      $user  = $this->User_model->get($r->userId);

      $document->setValue('rowDate#' . $pos, date("d-m-Y", strtotime($r->date)));
      $document->setValue('rowUser#' . $pos, $user->name);
      $document->setValue('rowState#' . $pos, $state->name);

      $dataDetail = $this->parse($r->detail);
      $document->setValue('rowDetail#' . $pos, $dataDetail['text']);

      $pos++;
    }                

    $document->saveAs('tmp/'.$filename);

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell >browser what's the file name
    header("Content-Type: application/docx");
    header("Content-Transfer-Encoding: binary");    
    
    //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
    //$objWriter->save('php://output');
    readfile('tmp/'.$filename); // or echo file_get_contents($temp_file);
    unlink('tmp/'.$filename);  

    foreach ($toDelete as $i) {
      unlink($i);
    }
    exit;   
  }

  function project($projectId)
  {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $phpWord->getCompatibility()->setOoxmlVersion(14);
    $phpWord->getCompatibility()->setOoxmlVersion(15);

    $filename = 'report' . uniqid(rand(), true) . '.docx';

    $toDelete = array();

    $project = $this->Project_model->get($projectId); 
    $projecttype = $this->Projecttype_model->get($project->typeId);
    $client = $this->Client_model->get($project->clientId);
    $incidents = $this->Incident_model->getListByProject($projectId);

    //Get testers 
    $listTesters = $this->Projecttester_model->getListByProject($project->id);
    $txtTesters = '';
    foreach ($listTesters as $t) {
      $u = $this->User_model->get($t->userId);
      $txtTesters .= $u->name . ' ';
    }

    $docname = 'assets/odt-templates/template_project_sp.docx';
    if(trim($project->templateReport) != '')
      $docname = 'assets/odt-templates/template_project_' . trim($project->templateReport) . '.docx';

    if($projecttype->stages)
    {
      $stages = explode(';', trim($projecttype->stages));
      if($project->stageId == $stages[sizeof($stages)-1])
      {
        $docname = 'assets/odt-templates/template_projectfinal_sp.docx';
        if(trim($project->templateReport) != '')
          $docname = 'assets/odt-templates/template_projectfinal_' . trim($project->templateReport) . '.docx';
      }
    }

    $document = $phpWord->loadTemplate($docname);

    //with project stage determine the report type to generate
    $stage = '';
    $version = 1;
    if($projecttype->stages)
    {      
      if(!$project->stageId)
        $stage = $stages[0];
      else
        $stage = $project->stageId;

      foreach ($stages as $s) {
        if($s != $stage)
          $version++;
        else
          break;
      }

      $document->cloneRow('ver', $version);    
      for($i = 1; $i <= $version; $i++)
      {
        $log = $this->Log_model->getStageChange($project->id, $stages[$i - 1]);
        $date = '';
        if($log)
          $date = $log->date;

        $document->setValue('ver#' . $i, ($i + 99) . ' ' . $stages[$i - 1]);
        $document->setValue('ver_date#' . $i, date('d-m-Y', strtotime($date)));
        $document->setValue('ver_testers#' . $i, $txtTesters);
      }

      $log = $this->Log_model->getStageChange($project->id, $stages[sizeof($stages)-1]);
      $date = '';
      if($log)
        $date = $log->date;     
      $document->setValue('updated', date('d-m-Y', strtotime($date))); 

      $document->setValue('date1', date("d-m-Y", strtotime($project->initDate)));    
      $document->setValue('date2', date("d-m-Y", strtotime($date)));    
      $document->setValue('date3', date("d-m-Y"));    
    }
    else
    {
      $document->setValue('ver', ($version + 99));
      $document->setValue('ver_date', date("d-m-Y"));
      $document->setValue('ver_testers', $txtTesters);      

      $document->setValue('date1', date("d-m-Y", strtotime($project->initDate)));    
      $document->setValue('date2', date("d-m-Y"));          
    }

    $document->setValue('project_name', $project->name);
    $document->setValue('project_type', $projecttype->name);
    $document->setValue('client_name', $client->name);
    $document->setValue('client_address', $client->address);
    $document->setValue('client_city', $client->city);
    $document->setValue('client_contact', $client->contact);
    $document->setValue('document_type', $project->name . ' ' . strtoupper($stage));    
    $document->setValue('project_initdate', date("d-m-Y", strtotime($project->initDate)));
    $document->setValue('project_enddate', date("d-m-Y"));    
    $document->setValue('document_ver', 'V' . ($version + 99));

    //Calcule average and fill list high and med
    $sum = 0;
    $count = 0;
    $listHigh = '';
    $listMed = '';
    $maxcvssInc = new Incident();
    $maxcvssInc->cvss = 0;

    $risk_counthigh1 = 0;
    $risk_countmed1 = 0;
    $risk_countlow1 = 0;
    $risk_counthigh2 = 0;
    $risk_countmed2 = 0;
    $risk_countlow2 = 0;

    foreach ($incidents as $i) {
      $sum += $i->cvss;
      $count ++;

      if($maxcvssInc->cvss < $i->cvss)
        $maxcvssInc = $i;

      $type = $this->Incidenttype_model->get($i->typeId);
      if($i->cvss >= 7)
        $listHigh = $listHigh . $type->name . '</w:t><w:br/><w:t>';
      else
        if($i->cvss >= 4)
          $listMed = $listMed . $type->name . '</w:t><w:br/><w:t>';

      if($projecttype->stages)
      {
        if($i->stageId == $stages[sizeof($stages)-1])          
        {
          if($i->cvss >= 7)
          {
            $risk_counthigh2++;        
          }
          else
          {
            if($i->cvss >= 4 && $i->cvss <= 6.9)
            {
              $risk_countmed2++;
            }
            else
            {
              if($i->cvss <= 3.9)
                $risk_countlow2++;
            }
          }
        }
        else
        {
          if($i->cvss >= 7)
          {
            $risk_counthigh1++;        
            if($i->stateId == 3)
              $risk_counthigh2++;        
          }
          else
          {
            if($i->cvss >= 4 && $i->cvss <= 6.9)
            {
              $risk_countmed1++;
              if($i->stateId == 3)
                $risk_countmed2++;
            }
            else
            {
              if($i->cvss <= 3.9)
              {
                $risk_countlow1++;          
                if($i->stateId == 3)
                  $risk_countlow2++;          
              }
            }
          }
        }
      }
      else
      {  
        if($i->cvss >= 7)
          $risk_counthigh1++;        
        else
          if($i->cvss >= 4 && $i->cvss <= 6.9)
            $risk_countmed1++;
          else
            if($i->cvss <= 3.9)
              $risk_countlow1++;
      }
    }

    $average = $sum / $count;
    if($average >= 9)      
      $risklevel = 'Critical';
    else
      if($average >= 7 && $average <= 8.9)
        $risklevel = 'High';        
      else
        if($average >= 4 && $average <= 6.9)
          $risklevel = 'Medium';
         else
          if($average <= 3.9)
            $risklevel = 'Low';

    $document->setValue('risk_higher', $maxcvssInc->description);
    $document->setValue('risk_generallevel', $risklevel);

    $document->setValue('risk_counthigh1', $risk_counthigh1);
    $document->setValue('risk_countmed1', $risk_countmed1);
    $document->setValue('risk_countlow1', $risk_countlow1);

    $document->setValue('risk_counthigh2', $risk_counthigh2);
    $document->setValue('risk_countmed2', $risk_countmed2);
    $document->setValue('risk_countlow2', $risk_countlow2);

    $document->setChart('chart1', array(array($risk_counthigh1, $risk_counthigh2), 
                                        array($risk_countmed1, $risk_countmed2), 
                                        array($risk_countlow1, $risk_countlow2)));


    $document->setValue('incidents_listhigh', $listHigh);
    $document->setValue('incidents_listmed', $listMed);

    $projectMetodology = $this->parse($projecttype->metodology);
    $document->setValue('project_metodology', $projectMetodology['text']);

    $document->setValue('project_scope', $project->scope);

    $document->cloneTable('incidenttable', sizeof($incidents));
    $document->setValue('incidenttable', '');
    $count = 1;
    foreach ($incidents as $i) {
      $type = $this->Incidenttype_model->get($i->typeId);
      $state = $this->Incidentstate_model->get($i->stateId);

      $document->setValue('incidenttable_title#' . $count, $type->name);
      $document->setValue('incidenttable_cvss#' . $count, $i->cvss);
      
      $img = '';
      if($i->cvss >= 0 && $i->cvss < 1)
        $img = "r1";
      else
        if($i->cvss >= 1 && $i->cvss < 4)
          $img = "r2";
        else
          if($i->cvss >= 4 && $i->cvss < 7)
            $img = "r3";
          else
            if($i->cvss >= 7 && $i->cvss < 9)
              $img = "r4";
            else
              $img = "r5"; 
      $document->setImg('incidenttable_image#' . $count, array('src' => 'assets/images/'.$img.'.png', 'swh'=>'60'));

      $detail = $this->parse($i->abstract);
      $document->setValue('incidenttable_detail#' . $count, $detail['text']);
      
      $suggestion = $this->parse($i->suggestion);
      $document->setValue('incidenttable_recom#' . $count, $suggestion['text']);      

      $document->setValue('incidenttable_state#' . $count, $state->name);
      $count++;
    }

    //Hosts and Services
    if(trim($project->services) != '')
    {
      $hosts = json_decode($project->services);
      $x = 1;
      $document->cloneTable('hosttable', sizeof($hosts));
      $document->setValue('hosttable', '');
      foreach($hosts as $h) {
        $document->setValue('hosttable_host#' . $x, $h->host);

        $ports = $h->ports;      
        $document->cloneTable('hosttable_ports#'.$x, sizeof($ports));
        $document->setValue('hosttable_ports#'.$x, '');
        $y = 1;
        foreach($ports as $p) {
          $document->setValue('hosttable_ports_port#' . $x . '#' . $y, $p->port);
          $document->setValue('hosttable_ports_state#' . $x . '#' . $y, $p->state);

          $img = '';
          if($p->cvss >= 0 && $p->cvss < 1)
            $img = "r1";
          else
            if($p->cvss >= 1 && $p->cvss < 4)
              $img = "r2";
            else
              if($p->cvss >= 4 && $p->cvss < 7)
                $img = "r3";
              else
                if($p->cvss >= 7 && $p->cvss < 9)
                  $img = "r4";
                else
                  $img = "r5"; 
          $document->setImg('hosttable_ports_risk#' . $x . '#' . $y, array('src' => 'assets/images/'.$img.'.png', 'swh'=>'60'));
          
          $document->setValue('hosttable_ports_cvss#' . $x . '#' . $y, $p->cvss);          
          $document->setValue('hosttable_ports_serv#' . $x . '#' . $y, $p->service);
          $document->setValue('hosttable_ports_deta#' . $x . '#' . $y, $p->details);
          $document->setValue('hosttable_ports_acc#' . $x . '#' . $y, $p->accordance);
          $y++;
        }
        $x++;
      }
    }
    else
    {
      $document->setValue('hosttable', '');
      $document->setValue('hosttable_host', '');
      $document->setValue('hosttable_ports_port', '');
      $document->setValue('hosttable_ports_state', '');
      $document->setValue('hosttable_ports_risk', '');
      $document->setValue('hosttable_ports_cvss', '');
      $document->setValue('hosttable_ports_serv', '');
      $document->setValue('hosttable_ports_deta', '');
      $document->setValue('hosttable_ports_acc', '');      
    }

    //Incidents detail
    $document->cloneBlock('incidentdetail', sizeof($incidents));
    $c = 0;
    foreach ($incidents as $i) {
      $t = $this->Incidenttype_model->get($i->typeId);
      $c ++;

      $document->setValue('incidentdetail_title#' . $c, $i->description);

      $description = $this->parse($t->description);     
      $document->setValue('incidentdetail_description#' . $c, $description['text']);
      //Proccess images tags Incident Detail
      foreach ($description['images'] as $x) {
        $document->setImg($x, array('src' => 'tmp/' . $x, 'swh'=>'600'));
        $toDelete[] = 'tmp/' . $x;
      }

      $document->setValue('incidentdetail_cvss#' . $c, $i->cvss);

      $detail = $this->parse($i->detail);      
      $document->setValue('incidentdetail_detail#' . $c, $detail['text']);
      //Proccess images tags Incident Detail
      foreach ($detail['images'] as $x) {
        $document->setImg($x, array('src' => 'tmp/' . $x, 'swh'=>'600'));
        $toDelete[] = 'tmp/' . $x;
      }    
      
      $suggestion1 = $this->parse($t->solution);
      $document->setValue('incidentdetail_recom#' . $c, $suggestion1['text']);
      //Proccess images tags Incident Detail
      foreach ($suggestion['images'] as $x) {
        $document->setImg($x, array('src' => 'tmp/' . $x, 'swh'=>'600'));
        $toDelete[] = 'tmp/' . $x;
      }     

      $suggestion2 = $this->parse($i->suggestion);
      $document->setValue('incidentdetail_recom2#' . $c, $suggestion2['text']);
      //Proccess images tags Incident Detail
      foreach ($suggestion2['images'] as $x) {
        $document->setImg($x, array('src' => 'tmp/' . $x, 'swh'=>'600'));
        $toDelete[] = 'tmp/' . $x;
      }     

      //Track 
      $list = $this->Log_model->getListByIncident($i->id);
      $document->cloneRow('trackDate#' . $c, sizeof($list));
      $pos = 1;
      foreach($list as $r) {
        $state = $this->Incidentstate_model->get($r->stateId);
        $user  = $this->User_model->get($r->userId);

        $document->setValue('trackDate#' . $c . '#' . $pos, date("d-m-Y", strtotime($r->date)));
        $document->setValue('trackUser#' . $c . '#' . $pos, $user->name);
        $document->setValue('trackState#' . $c . '#' . $pos, $state->name);

        $dataDetail = $this->parse($r->detail);
        $document->setValue('trackDetail#' . $c . '#' . $pos, $dataDetail['text']);

        $pos++; 
      }                    
    }    

    $document->saveAs('tmp/'.$filename);

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell >browser what's the file name
    header("Content-Type: application/docx");
    header("Content-Transfer-Encoding: binary");    
    
    //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
    //$objWriter->save('php://output');
    readfile('tmp/'.$filename); // or echo file_get_contents($temp_file);
    unlink('tmp/'.$filename);  

    foreach ($toDelete as $i) {
      unlink($i);
    }
    exit;   
  }
  
  function parse($htmltoconvert)
  {

    include_once(APPPATH."third_party/Simplehtmldom/simple_html_dom.php");
    
    $txt = '';
    $changed = false;
    $images = array();
    $html = new simple_html_dom();

    // if (preg_match('~<body[^>]*>(.*?)</body>~si', $htmltoconvert, $body))
    //   $htmltoconvert = $body[1];

    $html->load($htmltoconvert);

    //Replace images
    foreach($html->find('img') as $i) 
    {
      //create temp image
      $img = $i->src;
      if(strpos($img, 'jpeg')) 
      {
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $tmpfile = uniqid() . '.jpeg';
        $file = 'tmp/' . $tmpfile;
      }
      
      if(strpos($img, 'png'))
      {
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $tmpfile = uniqid() . '.png';
        $file = 'tmp/' . $tmpfile;
      }
      $success = file_put_contents($file, $data);            
      $images[] = $tmpfile;

      $i->outertext = '<p>${' . $tmpfile . '}</p>';
      $changed = true;      
    }
    if($changed)
      $html->load($html->save());

    foreach($html->find('p,pre') as $p) 
    {
        $txt .= htmlspecialchars(strip_tags($p->innertext)) . '</w:t><w:br/><w:t>';      
    }
    $ret['text'] = $txt;
    $ret['images'] = $images;
    return $ret;
  }

  function crl_parse_html($string, $allowed = array())
  {
    // String --> DOM Elements
    $string = str_get_html($string);
    // Fetch child of the current element (one by one)
    foreach ($string->find('*') as $child) {
        if (
            // Current inner-text contain one or more elements
            preg_match('/<[^<]+?>/is', $child->innertext) and
            // Current element tag is in maintained elements array
            in_array($child->tag, $allowed)
        ) {
            // Assign current inner-text to current filtered inner-text
            $child->innertext = crl_parse_html($child->innertext, $allowed);
        } else if (
            // Current inner-text contain one or more elements
            preg_match('/<[^<]+?>/is', $child->innertext) and
            // Current element tag is NOT in maintained elements array
            !in_array($child->tag, $allowed)
        ) {
            // Assign current inner-text to the set of inner-elements (if exists)
            $child->innertext = preg_replace('/(?<=^|>)[^><]+?(?=<|$)(<[^\/]+?>.+)/is', '$1', $child->innertext);
            // Assign current outer-text to current filtered inner-text
            $child->outertext = $this->crl_parse_html($child->innertext, $allowed);
        } else if (
            (
                // Current inner-text is only plaintext
                preg_match('/(?<=^|>)[^><]+?(?=<|$)/is', $child->innertext) and
                // Current element tag is NOT in maintained elements array
                !in_array($child->tag, $allowed)
            ) or
            // Current plain-text is empty
            trim($child->plaintext) == ''
        ) {
            // Assign current outer-text to empty string
            $child->outertext = '';
        }
    }
    return $string;
  }
}