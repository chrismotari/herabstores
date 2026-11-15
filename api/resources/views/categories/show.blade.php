@extends('layouts.backend', ['activePage' => 'categories', 'title' => $selected_category->name])
@section('title', $selected_category->name)

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
		<form action=" {{ route('categories.update', $selected_category) }} " method="POST" enctype="multipart/form-data">
			@method('PATCH')
			@include('categories.form')
		</form>
	</div>
</div>
@endsection

