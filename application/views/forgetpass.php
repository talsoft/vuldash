<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url('assets/metisMenu/metisMenu.min.css')?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/dist/css/sb-admin-2.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
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
                <a class="navbar-brand" href="<?php echo base_url();?>" style="font-size:25px">VulDash</a>
            </div>
            <!-- /.navbar-header -->
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">I forgot my password</h3>
                        </div>       
                        <div class="panel-body">                            
                            <?php 
                            if($return == 1):
                                if($this->session->flashdata('correct_recover')):?>
                                    <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('correct_recover');?>
                                    </div>
                                    <a href="<?php echo base_url('login');?>" class="btn btn-lg btn-success btn-block">Goto Login</a>
                                <?php 
                                else:
                                    if($this->session->flashdata('incorrect_recover')):?>
                                        <div class="alert alert-danger">
                                        <?php echo $this->session->flashdata('incorrect_recover');?>
                                        </div>
                                    <?php 
                                    endif;   
                                endif;
                            else:
                            ?>                            
                                <p>Enter your registered email address in the system</p>
                                <?php echo form_open(base_url().'users/recover')?>
                                    <fieldset>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Email" name="username" type="text" autofocus>
                                            <?php echo form_error('username', '<p class="text-danger">', '</p>');?>
                                        </div>
                                        <?php echo form_submit('buttonsubmit', 'Confirm', 'class="btn btn-lg btn-success btn-block"');?>
                                    </fieldset>
                                <?php echo form_close();?>
                            <?php
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/jquery/jquery.min.js')?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url('assets/metisMenu/metisMenu.min.js')?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url('assets/dist/js/sb-admin-2.js')?>"></script>

</body>

</html>
