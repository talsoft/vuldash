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
    <link href="<?php echo base_url('assets/app.css')?>" rel="stylesheet">
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
                <a class="navbar-brand" href="<?php echo base_url();?>">VulDash - Incidents</a>
            </div>
            <!-- /.navbar-header -->

            <?php echo $navbar;?>

            <?php echo $menubar;?>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <?php if(isset($project)):?>
                        <h1 class="page-header">Incidents for <?php echo $project->name ?></h1>
                    <?php else: ?>
                        <h1 class="page-header">Incidents</h1>
                    <?php endif ?>
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
                <div class="col-lg-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Incidents
                            <?php if($this->session->userdata('profileId') == 'A' || $this->session->userdata('profileId') == 'T'): ?>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" id="new">New</a></li>
                                        <li><a href="#" id="zapPlugin" data="zapimport">Zap Import</a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="status" class="alert alert-success" role="alert" style="display:none"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="incidents">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>CVSS</th>
                                        <th>State</th>
                                        <th width="190px">id</th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Incidents by Date Chart
                        </div>
                        <div class="panel-body">
                            <div id="stack-chart"></div>
                            <div id="stack-legend" class="chart-legend"></div>
                            <div id="stack-total" class="chart-total"></div>                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Risk Chart
                        </div>
                        <div class="panel-body">
                            <div id="donut-chart"></div>
                            <div id="donut-legend" class="chart-legend"></div>
                            <div id="donut-total" class="chart-total"></div>                            
                        </div>
                        <!-- /.panel-body -->
                    </div>                

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Audit's worth
                        </div>
                        <div class="panel-body">
                            <div id="bar-chart"></div>
                            <div id="bar-legend" class="chart-legend"></div>
                            <div id="bar-total" class="chart-total"></div>
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
    <div id="modalNew" class="modal fade" role="dialog"></div>
    <div id="modalDetail" class="modal fade" role="dialog"></div>
    <div id="modalImport" class="modal fade" role="dialog"></div>    

    <script src="<?php echo base_url('assets/jquery/jquery.min.js')?>"></script>
    <script src="<?php echo base_url('assets/moment/moment.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-datetimepicker.min.js')?>"></script>
    <script src="<?php echo base_url('assets/metisMenu/metisMenu.min.js')?>"></script>
    <script src="<?php echo base_url('assets/dist/js/sb-admin-2.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-plugins/dataTables.bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-responsive/dataTables.responsive.js')?>"></script>
    <script src="<?php echo base_url('assets/tinymce/tinymce.min.js')?>"></script>
    <script src="<?php echo base_url('assets/raphael/raphael.min.js')?>"></script>
    <script src="<?php echo base_url('assets/morrisjs/morris.min.js')?>"></script>
    <script src="<?php echo base_url('assets/morrisjs/morris.min.js')?>"></script>

    <script>
        $(document).ready(function() {
            chart = Morris.Donut({
                element: 'donut-chart',
                data: [{
                    label: "Critical",
                    value: 0
                }, 
                {
                    label: "High",
                    value: 0
                },                 
                {
                    label: "Medium",
                    value: 0
                }, 
                {
                    label: "Low",
                    value: 0
                },                 
                {
                    label: "Info",
                    value: 0
                }],
                colors: ["#C62A82", "#FE2E2E", "#FFBF00", "#85E576", "#7395CC"],
                resize: true,
            });

            moneychart = Morris.Bar({
                element: 'bar-chart',
                data: [{ y: '$', a: 0, b: 0, c:0, d:0}],
                barColors: ["#C62A82", "#FE2E2E", "#FFBF00", "#85E576", "#7395CC"],
                resize: true,
                stacked: true,
                xkey: 'y',
                ykeys: ['a', 'b', 'c', 'd', 'e'],
                labels: ['Critical', 'High', 'Medium', 'Low', "Info"],
                hideHover: 'always'
            });

            stacked = Morris.Bar({
                element: 'stack-chart',
                data: [{ y: '2000-01-01', a: 0, b: 0, c: 0, d: 0 }],
                barColors: ["#C62A82", "#FE2E2E", "#FFBF00", "#85E576", "#7395CC"],
                xkey: 'y',
                ykeys: ['a', 'b', 'c', 'd', 'e'],
                stacked: true,
                labels: ['Critical', 'High', 'Medium', 'Low', "Info"],
                hideHover: 'always'
            });

            $.ajax({
                type: "GET",
                url:  "<?php echo base_url('incidents/chart/' . $project->id);?>",
                success: function(resp)                    
                {   
                    chart.setData(resp);
                    var total = 0;
                    resp.forEach(function(e, i) {
                        var legendItem = $('<span></span>').text(e['label'] + ": " + e['value']).prepend('<i>&nbsp;</i>');
                        legendItem.find('i').css('backgroundColor', chart.options.colors[i]);
                        $('#donut-legend').append(legendItem)
                        total = total + e['value'];
                    });                    
                    var totalLegend = $('<span></span>').text("Total: " + total);
                    $('#donut-total').append(totalLegend);
                }
            });            

            $.ajax({
                type: "GET",
                url:  "<?php echo base_url('incidents/moneychart/' . $project->id);?>",
                success: function(resp)                    
                {   
                    moneychart.setData(resp);
                    var total = 0;                    
                    moneychart.options.labels.forEach(function(label, i) {
                        var legendItem = $('<span></span>').text(label + ": $" + moneychart.options.data[0][moneychart.options.ykeys[i]]).prepend('<i>&nbsp;</i>');
                        legendItem.find('i').css('backgroundColor', moneychart.options.barColors[i]);
                        $('#bar-legend').append(legendItem)
                        total = total + moneychart.options.data[0][moneychart.options.ykeys[i]];
                    }); 
                    var totalLegend = $('<span></span>').text("Total: $" + total);
                    $('#bar-total').append(totalLegend);
                }
            });

            $.ajax({
                type: "GET",
                url:  "<?php echo base_url('incidents/incidentschart/' . $project->id);?>",
                success: function(resp)                    
                {   
                    stacked.setData(resp);
                }
            });

            $('#incidents').DataTable({
                responsive: true,
                "ajax": "<?php echo base_url('incidents/list/' . $project->id);?>",
                "order": [[ 3, "desc" ]],
                "createdRow": function( row, data, dataIndex)
                                        {
                                            switch(data['risk']) {
                                                case "C":
                                                    $(row).addClass('critical');
                                                    break;
                                                case "H":
                                                    $(row).addClass('high');
                                                    break;
                                                case "M":
                                                    $(row).addClass('medium');
                                                    break;
                                                case "L":
                                                    $(row).addClass('low');
                                                    break;
                                                case "I":
                                                    $(row).addClass('info');
                                                    break;                                                
                                            }                                                                                        
                },                
                "columns": [
                            { "data": "date", "Title": "Date" },
                            { "data": "type", "Title": "Type" },
                            { "data": "description", "Title": "Description" },
                            { "data": "cvss", "Title": "CVSS" },
                            { "data": "state", "Title": "State" },
                            { "data": "id", "bSortable": false, "sTitle": "Actions", 
                                "mRender": function ( data, type, full )
                                                {
                                                    return '' +
                                                           '<div class="btn-group">' + 
                                                           <?php if($this->session->userdata('profileId') == 'A' || $this->session->userdata('profileId') == 'T'): ?>
                                                           '<button class="btn btn-default btn-sm" id="edit" ' +
                                                           'title="Edit">' + 
                                                           '<i class="fa fa-edit fa-fw"></i></button>' +
                                                           <?php else:?>
                                                           '<button class="btn btn-default btn-sm" id="view" ' +
                                                           'title="View">' + 
                                                           '<i class="fa fa-search fa-fw"></i></button>' +
                                                           <?php endif;?>
                                                           <?php if($this->session->userdata('profileId') == 'A' || $this->session->userdata('profileId') == 'T'): ?>
                                                           '<button class="btn btn-default btn-sm" id="delete" ' +
                                                           'title="Delete">' +
                                                           '<i class="fa fa-times-circle fa-fw"></i></button>' +
                                                           <?php endif;?>
                                                           <?php if($this->session->userdata('profileId') == 'A' || $this->session->userdata('profileId') == 'T' || $this->session->userdata('profileId') == 'E'): ?>
                                                           '<button class="btn btn-default btn-sm" id="tracing" ' +
                                                           'title="Tracing">' +
                                                           '<i class="fa fa-th-list fa-fw"></i></button>' +
                                                           '<button class="btn btn-default btn-sm" id="report" ' +
                                                           'title="Report">' +
                                                           '<i class="fa fa-print fa-fw"></i></button>' +                                                           
                                                           <?php endif;?>
                                                           '</div>'
                                                }
                            }
                        ],
                "columnDefs": [
                  { "width": "120px", "targets": 0 }
                ],                        
            });

            $("#incidents tbody").on("click", "#edit", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('incidents/edit');?>",
                    data: {id: id},
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal('show'); 
                    }
                });      
                return false;            
            });

            $("#new").on("click", function () {
                $.ajax({
                    url: "<?php echo base_url('incidents/create/' . $project->id);?>",
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal('show'); 
                    }
                });      
                return false;            
            });

            $("#zapPlugin").on("click", function () {
                plugin = $(this).attr("data");
                $.ajax({
                    url: "<?php echo base_url('incidents/zapplugin/' . $project->id . '/');?>" + plugin,
                    success: function(result) {
                        $("#modalImport").html(result); 
                        $("#modalImport").modal('show'); 
                    }
                });                  
                return false;            
              });  

            $("#incidents tbody").on("click", "#view", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('incidents/view/');?>" + id,
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal('show'); 
                    }
                });      
                return false;            
            });

            $("#incidents tbody").on("click", "#tracing", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('incidents/log/');?>" + id,
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal('show'); 
                    }
                });      
                return false;            
            });

            $("#incidents tbody").on("click", "#report", function () {
                var id = $(this).parent().parent().parent().attr("id");
                window.location.href = "<?php echo base_url('reports/incident/');?>" + id;
                return false;            
            });       

             $("#incidents tbody").on("click", "#delete", function () {
                var id = $(this).parent().parent().parent().attr("id");
                $.ajax({
                    url: "<?php echo base_url('incidents/delete');?>",
                    data: {id: id},
                    success: function(result) {
                        $("#modalForm").html(result); 
                        $("#modalForm").modal()                           
                    }
                });      
                return false;            
            });
        });                  
    </script>
</body>

</html>
