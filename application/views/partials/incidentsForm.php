<div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo isset($delete) ? base_url('incidents/delete') : base_url('incidents/save');?>" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo $incident->id;?>">
        <input type="hidden" name="projectId" id="projectId" value="<?php echo $incident->projectId;?>">
        <?php 
        if(isset($delete)):?>
          <input type="hidden" name="delete" id="delete" value="<?php echo $incident->id;?>">
          <p class="alert alert-danger">You want to delete the Incident? All associated Logs will be deleted.</p>
        <?php
        endif?>

        <?php
        if(!isset($delete)):?>        
        <div class="form-group">
          <label class="control-label col-sm-1" for="date">Date:</label>
          <div class="col-sm-4">
            <div class="input-group date" data-provide="datepicker">
              <input class="form-control datepicker" name="date" id="date" <?php echo isset($delete) ? "readonly" : ""?>>
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="control-label col-sm-1" for="name">Type:</label>
          <div class="col-sm-3">
            <select name="typeId" id="typeId" class="form-control" <?php echo isset($delete) ? "readonly" : ""?>>
              <option value="" <?php echo($incident->typeId == null ? 'selected="selected"' : '') ?>>Select Type</option>
              <?php
              foreach($types as $type): ?>
                <option value="<?= $type->id ?>" <?php echo($incident->typeId == $type->id ? 'selected="selected"' : '') ?>><?= $type->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>

          <label class="control-label col-sm-1" for="cvss">CVSSv2:</label>
          <div class="col-sm-3">
            <input type="cvss" name="cvss" class="form-control" id="description" placeholder="" value="<?php echo $incident->cvss;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group row">
          <label class="control-label col-sm-1" for="name">Objective Type:</label>
          <div class="col-sm-3">
            <select name="objectiveTypeId" id="objectiveTypeId" class="form-control" <?php echo isset($delete) ? "readonly" : ""?>>
              <option value="" <?php echo($incident->objectiveTypeId == null ? 'selected="selected"' : '') ?>>Select Objective Type</option>
              <?php
              foreach($objectiveTypes as $type): ?>
                <option value="<?= $type->id ?>" <?php echo($incident->objectiveTypeId == $type->id ? 'selected="selected"' : '') ?>><?= $type->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>

          <label class="control-label col-sm-1" for="objective">Objective:</label>
          <div class="col-sm-3">
            <input type="objective" name="objective" class="form-control" id="objective" placeholder="" value="<?php echo $incident->objective;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group row">
          <label class="control-label col-sm-1" for="description">Description:</label>
          <div class="col-sm-3">
            <input type="description" name="description" class="form-control" id="description" placeholder="Enter a Description" value="<?php echo $incident->description;?>">
          </div>

          <label class="control-label col-sm-1" for="name">State:</label>
          <div class="col-sm-3">
            <input type="hidden" name="stateId2" id="stateId2" value="<?php echo $incident->stateId;?>">
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
          <label class="control-label col-sm-1" for="name">Abstract:</label>
          <div class="col-sm-10">
            <textarea id="abstract" name="abstract"><?php echo($incident->abstract);?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="name">Detail:</label>
          <div class="col-sm-10">
            <textarea id="detail" name="detail"><?php echo($incident->detail);?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="name">Suggestion:</label>
          <div class="col-sm-10">
            <textarea id="suggestion" name="suggestion"><?php echo($incident->suggestion);?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="name">Attachments:</label>        
          <div class="col-sm-10">
            <div class="btn btn-default">
              <input id="upload" name="upload" type="file">
            </div>
            <?php
            if($incident->attach):?>
              <a href="<?php echo base_url('uploads/' . $incident->attach);?>" target="_blank" class="btn btn-primary" role="button">View Attach</a>
              <button type="button" id="deleteattach" class="btn btn-danger">Delete</button>
            <?php
            endif;
            ?>
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
      endif?> 
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
  $('#date').datetimepicker({     
                    defaultDate: "<?php echo $incident->date; ?>",
                    format: 'MM/DD/YYYY'
                  });

  $("#submit").click(function(e) {
    e.preventDefault();

    <?php
    if(!isset($delete)):?>
    $('#detail').html(tinymce.get('detail').getContent());
    $('#suggestion').html(tinymce.get('suggestion').getContent());
    $('#abstract').html(tinymce.get('abstract').getContent());
    <?php
    endif;?>

    var $form = $("#editForm");
    var formData = new FormData($("#editForm")[0]);    

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
                $("#modalForm").modal("hide");
                $("#status").html(resp.message);
                $("#status").show().delay(2000).fadeOut();
                $('#incidents').DataTable().ajax.reload(); 
              }
              else
              {
                $("#statusModal").html(resp.message);
                $("#statusModal").show();
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })    
    }); 

    $("#deleteattach").click(function(e) {
    });

    $("#modalForm").on("show.bs.modal", function() {
      tinymce.init({
          selector: "textarea",
          toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code | uploadimage",
          plugins: "print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools colorpicker textpattern help code uploadimage",
          paste_data_images: true,
          images_upload_handler: function (blobInfo, success, failure) {
            success("data:" + blobInfo.blob().type + ";base64," + blobInfo.base64());
          },          
      });
    });

    $("#modalForm").on("hide.bs.modal", function() {
      tinymce.remove();
    });   
</script>