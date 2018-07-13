<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash - Incidents</title>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/metisMenu/metisMenu.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/dist/css/sb-admin-2.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/morrisjs/morris.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/morrisjs/morris.css')?>" rel="stylesheet">

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
                <a class="navbar-brand" href="<?php echo base_url();?>">VulDash - User Profile</a>
            </div>
            <!-- /.navbar-header -->

            <?php echo $navbar;?>

            <?php echo $menubar;?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Profile</h1>
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
                <div id="status" class="alert" role="alert" style="display:none"></div>                      
            </div>  
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i> My data
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="editForm" class="form-horizontal"> 
                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="name">Name:</label>
                                  <div class="col-sm-10">
                                    <input type="name" name="name" class="form-control" id="name" value="<?php echo $user->name;?>" readonly="readonly">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="mail">Mail:</label>
                                  <div class="col-sm-10">
                                    <input type="mail" name="mail" class="form-control" id="mail" value="<?php echo $user->username;?>"  readonly="readonly">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="control-label col-sm-2" for="profile">Profile:</label>
                                  <div class="col-sm-10">
                                    <select name="profile" class="form-control" id="profile" placeholder="Select Profile" disabled>
                                    <option value="A" <?php echo ($user->profileId == 'A' ? 'selected' : ''); ?>>Administrator</option>
                                    <option value="T" <?php echo ($user->profileId == 'T' ? 'selected' : ''); ?>>Tester</option>
                                    <option value="G" <?php echo ($user->profileId == 'G' ? 'selected' : ''); ?>>Gerential User</option>
                                    <option value="E" <?php echo ($user->profileId == 'E' ? 'selected' : ''); ?>>Technician</option>
                                    </select>            
                                  </div>
                                </div>        
                            </form>
                            <button type="button" id="schangepass" class="btn btn-default">Change my password</button>
                        </div>
                        <!-- /.panel-body -->
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-stack-overflow fa-fw"></i> My projects
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Init Date</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $cont = 1;
                                foreach($projects as $r): ?>                                    
                                    <tr class="">
                                        <td><a href="<?php echo base_url('projects/incidents/'.$r['projectId']);?>"><?php echo $r['projectName']; ?></a></td>
                                        <td><?php echo $r['initDate']; ?></td>
                                        <td><?php echo $r['state']; ?></td>
                                    </tr>
                                <?php
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>                    
                </div>                
                <div class="col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications
                        </div>
                        <div class="panel-body">
                            <div class="list-group">
                                <?php
                                foreach($notifications as $not): ?>
                                    <a href="#" class="list-group-item">
                                        <i class="fa fa-comment fa-fw"></i> 
                                        Project: <?php echo $not['projectName']; ?></br>
                                        User: <?php echo $not['userName']; ?></br>
                                        Event: <?php echo $not['event']; ?>

                                        <?php
                                        $eventDate = date("Y-m-d", strtotime($not['date']));
                                        $today = date("Y-m-d", strtotime("today"));
    
                                        if($eventDate == $today):
                                            $d1 = new DateTime(date("Y-m-d H:i", strtotime("now")));
                                            $d2 = new DateTime(date("Y-m-d H:i", strtotime($not['date'])));
                                            $diff = $d1->diff($d2);

                                            if($diff->h > 1):
                                                $toshow = date_format($d2, 'H:i');
                                            else:
                                                $toshow = $diff->i . " mins ago";
                                            endif;
                                        else:
                                            $d1 = new DateTime(date("Y-m-d", strtotime("today")));
                                            $d2 = new DateTime(date("Y-m-d", strtotime($not['date'])));
                                            $diff = $d1->diff($d2);

                                            if($diff->d == 1):
                                                $toshow = "Yesterday";
                                            else:
                                                $toshow = date_format($d2, 'Y-m-d');
                                            endif;
                                        endif;
                                        ?>
                                        <span class="pull-right text-muted small"><em><?php echo $toshow; ?></em>
                                        </span>
                                    </a>                                                                
                                <?php
                                endforeach; ?>
                            </div>
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
            $("#schangepass").click(function(e) {
                e.preventDefault();
            });        
        });                  
    </script>
</body>

</html>
