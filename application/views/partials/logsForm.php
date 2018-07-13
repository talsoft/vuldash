<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="newTrack" class="form-horizontal" action="<?php echo base_url('logs/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $log->id;?>">
        <input type="hidden" name="incidentId" id="incidentId" value="<?php echo $log->incidentId;?>">

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
          <label class="control-label col-sm-2" for="name">State:</label>
          <div class="col-sm-10">
            <select name="stateId" id="stateId" class="form-control">
              <option value="0" <?php echo($projectState == null ? 'selected="selected"' : '') ?>>Select State</option>
              <?php
              foreach($states as $state): ?>
                <option value="<?= $state->id ?>" <?php echo($projectState == $state->id ? 'selected="selected"' : '') ?>><?= $state->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Detail:</label>
          <div class="col-sm-10">
            <textarea id="detail" name="detail" class="form-control" rows="2"></textarea>
          </div>
        </div>
      </form>    
    </div>
    <div class="modal-footer">
      <button type="submit" id="submit" class="btn btn-default">Save</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
  $('#date').datetimepicker({     
                    defaultDate: "<?php echo $log->date; ?>",
                    format: 'MM/DD/YYYY'
                  });

  $("#submit").click(function(e) {
    e.preventDefault();

    var $form = $("#newTrack");
    $('#newTrack #detail').html(tinymce.get('detail').getContent());
    $.ajax({
            type: "post",
            url: $form.attr("action"),
            data: $("#newTrack").serialize(),
            contentType: "application/x-www-form-urlencoded",
            success: function(responseData) {
              if(responseData.status == "ok")
              {
                $("#modalNew").modal("hide");
                $("#statusLog").html(responseData.message);
                $("#statusLog").show().delay(2000).fadeOut();
                $('#logs').DataTable().ajax.reload();                                
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

    $("#modalNew").on("show.bs.modal", function() {
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

    $("#modalNew").on("hide.bs.modal", function() {
      tinymce.remove();
    });  
</script>