<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash - Clients</title>

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
                <a class="navbar-brand" href="<?php echo base_url();?>">VulDash - Clients</a>
            </div>
            <!-- /.navbar-header -->

            <?php echo $navbar;?>

            <?php echo $menubar;?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Clients</h1>
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
                            <i class="fa fa-institution fa-fw"></i> Clients Admin
                            <?php if($profileId == 'A'): ?>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" id="new">New</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="status" class="alert alert-success" role="alert" style="display:none"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="clients">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th width="220px">id</th>
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
            $('#clients').DataTable({
                responsive: true,
                "ajax": "<?php echo base_url('clients/list');?>",
                "columns": [
                            { "data": "name", "Title": "Name" },
                            { "data": "id", "bSortable": false, "sTitle": "Actions", 
                                "mRender": function ( data, type, full )
                                                {
                                                    return ''+
                                                            <?php if($profileId == 'A'):?>
                                                           '<div class="btn-group">' + 
                                                           '<button class="btn btn-default btn-sm" id="edit" ' + 
                                                           'title="Edit">' + 
                                                           '<i class="fa fa-edit fa-fw"></i></button>' +
                                                           '<button class="btn btn-default btn-sm" id="delete" ' + 
                                                           'title="Delete">' +
                                                           '<i class="fa fa-times-circle fa-fw"></i></button>' +
                                                           '</div>' +
                                                           <?php endif; ?> 
                                                           '<div class="btn-group" role="group">&nbsp;</div>' +
                                                           '<div class="btn-group">' +
                                                           '<button class="btn btn-default btn-sm" id="projects" ' +
                                                           'title="Projets">' +
                                                           '<i class="fa fa-file-text fa-fw"></i></button>' +
                                                           <?php if($profileId == 'A'):?>
                                                           '<button class="btn btn-default btn-sm" id="users" ' +
                                                           'title="Users">' + 
                                                           '<i class="fa fa-users fa-fw"></i></button>' +
                                                           <?php endif; ?> 
                                                           '</div>'; 
                                                }
                            }
                        ],
            });

            $("#clients tbody").on("click", "#edit", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('clients/edit');?>",
                    data: {id: id},
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal(); 
                    }
                });      
                return false;            
            });

            $("#clients tbody").on("click", "#projects", function () {
                var id = $(this).parent().parent().parent().attr("id");
                window.location.href = "<?php echo base_url('clients/projects/');?>" + id;
                return false;            
            });

            $("#clients tbody").on("click", "#users", function () {
                var id = $(this).parent().parent().parent().attr("id");
                window.location.href = "<?php echo base_url('clients/users/');?>" + id;
                return false;            
            });

            $("#clients tbody").on("click", "#delete", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('clients/delete');?>",
                    data: {id: id},
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal(); 
                    }
                });      
                return false;            
            });

            $("#new").on("click", function () {
                $.ajax({
                    url: "<?php echo base_url('clients/create');?>",
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal('show'); 
                    }
                });      
                return false;            
            });     
        });                  
    </script>
</body>

</html>
