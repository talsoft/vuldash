<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="status" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo base_url('projectstype/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $projecttype->id;?>">
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Name:</label>
            <div class="col-sm-8">
                <input type="description" name="name" class="form-control" id="name" placeholder="Enter a Name" value="<?php echo $projecttype->name;?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Stages:</label>
            <button type="button" id="newstage" class="btn btn-default">New stage</button>    
            <div class="col-sm-5" id="stages">
                <div class="input-group">
                   <input type="text" name="stagename[]" class="form-control" placeholder="Enter Stage Name" maxlength="20">
                   <span class="input-group-btn">
                        <button class="btn btn-default" type="button">X</button>
                   </span>
                </div>                                                
            </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="description">Metodology:</label>
          <div class="col-sm-10">
            <textarea id="metodology" name="metodology"><?php echo($projecttype->metodology);?></textarea>
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
    $('#metodology').html(tinymce.get('metodology').getContent());
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
                $('#data').DataTable().ajax.reload();                                
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

    $("#newstage").click(function(e) {
        e.preventDefault(); 
        $("#stages").append('<div class="input-group"><input type="text" name="stagename[]" class="form-control" placeholder="Enter Stage Name" maxlength="20"><span class="input-group-btn"><button class="btn btn-default" id="removestage" type="button">X</button></span></div>');                                                

    });

    $("#stages").on("click", "#removestage", function(e) { 
        e.preventDefault(); 
        $(this).parent().parent('div').remove();
    });

    $("#modalForm").on("show.bs.modal", function() {
      $("#stages").empty();
      stages = "<?php echo $projecttype->stages;?>";
      if(stages)
      {
        var list = stages.split(';');
        list.forEach(function(element) {
          $("#stages").append('<div class="input-group"><input type="text" name="stagename[]" class="form-control" placeholder="Enter Stage Name" maxlength="20" value="' + element +'"><span class="input-group-btn"><button class="btn btn-default" id="removestage" type="button">X</button></span></div>');
        });
      }
      else 
      {
        $("#stages").append('<div class="input-group"><input type="text" name="stagename[]" class="form-control" placeholder="Enter Stage Name" maxlength="20"><span class="input-group-btn"><button class="btn btn-default" id="removestage" type="button">X</button></span></div>');
      }

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