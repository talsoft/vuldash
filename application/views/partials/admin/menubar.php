<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="<?php echo base_url();?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?php echo base_url('projects');?>"><i class="fa fa-stack-overflow fa-fw"></i> Projects</a>
            </li>
            <li>
                <a href="<?php echo base_url('clients');?>"><i class="fa fa-institution fa-fw"></i> Clients</a>
            </li>
            <li>
                <a href="<?php echo base_url('internalusers');?>"><i class="fa fa-users fa-fw"></i> Users</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> System<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo base_url('projectstype');?>">Project type</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('projectsstate');?>">Project State</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('incidentstype');?>">Incident type</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('incidentsstate');?>">Incident state</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('objectivestype');?>">Objective type</a>
                    </li>
                </ul>
            </li>            
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
