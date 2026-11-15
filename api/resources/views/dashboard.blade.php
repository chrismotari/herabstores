@extends('layouts.backend', ['activePage' => 'dashboard', 'title' => 'Dashboard'])
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row separate-row">
                            <div class="col-sm-6">
                                <div class="job-icon pb-4 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0 lh-1">342</h2>
                                            <span class="ms-3 badge badge-success light">+0.5%</span>
                                        </div>
                                        <span class="d-block mb-2">Sales this week</span>
                                    </div>
                                    <div id="NewCustomers"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pb-4 pt-4 pt-sm-0 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0 lh-1">4.2K</h2>
                                        </div>
                                        <span class="d-block mb-2">Sales last week</span>
                                    </div>
                                    <div id="NewCustomers1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pt-4 pb-sm-0 pb-4 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0 lh-1">15.67K</h2>
                                            <span class="ms-3 badge badge-danger light">-2.0%</span>
                                        </div>
                                        <span class="d-block mb-2">Sales this month</span>
                                    </div>
                                    <div id="NewCustomers2"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pt-4  d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0 lh-1">20.78K</h2>
                                        </div>
                                        <span class="d-block mb-2">Sales last month</span>
                                    </div>
                                    <div id="NewCustomers3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card " id="user-activity">
                    <div class="card-header border-0 pb-0 flex-wrap">
                        <h4 class="card-title mb-0">Overal Sales</h4>
                        <div class="mt-3 mt-sm-0">
                            <ul class="nav nav-tabs vacany-tabs style-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" data-series="Daily" href="#Daily" role="tab">Today</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" data-series="Weekly" href="#Weekly" role="tab">This Week</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" data-series="Monthly" href="#Monthly" role="tab">This Month</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-3 px-sm-3 px-0 pb-1">

                        <div class="">
                            <div class="table-responsive">
                                <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Guard</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse( App\Models\Permission::all() as $permission)
                                        <tr>
                                            <td> <a href=" {{route('permissions.show', $permission)}} " class="text-primary">{{$permission->name}}</a></td>
                                            <td>{{$permission->guard_name}}</td>
                                            <td><span class="badge badge-success badge-lg light">{{$permission->status}}</span></td>
                                            <td>
                                                <div class="action-buttons d-flex justify-content-end">
                                                    <a href="javascript:void(0);" class="btn btn-success light mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-main-icon" width="24px" height="24px" viewBox="0 0 32 32" x="0px" y="0px">
                                                            <g data-name="Layer 21">
                                                                <path d="M29,14.47A15,15,0,0,0,3,14.47a3.07,3.07,0,0,0,0,3.06,15,15,0,0,0,26,0A3.07,3.07,0,0,0,29,14.47ZM16,21a5,5,0,1,1,5-5A5,5,0,0,1,16,21Z" fill="#000000" fill-rule="nonzero"></path>
                                                                <circle cx="16" cy="16" r="3" fill="#000000" fill-rule="nonzero"></circle>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-secondary light mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>
                                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-danger light">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
                                                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            No permissions found
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card" id="user-activity1">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title mb-0">Best Sellers</h4>
                        <ul class="nav nav-tabs style-1 chart-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " data-bs-toggle="tab" data-series="Daily" href="#Daily1" role="tab">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-bs-toggle="tab" data-series="Weekly" href="#Weekly1" role="tab">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" data-series="Monthly" href="#Monthly1" role="tab">Month</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body ps-sm-3 ps-0 pb-2">
                        <div class="table-responsive">
                            <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Guard</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse( App\Models\Permission::all() as $permission)
                                    <tr>
                                        <td> <a href=" {{route('permissions.show', $permission)}} " class="text-primary">{{$permission->name}}</a></td>
                                        <td>{{$permission->guard_name}}</td>
                                        <td><span class="badge badge-success badge-lg light">{{$permission->status}}</span></td>
                                        <td>
                                            <div class="action-buttons d-flex justify-content-end">
                                                <a href="javascript:void(0);" class="btn btn-success light mr-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-main-icon" width="24px" height="24px" viewBox="0 0 32 32" x="0px" y="0px">
                                                        <g data-name="Layer 21">
                                                            <path d="M29,14.47A15,15,0,0,0,3,14.47a3.07,3.07,0,0,0,0,3.06,15,15,0,0,0,26,0A3.07,3.07,0,0,0,29,14.47ZM16,21a5,5,0,1,1,5-5A5,5,0,0,1,16,21Z" fill="#000000" fill-rule="nonzero"></path>
                                                            <circle cx="16" cy="16" r="3" fill="#000000" fill-rule="nonzero"></circle>
                                                        </g>
                                                    </svg>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-secondary light mr-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>
                                                            <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-danger light">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
                                                            <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
                                                        </g>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        No permissions found
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-4 align-items-center">
                            <h4 class="fs-20 mb-0 mt-1">New Customers</h4>
                            <div class="col-xl-6 col-sm-6">
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-green progress-animated" style="width: 35%; height:8px;" role="progressbar">
                                        <span class="sr-only">38% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                                    <span class="font-w500">Male</span>
                                    <h6 class="mb-0">42%</h6>
                                </div>
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-info progress-animated" style="width: 42%; height:8px;" role="progressbar">
                                        <span class="sr-only">45% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                                    <span class="font-w500">Female</span>
                                    <h6 class="mb-0">45%</h6>
                                </div>
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-blue progress-animated" style="width: 3%; height:8px;" role="progressbar">
                                        <span class="sr-only">85% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 justify-content-between">
                                    <span class="font-w500">Unidentified</span>
                                    <h6 class="mb-0">3%</h6>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pb-2">
                        <h4 class="card-title mb-0">Recent Activity</h4>
                        <div>
                            <select class="default-select dashboard-select">
                                <option data-display="Newest">Newest</option>
                                <option value="1">latest</option>

                                <option value="2">oldest</option>
                            </select>
                            <div class="dropdown custom-dropdown mb-0">
                                <div class="btn sharp tp-btn dark-btn" data-bs-toggle="dropdown">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="var(--text)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="var(--text)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="var(--text)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0);">Details</a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body loadmore-content pb-0 pt-3 list-grid-area dlab-scroll height370 recent-activity-wrapper" id="RecentActivityContent">
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle cx="8.5" cy="8.5" r="8.5" fill="var(--primary)" />
                                </svg>
                            </span>
                            <div class="d-flex align-items-center list-item-bx">
                                <div class="ms-3">
                                    <h6 class="mb-1">Bubles Studios ordered 5 mangoes</h6>
                                    <p class="mb-0">4min ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle cx="8.5" cy="8.5" r="8.5" fill="#d9d9d9" />
                                </svg>
                            </span>
                            <div class="d-flex align-items-center list-item-bx">
                               
                                <div class="ms-3">
                                    <h6 class="mb-1">Elextra bought skuma wiki</h6>
                                    <p class="mb-0">5min ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle cx="8.5" cy="8.5" r="8.5" fill="#d9d9d9" />
                                </svg>
                            </span>
                            <div class="d-flex align-items-center list-item-bx">
                                
                                <div class="ms-3">
                                    <h6 class="mb-1">Highspeed just dropped off a delivery</h6>
                                    <p class="mb-0">6min ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle cx="8.5" cy="8.5" r="8.5" fill="#d9d9d9" />
                                </svg>
                            </span>
                            <div class="d-flex align-items-center list-item-bx">
                                
                                <div class="ms-3">
                                    <h6 class="mb-1">Kleon returned his order</h6>
                                    <p class="mb-0">7min ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle cx="8.5" cy="8.5" r="8.5" fill="#d9d9d9" />
                                </svg>
                            </span>
                            <div class="d-flex align-items-center list-item-bx">
                                
                                <div class="ms-3">
                                    <h6 class="mb-1">Elextra has left us a 5 star rating</h6>
                                    <p class="mb-0">8min ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 text-center">
                        <a href="javascript:void(0);" class="btn btn-outline-primary m-auto " >View more</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>
@endsection