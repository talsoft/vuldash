<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash - Incidents State</title>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/metisMenu/metisMenu.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/dist/css/sb-admin-2.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/morrisjs/morris.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>">VulDash - Incidents state</a>
            </div>
            <!-- /.navbar-header -->

            <?php echo $navbar;?>

            <?php echo $menubar;?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Incidents State</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <?php if($this->session->flashdata('incorrect_credentials')):?>
                    <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('incorrect_credentials');?>
                    </div>
                <?php endif ?>                         
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-wrench fa-fw"></i> Incidents State
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="status" class="alert alert-success" role="alert" style="display:none"></div>
                            <div class="panel panel-default">
                                <div class="panel-body">                                                        
                                    <form id="editForm" class="form-horizontal" action="<?php echo base_url('incidentsstate/save');?>">
                                        <input type="hidden" name="id" id="id" value="">
                                        <div class="form-group">
                                            <label class="control-label col-sm-1" for="description">Name:</label>
                                            <div class="col-sm-8">
                                                <input type="description" name="name" class="form-control" id="name" placeholder="Enter a Name" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-1" for="description">Order:</label>
                                            <div class="col-sm-5">
                                                <input type="" name="order" class="form-control" id="order" placeholder="Enter a Order" value="">
                                            </div>
                                            <button type="button" id="submit" class="btn btn-default">Save</button>
                                            <button type="button" id="cancel" class="btn btn-default">Cancel</button>
                                        </div>
                                    </form>    
                                </div>
                            </div>

                            <table width="100%" class="table table-striped table-bordered table-hover" id="data">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Order</th>
                                        <th width="250px">id</th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- modal window -->
    <div id="modalForm" class="modal fade" role="dialog"></div>

    <script src="<?php echo base_url('assets/jquery/jquery.min.js')?>"></script>
    <script src="<?php echo base_url('assets/moment/moment.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-datetimepicker.min.js')?>"></script>
    <script src="<?php echo base_url('assets/metisMenu/metisMenu.min.js')?>"></script>
    <script src="<?php echo base_url('assets/dist/js/sb-admin-2.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-plugins/dataTables.bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-responsive/dataTables.responsive.js')?>"></script>

    <script>
        $(document).ready(function() {
            $('#data').DataTable({
                responsive: true,
                "ajax": "<?php echo base_url('incidentsstate/list');?>",
                "columns": [
                            { "data": "name", "Title": "Name" },
                            { "data": "order", "Title": "Order" },
                            { "data": "id", "bSortable": false, "sTitle": "Actions", 
                                "mRender": function ( data, type, full )
                                                {
                                                    return '' +
                                                           '<div class="btn-group">' +
                                                           '<button class="btn btn-default btn-sm" id="edit" ' +
                                                           'title="Edit">' + 
                                                           '<i class="fa fa-edit fa-fw"></i></button>' +
                                                           '<button class="btn btn-default btn-sm" id="delete" ' +
                                                           'title="Delete">' +
                                                           '<i class="fa fa-times-circle fa-fw"></i></button>' +          
                                                           '</div>'; 
                                                }
                            }
                        ],
            });

            $("#data tbody").on("click", "#edit", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('incidentsstate/edit');?>",
                    data: {id: id},
                    success: function(result) {
                        $("#id").val(result.id);
                        $("#name").val(result.name);
                        $("#order").val(result.listOrder);
                    }
                });                
                return false;            
            });

            $("#data tbody").on("click", "#delete", function () {
                if(confirm("Delete the record?"))
                {                
                    var id = $(this).parent().parent().parent().attr("id");
                    $.ajax({
                        url: "<?php echo base_url('incidentsstate/delete');?>",
                        data: {id: id},
                        success: function(result) {
                            $('#data').DataTable().ajax.reload();  
                        }
                    });                
                }
                return false;            
            });

            $("#cancel").click(function(e) {
                $("#id").val("");
                $("#name").val("");
                $("#order").val("");                
            });

            $("#submit").click(function(e) {
                e.preventDefault();

                var $form = $("#editForm");
                $.ajax({
                        type: "post",
                        url: $form.attr("action"),
                        data: $("#editForm").serialize(),
                        contentType: "application/x-www-form-urlencoded",
                        success: function(responseData) {
                            if(responseData.status == "ok")
                            {
                                $("#status").html(responseData.message);
                                $("#status").show().delay(2000).fadeOut();
                                $("#id").val("");
                                $("#name").val("");
                                $("#order").val("");                                
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
        });                  
    </script>
</body>

</html>
