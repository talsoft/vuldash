<div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>

      <form id="editForm" class="form-horizontal" action="<?php echo base_url('projects/reportUpload2');?>"  enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo $project->id;?>">

        <div class="form-group">
          <label class="control-label col-sm-1" for="name">Report:</label>        
          <div class="col-sm-10">
            <div class="btn btn-default">
              <input id="upload" name="upload" type="file">
            </div>
          </div>        
        </div>
      </form>    
    </div>
    <div class="modal-footer">
      <button type="submit" id="submit" class="btn btn-default">Upload</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

  $("#submit").click(function(e) {
    e.preventDefault();

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
});
</script>