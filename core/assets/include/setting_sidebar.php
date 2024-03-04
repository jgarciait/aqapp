<div>
        <aside class="sidebar" id="sidebar">
            <div class="toggle-button-container">
                <button type="button" class="icon-sm" id="toggleButton"><i class="fa fa-circle-dot"></i></button>
            </div>
            <nav>
                 <ul>
                    <li class="has-subnav">
                        <a href="socialHome.php">
                            <i class="fa fa-home fa-sm"></i>
                            <span class="nav-text">
                                Home
                            </span>
                        </a>
                    </li>
                    <?php if ($sysRol['ubs_sys_groups_id'] == '1') { ?> 
                        <li class="has-subnav">
                            <a href="settings.php">
                                <i class="fa fa-gear fa-2x"></i>
                                <span class="nav-text">
                                    Settings
                                </span>
                            </a>
                        </li>
                         <li class="has-subnav">
                            <a href="user_management.php">
                                <i class="fa fa-users fa-2x"></i>
                                <span class="nav-text">
                                    User Management
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="modulesList.php">
                                <i class="fa fa-circle-nodes fa-2x"></i>
                                <span class="nav-text">
                                    Module Management
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="logs.php">
                                <i class="fa fa-clock-rotate-left fa-2x"></i>
                                <span class="nav-text">
                                    Logs
                                </span>
                            </a>
                        </li>
                        <li class="has-subnav">
                            <a href="audit_trail.php">
                                <i class="fa fa-user-secret fa-2x"></i>
                                <span class="nav-text">
                                    Audit Trail
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                    <ul class="logout">
                        <li>
                            <a href="logout.php">
                                <i style="color:Tomato;"  class="fa fa-power-off fa-2x"></i>
                                <span  style="font-weight: bold; color:Tomato;" class="nav-text">
                                Log Out
                                </span>
                            </a>
                        </li>
                    </ul>
                </ul>
            </nav>
        </aside>
    </div>