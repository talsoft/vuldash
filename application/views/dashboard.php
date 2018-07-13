<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash - Dashboard</title>

    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/app.css')?>" rel="stylesheet">    
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
                <a class="navbar-brand" href="<?php echo base_url();?>">VulDash - Dashboard</a>
            </div>
            <!-- /.navbar-header -->

            <?php echo $navbar;?>

            <?php echo $menubar;?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
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

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-stack-overflow fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $activeprojects; ?></div>
                                    <div>Active Projects!</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url('projects');?>">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shield fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $lowincidents; ?></div>
                                    <div>New Low Incidents!</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left">&nbsp;</span>
                            <span class="pull-right">&nbsp;</span>
                            <div class="clearfix"></div>
                        </div>                        
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shield fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $modincidents; ?></div>
                                    <div>New Moderate Incidents!</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left">&nbsp;</span>
                            <span class="pull-right">&nbsp;</span>
                            <div class="clearfix"></div>
                        </div>                        
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shield fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $higincidents; ?></div>
                                    <div>New High Incidents!</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left">&nbsp;</span>
                            <span class="pull-right">&nbsp;</span>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Last Activity
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Incident</th>
                                        <th>CVSS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($lastactivity as $r): 
                                    $style = '';
                                    switch($r['risk']) {
                                        case "C":
                                            $style = 'critical';
                                            break;                                        
                                        case "H":
                                            $style = 'high';
                                            break;
                                        case "M":
                                            $style = 'medium';
                                            break;
                                        case "L":
                                            $style = 'low';
                                            break;
                                        case "I":
                                            $style = 'info';
                                            break;
                                    }                                            

                                    ?>                                    
                                    <tr class="<?php echo $style;?>">
                                        <td><a href="<?php echo base_url('projects/incidents/'.$r['projectId']);?>"><?php echo $r['projectName']; ?></a></td>
                                        <td width="100px"><?php echo date("m-d-Y", strtotime($r['date'])); ?></td>
                                        <td><?php echo $r['userName']; ?></td>
                                        <td><?php echo $r['description']; ?></td>
                                        <td class="center"><?php echo $r['cvss']; ?></td>
                                    </tr>
                                <?php
                                endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications
                        </div>
                        <!-- /.panel-heading -->
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
                            <!-- /.list-group -->
                            <a href="#" class="btn btn-default btn-block">View All</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
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

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url('assets/dist/js/sb-admin-2.js')?>"></script>

</body>

</html>
