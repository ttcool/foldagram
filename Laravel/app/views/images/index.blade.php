@extends('images.image_master')


@section('content')
	{{Form::open(array('url' => '/image', 'files' => true))}}
	{{Form::text('title','',array('placeholder'=>'Please insert your title here'))}}
	{{Form::file('image')}}
	{{Form::submit('save!',array('name'=>'send'))}}
	{{Form::close()}}
@stop
