<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusModal" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo isset($delete) ? base_url('users/delete') : base_url('users/save');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $user->id;?>">
        <input type="hidden" name="type" id="type" value="U">
        <?php 
        if(isset($delete)):?>
          <input type="hidden" name="delete" id="delete" value="<?php echo $user->id;?>">
          <p class="alert alert-danger">You want to delete the User?</p>
        <?php
        endif?>        
        
        <?php
        if(!isset($delete)):?>        
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Name:</label>
          <div class="col-sm-10">
            <input type="name" name="name" class="form-control" id="name" placeholder="Enter User Name" value="<?php echo $user->name;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="mail">Mail:</label>
          <div class="col-sm-10">
            <input type="mail" name="mail" class="form-control" id="mail" placeholder="Enter Mail Address" value="<?php echo $user->username;?>" <?php echo isset($delete) ? "readonly" : ""?>>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="profile">Profile:</label>
          <div class="col-sm-10">
            <select name="profile" class="form-control" id="profile" placeholder="Select Profile" <?php echo isset($delete) ? "disabled" : ""?>>
              <option value="G" <?php echo ($user->profileId == 'G' ? 'selected' : ''); ?>>Gerential User</option>
              <option value="E" <?php echo ($user->profileId == 'E' ? 'selected' : ''); ?>>Technician</option>
            </select>            
          </div>
        </div>        

        <div class="form-group" id="clientSelector">
          <label class="control-label col-sm-2" for="clientId">Client:</label>
          <div class="col-sm-10">
            <select name="clientId" id="clientId" class="form-control" disabled>
              <option value="0" <?php echo($user->clientId == null ? 'selected="selected"' : '') ?>>Select Client</option>
              <?php
              foreach($clients as $client): ?>
                <option value="<?= $client->id ?>" <?php echo($clientId == $client->id ? 'selected="selected"' : '') ?>><?php echo $client->name ?></option>
              <?php
              endforeach ?>
            </select>             
          </div>
        </div>                
        <?php
        endif;?>        

      </form>    
    </div>
    <div class="modal-footer">
      <?php
      if(!isset($delete)):
        if($user->id): ?>
        <div class="pull-left">
          <button type="button" id="sendmail" class="btn btn-default">Resend Confirmation Mail</button>
        </div>
        <?php
        endif;
      endif;?>
      <div class="pull-right">
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
</div>

<script>
  $("#submit").click(function(e) {
    e.preventDefault();

    $('#clientId').removeAttr('disabled');
    
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
                $('#users').DataTable().ajax.reload();                                
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

    $("#sendmail").click(function(e) {
      e.preventDefault();
      
      $.ajax({
          url: "<?php echo base_url('users/sendmail');?>",
          data: {id: <?php echo $user->id;?>},
          success: function(responseData) {
              if(responseData.status == "ok")
              {
                $("#modalForm").modal("hide");
                $("#status").html(responseData.message);
                $("#status").show().delay(2000).fadeOut();
                $('#users').DataTable().ajax.reload();                                
              }
              else
              {
                $("#statusModal").html(responseData.message);
                $("#statusModal").show();
              }            
          }
      });        
    });     
</script>