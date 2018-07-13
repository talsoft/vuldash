<div class="modal-dialog-md">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="status" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo base_url('incidentstype/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $incidenttype->id;?>">

        <div class="form-group">
          <label class="control-label col-sm-2" for="objective">Name:</label>
          <div class="col-sm-5">
            <input type="name" name="name" class="form-control" id="name" placeholder="" value="<?php echo $incidenttype->name;?>">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="description">Description:</label>
          <div class="col-sm-10">
            <textarea id="description" name="description"><?php echo($incidenttype->description);?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="description">Solution:</label>
          <div class="col-sm-10">
            <textarea id="solution" name="solution"><?php echo($incidenttype->solution);?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="description">References:</label>
          <div class="col-sm-10">
            <textarea id="reference" name="reference"><?php echo($incidenttype->reference);?></textarea>
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
  $("#submit").click(function(e) {
    e.preventDefault();

    var $form = $("#editForm");
    $('#description').html(tinymce.get('description').getContent());
    $('#solution').html(tinymce.get('solution').getContent());
    $('#reference').html(tinymce.get('reference').getContent());
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
                $('#incidents').DataTable().ajax.reload();                                
              }
              else
              {
                $("#status").html(responseData.message);
                $("#status").show();
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })    
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