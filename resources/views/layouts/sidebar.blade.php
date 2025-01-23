<div class="dlabnav">
    <div class="dlabnav-scroll">
        <div class="dropdown header-profile2 ">
            <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                <div class="header-info2 d-flex align-items-center">
                    <img src=" {{ asset('images/user.png') }}" alt="">
                    @auth
                    <div class="d-flex align-items-center sidebar-info">
                        <div>
                            <span class="font-w400 d-block">{{auth()->user()->name}}</span>
                            <small class="text-end font-w400">{{auth()->user()->main_role}}</small>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    @endauth

                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('users.show', auth()->user()->id) }}" class="dropdown-item ai-icon ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="ms-2">Profile </span>
                </a>
              
                <form action="{{ route('logout') }}" method="post"> 
                    @csrf
                    <button class="dropdown-item ai-icon" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ms-2">Logout </span>
                    </button>
                </form>
            </div>
        </div>
        <ul class="metismenu" id="menu">

            <li><a href="/" class="" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-briefcase"></i>
                    <span class="nav-text">Job</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="job_adverts">Job Adverts</a></li>
                    <li><a href="jobs/candidate-view">Available Jobs</a></li>
                    <li><a href="job-applications">Job Applications</a></li>
                    <li><a href="apply-job.html">Apply Job</a></li>
                    <li><a href="new-job.html">New Job</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="users" aria-expanded="false">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Users</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="users">Admin Users</a></li>
                    <li><a href="users">Applicant Profiles</a></li>
                    <li><a href="users/create">New User</a></li>
                    <li><a href="permissions">Permissions</a></li>
                    <li><a href="roles">Roles</a></li>
                </ul>
            </li>

            
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-050-info"></i>
                    <span class="nav-text">Help</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="app-profile.html">System Information</a></li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Contact Us</a>
                        <ul aria-expanded="false">
                            <li><a href="email-compose.html">Email</a></li>
                            <li><a href="email-inbox.html">Phone</a></li>
                        </ul>
                    </li>

                </ul>
            </li>
            
        </ul>
        <!-- <div class="plus-box">
            <p class="fs-14 font-w600 mb-2">Let Jobick Managed<br>Your Resume Easily<br></p>
            <p class="plus-box-p">Lorem ipsum dolor sit amet</p>
        </div> -->
        <div class="copyright">
            <p><strong>Unity Careers</strong> © <span class="current-year"></span> All Rights Reserved</p>
        </div>
    </div>
</div>