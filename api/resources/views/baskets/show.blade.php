@extends('layouts.backend', ['activePage' => 'baskets', 'title' => $selected_basket->name])
@section('title', $selected_basket->name)

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
		<form action=" {{ route('baskets.update', $selected_basket) }} " method="POST" enctype="multipart/form-data">
			@method('PATCH')
			@include('baskets.form')
		</form>
	</div>
</div>
@endsection

