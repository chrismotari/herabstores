@extends('layouts.app')
@section('content')
<div class="breadcrumb-area breadcrumb-bg-two">
    <div class="container custom-container">
    <div class="row">
    <div class="col-xl-12">
        <div class="table-responsive">
            <table class="table display mb-4 dataTablesCard job-table table-responsive-xl card-table" id="example5">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><a href="{{ route('products.show', $product) }}">{{$product->name}}</a></td>
                        <td class="wspace-no">{{$product->category->name}}</td>
                        <td>{{$product->created_at}}</td>
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
                        <td colspan="7" class="text-center">No job adverts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
</div>

@endsection