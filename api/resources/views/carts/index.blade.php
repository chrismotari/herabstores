@extends('layouts.backend', ['activePage' => 'carts', 'title' => 'Carts'])
@section('title', 'Carts')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="table-responsive">
            <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Number of items</th>
                        <th>Total Amount</th>
                        <th>Last Modified</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carts as $cart)
                    <tr>
                        @if ($cart->user)
                            <td><a href="{{ route('users.show', $cart->user) }}">{{$cart->user->name}}</a></td>
                        @else    
                            <td>Guest</a></td>
                        @endif
                        <td class="wspace-no">{{$cart->items_count}}</td>
                        <td class="wspace-no">{{$cart->amount}}</td>
                        <td>{{$cart->updated_at}}</td>
                        <td><span class="badge badge-success badge-lg light">{{$cart->status}}</span></td>
                        <td>
                            <div class="action-buttons d-flex justify-content-end">
                                <a href="javascript:void(0);" class="btn btn-success light mr-2">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-secondary light mr-2">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <p  class="text-center">No data found</p>
                  
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>     
@endsection
