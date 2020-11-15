@extends('layouts.app')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger">
  <p>{{ $message }}</p>
</div>
@endif

{!! Form::open(array('route' => 'message_notif.send','method'=>'POST')) !!}
    {{ csrf_field() }}

    <div class="form-group">
        <label for="title">To (User Email):</label>
        {!! Form::text('to', null, array('placeholder' => 'user@email.com','class' => 'form-control')) !!}
    </div>
    
    <div class="form-group">
        <label for="title">Title:</label>
        {!! Form::text('title', null, array('placeholder' => 'Title Message','class' => 'form-control')) !!}
    </div>

    <div class="form-group">
        <label for="title">Short Desc:</label>
        {!! Form::text('short_desc', null, array('placeholder' => 'Short Desc Message','class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        <label for="title">Content Text:</label>
        {!! Form::text('content_text', null, array('placeholder' => 'Content Text Message','class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        <label for="title">Image Url:</label>
        {!! Form::text('image_url', null, array('placeholder' => 'image untuk notif','class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        <label for="title">Tag:</label>
        {!! Form::text('tag', null, array('placeholder' => 'promo/event/etc','class' => 'form-control')) !!}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" style="background-color: rgba(182, 0, 0, 1); color: rgba(255, 255, 255, 1);" class="btn hero-save btn-lg btn-block ">Kirim</button>
    </div>
{!! Form::close() !!}
@endsection