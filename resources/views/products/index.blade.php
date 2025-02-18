@extends('layouts.backend', ['activePage' => 'products', 'title' => 'Products'])
@section('title', 'Products')
@section('add_button')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Add New</button>
@endsection
@section('content')


        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td><a href="{{ route('products.show', $product) }}">{{$product->name}}</a></td>
                                <td class="wspace-no">{{$product->category->name}}</td>
                                <td>KSHS {{$product->price}}</td>
                                <td>{{$product->stock}}</td>
                                <td><span class="badge badge-success badge-lg light">Active</span></td>
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
                            <tr>
                                <td colspan="7" class="text-center">No data found</td>
                            </tr>
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
				<h2 class="modal-title">New Product</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal">
				</button>
			</div>
			<div class="modal-body">
			<form action=" {{ route('products.store') }} " method="POST" enctype="multipart/form-data">
				@include('products.form')
			</form>
			</div>
			
		</div>
	</div>
</div>
@endsection