@extends('layouts.admin', ['title' => $term->id ? "Edit: ".$term->name : "Create Service Terms"])


@section('pre')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="fs-5 color-900 mt-1 mb-0">{{$term->id? "Edit: ".$term->name : "Create Service Terms"}}</h1>
            <small class="text-muted">{{$term->id?"Manage your": "Create new"}} Terms of Service for
                different types of accounts</small>
        </div>

    </div> <!-- .row end -->

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form method="post" action={{$term->id ? "/admin/terms/$term->id" : "/admin/terms"}}>
                @csrf
                @method($term->id ? "PUT" : "POST")
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div class="form-floating">
                            <input type="text" name="name" class="form-control" value="{{$term->name}}">
                            <label>Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <textarea name="body" class="tinymce">{!! $term->body !!}</textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary pull-right ladda" data-style="zoom-out">
                                <i class="fa fa-save"></i> {{$term->name?"Save": "Create"}} Service Terms
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
