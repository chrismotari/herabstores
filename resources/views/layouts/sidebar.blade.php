<div class="dlabnav">
    <div class="dlabnav-scroll">
        <div class="dropdown header-profile2 ">
            <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                <div class="header-info2 d-flex align-items-center">
                    <img src=" {{ asset('backend/images/user.png') }}" alt="">
                    @auth
                    <div class="d-flex align-items-center sidebar-info">
                        <div>
                            <span class="font-w400 d-block">{{auth()->user()->name}}</span>
                            <small class="text-end font-w400">Admin</small>
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
                    <i class="fa fa-eye"></i>
                    <span class="nav-text">Customer View</span>
                </a>
            </li>
            <li><a href="/" class="" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-briefcase"></i>
                    <span class="nav-text">Products</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('products.index') }}">All Products</a></li>
                    <li><a href="baskets">Baskets</a></li>
                    <li><a href="offers">Offers & Promotions</a></li>
                    <li><a href="{{ route('products.create') }}">Add Product</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-tasks"></i>
                    <span class="nav-text">Categories</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href=" {{route('categories.index')}} ">All categories</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="nav-text">Orders</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href=" {{route('orders.index')}} ">New Orders</a></li>
                    <li><a href=" {{route('orders.index')}} ">All Orders</a></li>
                    <li><a href="#">Reported Cases</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="users" aria-expanded="false">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">User Management</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route ('users.index') }}">Store managers</a></li>
                    <li><a href="{{ route ('users.customers') }}">Customer Profiles</a></li>
                    <li><a href="{{ route ('users.create') }}">New User</a></li>
                    <li><a href="{{ route ('permissions.index') }}">Permissions</a></li>
                    <li><a href="{{ route ('roles.index') }}">Roles</a></li>
                </ul>
            </li>

            
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-050-info"></i>
                    <span class="nav-text">Help</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">System Information</a></li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Contact Us</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Email</a></li>
                            <li><a href="#">Phone</a></li>
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
            <p><a href="https://saibadigital.tech" target="none"><strong>Saiba Digital</strong></a> © <span class="current-year"></span> All Rights Reserved</p>
        </div>
    </div>
</div>