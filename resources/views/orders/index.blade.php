@extends('layouts.backend', ['activePage' => 'orders', 'title' => 'Orders'])
@section('title', 'Orders')
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Add New</button> --}}
@section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Order Number</th>
                                <th>Estate</th>
                                <th>Price</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td><a href="">{{$order->full_name}}</a></td>
                                <td>{{$order->order_number}}</td>
                                <td class="wspace-no">{{$order->estate?->name}}</td>
                                <td>KSHS {{$order->total}}</td>
                                <td>{{count($order->order_items)}}</td>
                                <td><span class="badge badge-success badge-lg light">{{$order->status}}</span></td>
                                <td>
                                    <div class="action-buttons d-flex justify-content-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-success light mr-2">
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
                            No data
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



@endsection
@section('modals')
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title">Dispatch Order</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal">
				</button>
			</div>
			<div class="modal-body">
			{{-- <form action=" {{ route('orders.store') }} " method="POST" enctype="multipart/form-data">
				@include('orders.form')
			</form> --}}
			</div>
			
		</div>
	</div>
</div>
@endsection