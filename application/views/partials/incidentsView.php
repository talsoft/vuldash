<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="status" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="">
        <input type="hidden" name="id" id="id" value="<?php echo $incident->id;?>">
        <input type="hidden" name="projectId" id="projectId" value="<?php echo $incident->projectId;?>">

        <ul class="nav nav-tabs">
          <li class="active"><a href="#data" data-toggle="tab">Data</a></li>
          <li><a href="#reference" data-toggle="tab">References</a></li>
          <li><a href="#trace" data-toggle="tab">Trace</a></li>
        </ul>

        <div class="tab-content ">
          <div class="tab-pane active" id="data">
            <br/>
            <div class="form-group">
              <label class="control-label col-sm-2" for="date">Date:</label>
              <div class="col-sm-4">
                <div class="input-group date" data-provide="datepicker">
                  <input class="form-control datepicker" name="date" id="date">
                  <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Type:</label>
              <div class="col-sm-10">
                <select name="typeId" id="typeId" class="form-control" disabled>
                  <option value="0" <?php echo($incident->typeId == null ? 'selected="selected"' : '') ?>>Select Type</option>
                  <?php
                  foreach($types as $type): ?>
                    <option value="<?= $type->id ?>" <?php echo($incident->typeId == $type->id ? 'selected="selected"' : '') ?>><?= $type->name ?></option>
                  <?php
                  endforeach ?>
                </select>             
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="cvss">CVSSv2:</label>
              <div class="col-sm-5">
                <input type="cvss" name="cvss" class="form-control" id="description" placeholder="" value="<?php echo $incident->cvss;?>" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Objective Type:</label>
              <div class="col-sm-10">
                <select name="objectiveTypeId" id="objectiveTypeId" class="form-control" disabled>
                  <option value="0" <?php echo($incident->objectiveTypeId == null ? 'selected="selected"' : '') ?>>Select Objective Type</option>
                  <?php
                  foreach($objectiveTypes as $type): ?>
                    <option value="<?= $type->id ?>" <?php echo($incident->objectiveTypeId == $type->id ? 'selected="selected"' : '') ?>><?= $type->name ?></option>
                  <?php
                  endforeach ?>
                </select>             
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="objective">Objective:</label>
              <div class="col-sm-5">
                <input type="objective" name="objective" class="form-control" id="objective" placeholder="" value="<?php echo $incident->objective;?>" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description:</label>
              <div class="col-sm-10">
                <input type="description" name="description" class="form-control" id="description" placeholder="Enter a Description" value="<?php echo $incident->description;?>" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">State:</label>
              <div class="col-sm-10">
                <select name="stateId" id="stateId" class="form-control" disabled>
                  <option value="0" <?php echo($incident->stateId == null ? 'selected="selected"' : '') ?>>Select State</option>
                  <?php
                  foreach($states as $state): ?>
                    <option value="<?= $state->id ?>" <?php echo($incident->stateId == $state->id ? 'selected="selected"' : '') ?>><?= $state->name ?></option>
                  <?php
                  endforeach ?>
                </select>             
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Detail:</label>
              <div class="col-sm-10">
                <textarea id="detail" name="detail"><?php echo($incident->detail);?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Suggestion:</label>
              <div class="col-sm-10">
                <textarea id="suggestion" name="suggestion"><?php echo($incident->suggestion);?></textarea>
              </div>
            </div>            
          </div>
          <div class="tab-pane" id="reference">          
            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Description:</label>
              <div class="col-sm-10">
                <textarea id="description" name="description" rows="6"><?php echo($incidenttype->description);?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Solution:</label>
              <div class="col-sm-10">
                <textarea id="solution" name="solution" rows="6"><?php echo($incidenttype->solution);?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Reference:</label>
              <div class="col-sm-10">
                <textarea id="reference" name="reference" rows="6"><?php echo($incidenttype->reference);?></textarea>
              </div>
            </div>

          </div>
          <div class="tab-pane" id="trace">
            <br/>
            <table width="100%" class="table table-striped table-bordered table-hover" id="log">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Detail</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </form>    
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#date').datetimepicker({     
                      defaultDate: "<?php echo $incident->date; ?>",
                      format: 'MM/DD/YYYY',
                    });
    $('#date').data("DateTimePicker").disable();

    $('#log').DataTable({
          responsive: true,
          data: <?php echo $log; ?>,
          "ordering": false,
          "info":     false,
          "searching":false,          
          "columns": [
                      {"data": "date", "Title": "Date"}, 
                      {"data": "detail", "Title": "Detail"}
                  ],
      });

    $("#modalForm").on("shown.bs.modal", function() {
      tinymce.init({        
          selector: "textarea",
          readonly: true,
          menubar: false,
          statusbar: false,
          toolbar: false          
      });
    });

    $("#modalForm").on("hide.bs.modal", function() {
      tinymce.remove();
    });    
  });

</script>