<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <textarea id="detail" name="detail"><?php echo($log->detail);?></textarea>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
    </div>    
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#modalDetail").on("show.bs.modal", function() {
      tinymce.init({        
          selector: "textarea",
          readonly: true,
          menubar: false,
          statusbar: false,
          toolbar: false          
      });
    });

    $("#modalDetail").on("hide.bs.modal", function() {
      tinymce.remove();
    }); 
  });      
</script>