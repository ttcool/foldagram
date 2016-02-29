<?php

class AlbumsController extends BaseController{

	public function getList()
	{
		$albums = Album::with('Photos')->get();
		return View::make('gallerys.index')
				->with('albums',$albums);
	}
	public function getAlbum($id)
	{
		$album = Album::with('Photos')->find($id);
		$albums = Album::where('id','!=', $id)->get();
		return View::make('gallerys.album')
				->with('album',$album)
				->with('albums', $albums);
	}
	public function getForm()
	{
		return View::make('gallerys.createalbum');
	}
	public function postCreate()
	{
		$rules = array(

		'name' => 'required',
		'cover_image'=>'required|image'

		);
		
    	$validator = Validator::make(Input::all(), $rules);
    	if($validator->fails()){

    		return Redirect::route('create_album_form')
    			->withErrors($validator)
				->withInput();
    	}

		$file = Input::file('cover_image');
		$random_name = str_random(8);
		$destinationPath = 'albums/';
		$extension = $file->getClientOriginalExtension(); //We need to extension of the file because we'll rename the file.
		$filename=$random_name.'_cover.'.$extension;
		$uploadSuccess = Input::file('cover_image')->move($destinationPath, $filename);
		$album = Album::create(array(
            'name' => Input::get('name'),
            'description' => Input::get('description'),
            'cover_image' => $filename,
        ));

        return Redirect::route('show_album',array('id'=>$album->id));
	}

	public function getDelete($id)
	{
		$album = Album::find($id);

		$album->delete();

        return Redirect::route('albums_index');
      //  return Redirect::route('show_album',array('id'=>$album->id));
	}

}
