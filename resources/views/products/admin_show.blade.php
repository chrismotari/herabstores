@extends('layouts.backend', ['activePage' => 'products', 'title' => $selected_product->name])
@section('title', $selected_product->name)

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
		<form action=" {{ route('products.update', $selected_product) }} " method="POST" enctype="multipart/form-data">
			@method('PATCH')
			@include('products.form')
		</form>
	</div>
</div>
@endsection

