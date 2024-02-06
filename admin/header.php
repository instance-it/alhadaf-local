<div class="pace  pace-inactive" id="loaderprogress">
    <div class="pace-progress"></div>
</div>
<div class="preloader" id="preloader" style="display:none;">
    <div class="loading-item"></div>
</div>
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm expand-header">
        
        <ul class="navbar-nav theme-brand flex-row  text-center">
            <li class="nav-item theme-logo">
                <a href="index.php">
                    <img src="assets/img/logo-white.png" class="navbar-logo" alt="logo">
                </a>
            </li>
            <li class="nav-item theme-text">
                <a href="index.php" class="nav-link"> <?php echo $LoginInfo->getFullname(); ?> </a>
            </li>
            <li class="nav-item toggle-sidebar d-lg-none">
                <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><i class="bi bi-list"></i></a>
            </li>
        </ul>

        <ul class="navbar-item flex-row navbar-dropdown mr-auto">
        
            <li class="nav-item nav-page-name">
                <a href="#" class="nav-link">
                    <span id="customernamelbl"></span>
                </a>
            </li>
        </ul>
        
        <ul class="navbar-item flex-row navbar-dropdown">  
                  
            <li class="nav-item dropdown language-dropdown more-dropdown">
    
                <!-- <select class="form-control selectpicker" name="topbranchid" id="topbranchid">
                </select> -->

                <!-- <div class="dropdown custom-dropdown-icon">
                    <a class="dropdown-toggle btn" href="#" role="button" id="selectAppointmentBranch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Athwalines</span> 
                    <i class="bi bi-caret-down-fill"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="selectAppointmentBranch" id="topbranchid" name="topbranchid">
                        <a class="dropdown-item" data-value="Vesu" href="javascript:void(0);"> Vesu</a>
                        <a class="dropdown-item" data-value="Athwalines" href="javascript:void(0);"> Athwalines</a>
                        <a class="dropdown-item" data-value="Adajan" href="javascript:void(0);"> Adajan</a>
                        <a class="dropdown-item" data-value="Varachha" href="javascript:void(0);"> Varachha</a>
                    </div>
                </div> -->
            </li>
            <!--<li class="nav-item dropdown quicklink-dropdown more-dropdown">
                <div class="dropdown custom-dropdown-icon">
                    <a class="dropdown-toggle btn" href="#" role="button" id="selectQuickLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>New</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="selectQuickLinks">
                        <a class="dropdown-item" data-value="Appointment" href="appointment.php"> Appointment</a>
                    </div>
                </div>
            </li>-->
    
            <li class="nav-item header-master">
                <a href="master" data-toggle="tooltip" title="Master"><i class="bi bi-box"></i></a>
            </li>

            <!-- <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link" id="notificationDropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="badge count">18</span>
                    <i class="bi bi-bell"></i>
                </a>
            </li> -->
            

            <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <img src="assets/img/user-profile.jpg" class="img-fluid mr-2" alt="avatar">
                            <div class="media-body">
                                <h5><?php echo $LoginInfo->getFullname(); ?></h5>
                                <!-- <p>Admin</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <a href="myprofile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>My Profile</span>
                        </a>
                    </div>
                    <div class="dropdown-item logout-btn">
                        <a href="javascript:void(0);" class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>