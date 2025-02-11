@extends('layouts.backend', ['activePage' => 'users', 'title' => 'Users'])
@section('title', 'Users')
@section('add_button')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Add New</button>
@endsection
@section('content')
<div class="row">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
	<div class="col-xl-12">
		<form action=" {{ route('products.store') }} " method="POST" enctype="multipart/form-data">
			@csrf
			@include('products.form')
		</form>
	</div>
</div>
@endsection

@section('modals')
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">New User</h5>
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