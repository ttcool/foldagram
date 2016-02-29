@extends('images.image_master')

@section('content')
	
	@if(count($images))
		<ul>
		@foreach($images as $each)
			<li>
				<a href="{{URL::to('snatch/'.$each->id)}}">{{Html::image(Config::get('image.thumb_folder').'/'.$each->image)}}</a>
			</li>
		@endforeach
		</ul> 
		
		<p>{{$images->links()}}</p>
	@else
		<p>No images uploaded yet, {{Html::link('/','care to upload one?')}}</p>
	@endif
@stop
