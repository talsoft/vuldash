<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo $title;?></h4>
    </div>
    <div class="modal-body">
      <div id="statusLog" class="alert alert-success" role="alert" style="display:none"></div>
      <table width="100%" class="table table-striped table-bordered table-hover" id="logs">
          <thead>
              <tr>
                  <th>Date</th>
                  <th>User</th>
                  <th>State</th>
                  <th>Detail</th>
              </tr>
          </thead>
      </table>      
    </div>
    <div class="modal-footer">
      <button type="button" id="newRecord" class="btn btn-default">New Record</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
    </div>    
  </div>
</div>

<script>
  $(document).ready(function() {
      $('#logs').DataTable({
          responsive: true,
          "info":     false,
          "searching":false, 
          "paging":   false,         
          "ajax": "<?php echo base_url('logs/list/' . $incident->id);?>",
          "order": [[ 1, "desc" ]],
          "columns": [
                      { "data": "date", "Title": "Date" },
                      { "data": "user", "Title": "User" },
                      { "data": "state", "Title": "State" },
                      { "data": "id", "bSortable": false, "sTitle": "Detail", 
                                "mRender": function ( data, type, full )
                                                {
                                                    return '' +
                                                           '<div class="btn-group">' + 
                                                           '<button class="btn btn-default" id="view" ' +
                                                           'title="Edit">' + 
                                                           '<i class="fa fa-eye fa-fw"></i></button>' +
                                                           '</div>'
                                                }
                            }                      
                  ],
      });


      $("#newRecord").on("click", function (e) {;
          $.ajax({
              url: "<?php echo base_url('logs/create/' . $incident->id);?>",
              success: function(result) {
                  $("#modalNew").html(result); 
                  $("#modalNew").modal('show'); 
              }
          });      
          return false;            
      });

      $("#logs tbody").on("click", "#view", function () {
          var id = $(this).parent().parent().parent().attr("id");
          $.ajax({
              url: "<?php echo base_url('incidents/logdetail/');?>" + id,
              success: function(result) {
                  $("#modalDetail").html(result); 
                  $("#modalDetail").modal('show'); 
              }
          });      
          return false;            
      });      
  });
       
</script>