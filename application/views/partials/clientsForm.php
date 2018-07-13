<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo isset($delete) ? base_url('clients/delete') : base_url('clients/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $client->id;?>">
        <?php 
        if(isset($delete)):?>
          <input type="hidden" name="delete" id="delete" value="<?php echo $client->id;?>">
          <p class="alert alert-danger">You want to delete the Client? All associated projects will be deleted.</p>          
        <?php
        endif?>
        
        <?php
        if(!isset($delete)):?>        
        <div class="form-group">
          <label class="control-label col-sm-2" for="companyName">Company Name:</label>
          <div class="col-sm-10">
            <input type="name" name="name" class="form-control" id="name" placeholder="Enter Company Name" value="<?php echo $client->name;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="contactName">Contact Name:</label>
          <div class="col-sm-10">
            <input type="name" name="contact" class="form-control" id="contact" placeholder="Enter Contact Name" value="<?php echo $client->contact;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-2" for="address">Address:</label>
          <div class="col-sm-10">
            <input type="text" name="address" class="form-control" id="address" placeholder="Enter a Address" value="<?php echo $client->address;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="phone1">City:</label>
          <div class="col-sm-10">
            <input type="text" name="city" class="form-control" id="city" placeholder="Enter a City" value="<?php echo $client->city;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="phone1">Telephone:</label>
          <div class="col-sm-10">
            <input type="tel" name="phone1" class="form-control" id="phone1" placeholder="Enter Phone number" value="<?php echo $client->phone1;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-2" for="phone2"></label>
          <div class="col-sm-10">
            <input type="tel" name="phone2" class="form-control" id="phone2" placeholder="Enter Phone number" value="<?php echo $client->phone2;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>
        <?php
        endif?>
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
  $("#submit").click(function(e) {
    e.preventDefault();

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
                $('#clients').DataTable().ajax.reload();                                
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
</script>