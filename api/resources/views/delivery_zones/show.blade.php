@extends('layouts.backend', ['activePage' => 'delivery_zones', 'title' => $selected_delivery_zone->name])
@section('title', $selected_delivery_zone->name)

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
		<form action=" {{ route('delivery_zones.update', $selected_delivery_zone) }} " method="POST" enctype="multipart/form-data">
			@method('PATCH')
			@include('delivery_zones.form')
		</form>
	</div>
</div>
@endsection

