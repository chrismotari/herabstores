@extends('layouts.backend', ['activePage' => 'users', 'title' => 'Users'])
@section('title', $this_user->name)
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 flex-wrap align-items-start">
                    <div class="col-md-8">
                        <div class="user d-sm-flex d-block pe-md-5 pe-0">
                            <img src="{{$this_user->photo_url}}" alt="">
                            <div class="ms-sm-3 ms-0 me-md-5 md-0">
                                <h5 class="mb-1"><a href="javascript:void(0);" class="text-dark">{{$this_user->name}}</a></h5>
                                <div class="listline-wrapper mb-2">
                                    <span class="item"><i class="text-primary far fa-envelope"></i>{{$this_user->email}}</span>
                                    <span class="item"><i class="text-primary far fa-id-badge"></i>{{$this_user->phone}}</span>
                                    <span class="item"><i class="text-primary fas fa-map-marker-alt"></i>{{$this_user->city}}, {{$this_user->country->name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="card-body pt-0">
                    <h4 class="card-title">Update Profile</h4>
                    <div class="row">
                        <form action="{{route('users.update', $this_user->id)}}" method="post">
                            @method('PATCH')
                            @include('users.form')
                            
                        </form>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection