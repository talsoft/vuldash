<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="status" class="alert alert-danger" role="alert" style="display:none"></div>
      <form id="editForm" class="form-horizontal" action="<?php echo base_url('projects/testerssave');?>">
        <input type="hidden" name="id" id="id" value="<?php echo $project->id;?>">
        
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Name:</label>
          <div class="col-sm-10">
            <input type="name" name="name" class="form-control" id="name" placeholder="Enter Project Name" value="<?php echo $project->name;?>" readonly="readonly">
          </div>
        </div> 
        
        <table width="100%" class="table table-striped table-bordered table-hover" id="users">
          <thead>
            <tr>
              <th>Name</th>
              <th>Active</th>
            </tr>
          </thead>
        </table>
        <button type="button" id="newrow" class="btn btn-default">New</button>
      </form>    
    </div>
    <div class="modal-footer">
      <button type="submit" id="submit" class="btn btn-default">Save</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
      var list = <?php echo $users; ?>; 
      $('#users').DataTable({
          responsive: true,
          data: <?php echo $projectusers; ?>,
          "paging":   false,
          "ordering": false,
          "info":     false,
          "searching":false,          
          "columns": [
                      {"data": "name", "Title": "Name", 
                       "mRender": function(data, type, full)
                                      {
                                        ret = '<select class="form-control" ' + 'name="tester[]" id="tester" ' +
                                              (data != '' ? ' disabled' : '') + '>';
                                        ret += '<option value="0">Select</option>';

                                        $.each(list, function(key, value) {   
                                          ret += '<option value="' + key + '"' + 
                                                 (value == data ? 'selected' : '') + '>' +
                                                 value +
                                                 '</option>';
                                        });
                                        ret += '</select>';
                                        if(data != 0)
                                          ret += '<input type="hidden" name="tester[]" value="' + full.id + '"/>';          
                                        return ret;
                                      }
                      },
                      {"data": "active", "Title": "Active", 
                       "mRender": function(data, type, full)
                                      {
                                        return '<input type="checkbox" class="form-control" name="active[]" id="active" value=""' +
                                        (data == 1 ? 'checked' : '') + '>';
                                      }
                      }
                  ],
      });

      $("#newrow").click(function(e) {
        $('#users').DataTable().row.add({"id":"0","name":"","active":"1","DT_RowId":"0"}).draw(false);
      });

      $("#users tbody").on("change", "#tester", function () {
        id = this.value;
        yes = false;

        $('#users').DataTable().rows(function(idx, data, node) 
                                          {
                                            if(!yes)
                                              yes = data.id == id ? true : false;
                                          }
                                    );
        if(yes)
          alert("The selected tester is already selected");

        data = $('#users').DataTable().row($(this).parents('tr')).data(); //.cell(0).data(id);
        data['id'] = id;
      }); 

      $("#users tbody").on("change", "#active", function () {
        data = $('#users').DataTable().row($(this).parents('tr')).data(); //.cell(0).data(id);
        data['active'] = this.checked;
      });
  });

  $("#submit").click(function(e) {
    e.preventDefault();

    rows = [];
    $('#users').DataTable().rows(function(idx, data, node) 
                                          {
                                            rows.push(Array(data.id, data.active));
                                          }
                                    );

    var $form = $("#editForm")
    $.ajax({
            type: "post",
            url: $form.attr("action"),
            data: JSON.stringify([$("#id").val(), rows]),
            contentType: "application/json",
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
                $("#status").html(responseData.message);
                $("#status").show();
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })    
    });     
</script>