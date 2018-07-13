<div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>

      <form id="editForm" class="form-horizontal" action="<?php echo isset($delete) ? base_url('projects/delete') : base_url('projects/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $project->id;?>">
        <input type="hidden" name="services" id="services" value="<?php echo $project->services;?>">
        <?php 
        if(isset($delete)):?>
          <input type="hidden" name="delete" id="delete" value="<?php echo $project->id;?>">
          <p class="alert alert-danger">You want to delete the Project? All associated data will be deleted.</p>
        <?php
        endif?>

        <?php
        if(!isset($delete)):?>        
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Name:</label>
          <div class="col-sm-10">
            <input type="name" name="name" class="form-control" id="name" placeholder="Enter Project Name" value="<?php echo $project->name;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Client:</label>
          <div class="col-sm-10">
            <select name="clientId" id="clientId" class="form-control">
              <option value="" <?php echo($project->clientId == null ? 'selected="selected"' : '') ?>>Select Client</option>
              <?php
              foreach($clients as $client): ?>
                <option value="<?= $client->id ?>" <?php echo($project->clientId == $client->id ? 'selected="selected"' : '') ?>><?= $client->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="initDate">Init date:</label>
          <div class="col-sm-4">
            <div class="input-group date" data-provide="datepicker">
              <input class="form-control datepicker" name="initDate" id="initDate">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">State:</label>
          <div class="col-sm-10">
            <select name="stateId" id="stateId" class="form-control">
              <option value="" <?php echo($project->stateId == null ? 'selected="selected"' : '') ?>>Select State</option>
              <?php
              foreach($states as $state): ?>
                <option value="<?= $state->id ?>" <?php echo($project->stateId == $state->id ? 'selected="selected"' : '') ?>><?= $state->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Description:</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="2" id="description" name="description" placeholder="Enter Project Description"><?php echo $project->description;?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Scope:</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="2" id="scope" name="scope" placeholder="Enter Project Scope"><?php echo $project->scope;?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="services">Ports and Services:</label>
          <div class="col-sm-10" style="height:200px;overflow-y:scroll;">
            <table width="100%" class="table table-striped table-bordered table-hover" id="portsServices">
              <thead>
                <tr>
                  <th>Port</th>
                  <th>State</th>
                  <th>Risk</th>
                  <th>CVSSv2</th>
                  <th>Service</th>
                  <th>Accordance</th>
                  <th>Details</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>            
            <?php 
            if(!isset($delete)):?>
              <div class="btn-group pull-left">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Import <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="#" id="pluginImport" data="nmap">nMap xml</a></li>
                </ul>
              </div>
              <button type="button" id="addHost" class="btn btn-default pull-right">Add Host</button>
            <?php
            endif;?>                      
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Type:</label>
          <div class="col-sm-10">
            <select name="typeId" id="typeId" class="form-control">
              <option value="0" <?php echo($project->typeId == null ? 'selected="selected"' : '') ?>>Select Type</option>
              <?php
              foreach($types as $type): ?>
                <option value="<?= $type->id ?>" <?php echo($project->typeId == $type->id ? 'selected="selected"' : '') ?>><?= $type->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>
        </div>

        <?php
        if($project->typeId):
          if(sizeof($stages) > 0):?>
            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Stage:</label>
              <div class="col-sm-10">
                <select name="stageId" id="stageId" class="form-control">
                  <option value="" <?php echo($project->stageId == null ? 'selected="selected"' : '') ?>>Select Stage</option>
                  <?php
                  foreach($stages as $stage): ?>
                    <option value="<?= $stage->id ?>" <?php echo($project->stageId == $stage->id ? 'selected="selected"' : '') ?>><?= $stage->name ?></option>
                  <?php
                  endforeach ?>
                </select>             
              </div>
            </div>
          <?php
          endif;
        endif;?>  

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Report Lenguage:</label>
          <div class="col-sm-10">
            <select name="reportTemplate" id="reportTemplate" class="form-control">
              <option value="" <?php echo($project->templateReport == '' ? 'selected="selected"' : '') ?>>Select Lenguage</option>
              <option value="sp" <?php echo($project->templateReport == 'sp' ? 'selected="selected"' : '') ?>>Spanish</option>
              <option value="en" <?php echo($project->templateReport == 'en' ? 'selected="selected"' : '') ?>>Inglish</option>
            </select>             
          </div>
        </div>

        <?php
        endif;?>
      </form>    
    </div>
    <div class="modal-footer">
      <?php 
      if(isset($delete)):?>
      <button type="submit" id="submit" class="btn btn-default">Delete</button>
      <?php
      else:?>    
      <button type="submit" id="submit" class="btn btn-default">Save</button>
      <?php
      endif;?>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<div id="modalImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Hosts</h4>
      </div>
      <div class="modal-body">
        <form id="importForm" class="form-horizontal" action="<?php echo base_url('projects/importhosts');?>" enctype="multipart/form-data">
          <input type="hidden" name="plugin" id="plugin"/>
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
        <button type="button" id="acceptImport" class="btn btn-default">Accept</button>
        <button type="button" id="cancelImport" class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="modalHost" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Host</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Host:</label>
            <div class="col-sm-10">
              <input type="name" name="hostName" class="form-control" id="hostName" placeholder="Enter Host" value="">
            </div>
          </div>        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="acceptHost" class="btn btn-default">Accept</button>
        <button type="button" id="cancelHost" class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="modalPort" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Port / Service</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Port:</label>
            <div class="col-sm-10">
              <input type="port" name="port" class="form-control" id="port" placeholder="Enter Port" value="">
            </div>
          </div>        

          <div class="form-group">
            <label class="control-label col-sm-2" for="name">State:</label>
            <div class="col-sm-10">
              <input type="state" name="state" class="form-control" id="state" placeholder="Enter State" value="">
            </div>
          </div>        

          <div class="form-group">
            <label class="control-label col-sm-2" for="name">CVSSv2:</label>
            <div class="col-sm-10">
              <input type="cvss" name="cvss" class="form-control" id="cvss" placeholder="Enter CVSSv2" value="">
            </div>
          </div>        

          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Service:</label>
            <div class="col-sm-10">
              <input type="service" name="service" class="form-control" id="service" placeholder="Enter Service" value="">
            </div>
          </div>        

          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Accordance:</label>
            <div class="col-sm-10">
              <input type="accordance" name="accordance" class="form-control" id="accordance" placeholder="Enter Accordance" value="">
            </div>
          </div>        

          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Details:</label>
            <div class="col-sm-10">
              <input type="details" name="details" class="form-control" id="details" placeholder="Enter Details" value="">
            </div>
          </div>        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="acceptPort" class="btn btn-default">Accept</button>
        <button type="button" id="cancelPort" class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  var clickedRow;
  var updating;

  if('<?php echo $project->services;?>' != '')
    fillHostTable(JSON.parse('<?php echo $project->services;?>'));

  $('#initDate').datetimepicker({     
                    defaultDate: "<?php echo $project->initDate; ?>",
                    format: 'MM/DD/YYYY'
                  });

  $("#submit").click(function(e) {
    e.preventDefault();

    var data = [];
    var hostName = "";
    var ports = [];
    $("#portsServices tbody").find('tr').each(function (rowIndex, r) {
      if(r.cells.length == 2)
      {
        if(hostName != "")
        {
          host = {
            host : hostName,
            ports : ports          
          };
          data.push(host);
        }

        hostName = $(r).find("td:eq(0)").text();
        ports = [];
      }
      else
      {
        port = {
          port : $(r).find(".port").text(),
          state : $(r).find(".state").text(),
          cvss : $(r).find(".cvss").text(),
          service : $(r).find(".service").text(),
          accordance : $(r).find(".accordance").text(),
          details : $(r).find(".details").text()
        };
        ports.push(port);
      }
    });
    host = {
      host : hostName,
      ports : ports          
    };    
    data.push(host);

    $('#services').val(JSON.stringify(data));

    var $form = $("#editForm")
    $.ajax({
            type: "post",
            url: $form.attr("action"),
            data: $("#editForm").serialize(),
            contentType: "application/x-www-form-urlencoded",
            success: function(responseData) {
              if(responseData.status == "ok")
              {
                $("#modalForm").modal("hide");
                $("#status").html(responseData.message);
                $("#status").show().delay(2000).fadeOut();
                $('#projects').DataTable().ajax.reload();                                
              }
              else
              {
                $("#statusModal").html(responseData.message);
                $("#statusModal").show();
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })    
    });

  $("#addHost").on("click", function () {
    updating = false;

    $("#hostName").val('');
    $("#modalHost").modal('show'); 
    return false;            
  });

  $(document).on("click", "#addPort", function(e) {
    updating = false;
    clickedRow = $(this).closest("tr");

    $("#port").val('');
    $("#state").val('');
    $("#cvss").val('');
    $("#service").val('');
    $("#accordance").val('');
    $("#details").val('');      
    $("#modalPort").modal('show'); 
    return false;            
  });

  $(document).on("click", "#deleteHost", function(e) {
    clickedRow = $(this).closest("tr");

    //Delete all Ports on Host
    $(clickedRow).nextUntil("tr.host", "tr.port").remove();
    $(clickedRow).remove();
    return false;            
  });

  $(document).on("click", "#portsServices .host", function(e) {
    updating = true;
    clickedRow = $(this).closest("tr");

    $("#hostName").val($(clickedRow).find("td:eq(0)").text());
    $("#modalHost").modal('show'); 
    return false;            
  });

  $(document).on("click", "#portsServices .port", function(e) {
    updating = true;
    clickedRow = $(this).closest("tr");

    $("#port").val($(clickedRow).find(".port").text());
    $("#state").val($(clickedRow).find(".state").text());
    $("#cvss").val($(clickedRow).find(".cvss").text());
    $("#service").val($(clickedRow).find(".service").text());
    $("#accordance").val($(clickedRow).find(".accordance").text());
    $("#details").val($(clickedRow).find(".details").text());      
    $("#modalPort").modal('show'); 
    return false;            
  });

  $("#acceptHost").on("click", function () {
    if(updating)      
    {
      $(clickedRow).find("td:eq(0)").text($("#hostName").val());
      updating = false;
    }
    else
    {
      $("#portsServices tbody").append('<tr class="host"><td colspan="6" class="name">' + $("#hostName").val() + '</td>' +
      '<td colspan="2">' +
      '<button type="button" class="btn btn-default btn-sm" id="addPort" title="Add Port"><i class="fa fa-plus-circle fa-fw"></i></button>' +
      '<button type="button" class="btn btn-default btn-sm" id="deleteHost" title="Delete host"><i class="fa fa-times-circle fa-fw"></i></button>' +
      '</td>' +
      '</tr>');
    }
    $("#modalHost").modal('hide'); 
    return false;            
  });  

  $("#cancelHost").on("click", function () {
    updating = false;
    $("#modalHost").modal('hide'); 
    return false;            
  });    

  $("#acceptPort").on("click", function () {
    x = Number($("#cvss").val());
    path = "<?php echo base_url('assets/images/')?>";
    img = "";
    if(x >= 0 && x < 1)
      r = "r1";
    else
      if(x >= 1 && x < 4)
        r = "r2";
      else
        if(x >= 4 && x < 7)
          r = "r3";
        else
          if(x >= 7 && x < 9)
            r = "r4";
          else
            r = "r5";    
    if(updating)      
    {
      $(clickedRow).find(".port").text($("#port").val());
      $(clickedRow).find(".state").text($("#state").val());
      $(clickedRow).find(".risk").html('<img src="' + path + r + '.png" width="60"/>');
      $(clickedRow).find(".cvss").text($("#cvss").val());
      $(clickedRow).find(".service").text($("#service").val());
      $(clickedRow).find(".accordance").text($("#accordance").val());
      $(clickedRow).find(".details").text($("#details").val());            
      updating = false;
    }
    else
    {
      var newRow = $('<tr class="port">');
      var cols = "";

      cols += '<td class="port">' + $("#port").val() + '</td>';
      cols += '<td class="state">' + $("#state").val() + '</td>';
      cols += '<td class="risk"><img src="' + path + r + '.png" width="60"/></td>';
      cols += '<td class="cvss">' + $("#cvss").val() + '</td>';
      cols += '<td class="service">' + $("#service").val() +'</td>';
      cols += '<td class="accordance">' + $("#accordance").val() + '</td>';
      cols += '<td class="details">' + $("#details").val() + '</td>';
      cols += '<td><button type="button" class="btn btn-default btn-sm" onClick="$(this).closest(\'tr\').remove();" title="Delete port"><i class="fa fa-times-circle fa-fw"></i></button></td>';

      newRow.append(cols);      
      newRow.insertAfter(clickedRow);
    }
    $("#modalPort").modal('hide'); 
    return false;            
  });  

  $("#cancelPort").on("click", function () {
    updating = false;
    $("#modalPort").modal('hide'); 
    return false;            
  });  

  $("#pluginImport").on("click", function () {
    plugin = $(this).attr("data");
    $("#modalImport #plugin").val(plugin); 
    $("#modalImport").modal('show'); 
    return false;            
  });  

  $("#cancelImport").on("click", function () {
    $("#modalImport").modal('hide'); 
    return false;            
  });  

  $("#acceptImport").on("click", function () {
    var $form = $("#importForm");
    var formData = new FormData($("#importForm")[0]);    

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
                $("#statusModal").html(resp.message);
                $("#statusModal").removeClass('alert-danger').addClass('alert-success');
                $("#statusModal").show();  
                console.log(resp.result);
                fillHostTable(JSON.parse(resp.result));
              }
              else
              {
                $("#modalImport").modal("hide");                
                $("#statusModal").html(resp.message);
                $("#statusModal").removeClass('alert-success').addClass('alert-danger');
                $("#statusModal").show();                
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })    
    return false;            
  });    

  function fillHostTable(data)
  {
    var hosts = data;
    if(hosts != '') {
      $.each(hosts, function(i, item) {
        $("#portsServices tbody").append('<tr class="host"><td colspan="6" class="name">' + item.host + '</td>' +
          '<td colspan="2">' +
          '<button type="button" class="btn btn-default btn-sm" id="addPort" title="Add Port"><i class="fa fa-plus-circle fa-fw"></i></button>' +
          '<button type="button" class="btn btn-default btn-sm" id="deleteHost" title="Delete host"><i class="fa fa-times-circle fa-fw"></i></button>' +
          '</td>' +
          '</tr>');
        $.each(item.ports, function(i, item) {
          x = Number(item.cvss);
          path = "<?php echo base_url('assets/images/')?>";
          img = "";
          if(x >= 0 && x < 1)
            r = "r1";
          else
            if(x >= 1 && x < 4)
              r = "r2";
            else
              if(x >= 4 && x < 7)
                r = "r3";
              else
                if(x >= 7 && x < 9)
                  r = "r4";
                else
                  r = "r5";
          $("#portsServices tbody").append('<tr class="port">' +
            '<td class="port">' + item.port + '</td>' +
            '<td class="state">' + item.state + '</td>' +
            '<td class="risk"><img src="' + path + r + '.png" width="60"/></td>' +
            '<td class="cvss">' + item.cvss + '</td>' +
            '<td class="service">' + item.service + '</td>' +
            '<td class="accordance">' + item.accordance + '</td>' +
            '<td class="details">' + item.details + '</td>' +
            '<td><button type="button" class="btn btn-default btn-sm" onClick="$(this).closest(\'tr\').remove();" title="Delete port"><i class="fa fa-times-circle fa-fw"></i></button></td>'
          );
        });
      });
    }
  }
});
</script>