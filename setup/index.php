<?php

error_reporting(0); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/database.php';

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/database.php file to 777");
		}

		// If no errors, redirect to next step
		if(!isset($message)) {
		  $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
      $redir .= "://".$_SERVER['HTTP_HOST'];
      $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
      //$redir = str_replace('install/','',$redir); 
			header( 'Location: ' . $redir . 'setup2.php' ) ;
		}

	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>VulDash - Setup</title>

    <!-- Bootstrap Core CSS -->
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../assets/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                <a class="navbar-brand" href="#" style="font-size:25px">VulDash - Setup</a>
            </div>
            <!-- /.navbar-header -->
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-offset-3">
                		<br/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Setup</h3>
                        </div>       
                        <div class="panel-body">
    											<?php if(is_writable($db_config_path)){?>

		  											<?php if(isset($message)) {echo '<p class="alert alert-danger">' . $message . '</p>';}?>

													  <form id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
											        <fieldset>
											        	<legend>Database settings</legend>
											        	<div class="form-group">
											          	<label for="hostname">Hostname</label>
											          	<input type="text" id="hostname" value="localhost" class="form-control" name="hostname" />
											          </div>
											          <div class="form-group">
											          	<label for="username">Username</label>
											          	<input type="text" id="username" class="form-control" name="username" />
											          </div>
											          <div class="form-group">
											          	<label for="password">Password</label>
											          	<input type="password" id="password" class="form-control" name="password" />
											          </div>
											          <div class="form-group">
											          	<label for="database">Database Name</label>
											          	<input type="text" id="database" class="form-control" name="database" />
											          </div>
											          <input type="submit" value="Setup Database" id="submit" class="btn btn-lg btn-success btn-block"/>
											        </fieldset>
													  </form>

	  											<?php } else { ?>
										      	<p class="alert alert-danger">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
											  	<?php } ?>
											  </div>
										</div>
								</div>
						</div>
				</div>
		</div>
    <!-- jQuery -->
    <script src="../assets/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url('assets/metisMenu/metisMenu.min.js')?>"></script>    

    <!-- Custom Theme JavaScript -->
    <script src="../assets/dist/js/sb-admin-2.js"></script>

	</body>
</html>
