@extends('layouts.backend', ['activePage' => 'refs', 'title' => 'Referrers'])
@section('title', 'Referrers')
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
                            <th>No.</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Expires At</th>
                            <th>Used At</th>
                            <th>Canceled At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refs as $ref)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="wspace-no"><a href="{{ route('refs.show', $ref) }}">{{$ref->user->name}}</a></td>
                            <td>{{$ref->ref_code}}</td>
                            <td>{{$ref->expeires_at}}</td>
                            <td>{{$ref->used_at}}</td>
                            <td>{{$ref->canceled_at}}</td>
                            @if ($ref->is_active)
                                <td><span class="badge badge-success badge-lg light">Active </span></td>
                            @else
                                <td><span class="badge badge-danger badge-lg light">Inactive</span></td>
                            @endif
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
                        No data found
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
				<h2 class="modal-title">New Ref</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal">
				</button>
			</div>
			<div class="modal-body">
			<form action=" {{ route('refs.store') }} " method="POST" enctype="multipart/form-data">
				@include('refs.form')
			</form>
			</div>
			
		</div>
	</div>
</div>
@endsection