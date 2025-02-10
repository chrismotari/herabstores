@extends('layouts.app')
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
		<form action=" {{ route('users.store') }} " method="POST" enctype="multipart/form-data">
			@include('users.form')
		</form>
	</div>
</div>
@endsection

@section('modals')
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">New User</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal">
				</button>
			</div>
			<div class="modal-body">
			<form action=" {{ route('users.store') }} " method="POST" enctype="multipart/form-data">
				@include('users.form')
			</form>
			</div>
			
		</div>
	</div>
</div>
@endsection