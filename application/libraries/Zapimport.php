<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Zapimport { 

  protected $CI;
  public $url = "";
  public $upload_path = "";

  public function __construct($params)
  {
    $this->CI =& get_instance();
    $this->url = $params['url'];
    $this->upload_path = $params['upload_path'];
  }

  public function show()  
  {
    $return = '<div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ZAP XML Import</h4>
                  </div>
                  <div class="modal-body">
                    <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>
                    <form id="importZAPForm" class="form-horizontal" action="' . base_url($this->url) . '" enctype="multipart/form-data">
                      <input id="process" name="process" type="hidden" value="1">
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="name">File:</label>
                        <div class="col-sm-10">
                          <div class="btn btn-default">
                            <input id="upload" name="upload" type="file">
                          </div>
                        </div>        
                      </div>        
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="acceptImportZAP" class="btn btn-default">Accept</button>
                    <button type="button" id="cancelImport" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
              <script>
                 $("#acceptImportZAP").on("click", function () {
                    var $form = $("#importZAPForm");
                    var formData = new FormData($("#importZAPForm")[0]);    

                    $.ajax({
                            type: "post",
                            url: $form.attr("action"),
                            data: formData,
                            mimeType: "multipart/form-data",
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(responseData) {
                              resp = JSON.parse(responseData);
                              if(resp.status == "ok")
                              {
                                $("#modalImport").modal("hide");
                                $("#status").html(resp.message);
                                $("#status").removeClass("alert-danger").addClass("alert-success");
                                $("#status").show();
                                $("#incidents").DataTable().ajax.reload(); 
                              }
                              else
                              {                                
                                $("#statusModal").html(resp.message);
                                $("#statusModal").removeClass("alert-success").addClass("alert-danger");
                                $("#statusModal").show();                
                              }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        })    
                    return false;            
                  });                  
              </script>';
    return $return;
  }

  public function process()
  {
    $file_element_name = 'upload';

    $config['upload_path'] = $this->upload_path;
    $config['allowed_types'] = 'xml|txt';
    $config['max_size'] = 1024 * 8;
    $config['encrypt_name'] = TRUE;

    $this->CI->load->library('upload', $config);

    if(!empty($_FILES["upload"]["name"]))
    {
      if ($this->CI->upload->do_upload($file_element_name))
      {
        $data = $this->CI->upload->data();        

        $result = $this->parsexml($data['full_path']);

        @unlink($_FILES[$file_element_name]);
        @unlink($data['full_path']);

        $result = array('status' => 'ok', 'message' => 'The data was imported correctly', 'json' => $result);
      }
      else 
      {
        $result = array('status' => 'error', 'message' => 'Failed to upload attach file. ' . $this->upload->display_errors());
      }
    }
    else
    {
      $result = array('status' => 'error', 'message' => 'File is empty');      
    }
    return $result;
  }

  public function parsexml($file) 
  {    
    $xml = simplexml_load_file($file);

    $sites = array();
    $date = ''.$xml->attributes()['generated'];

    foreach ($xml as $site) 
    {      
      $alerts = array();
      foreach ($site->alerts->alertitem as $item) 
      {      
        $alerts[] = array(
                    'alert'       => ''.$item->alert,
                    'riskcode'    => ''.$item->riskcode,
                    'desc'        => ''.$item->desc,
                    'solution'    => ''.$item->solution,
                    'reference'   => ''.$item->reference,
        );
      }      
      $sites[] = array(
        'date'   => $date,
        'site'   => ''.$site->attributes()['host'],
        'name'   => ''.$site->attributes()['name'],
        'port'   => ''.$site->attributes()['port'],
        'alerts' => $alerts,
      );  
    }
    return json_encode(array_values($sites));
  }
}