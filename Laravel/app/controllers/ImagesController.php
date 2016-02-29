<?php

class ImagesController extends BaseController{

	public function getForm($id)
	{
		$album = Album::find($id);

		return View::make('gallerys.addimage')
				->with('album',$album);
	}

	public function postAdd()
	{
		$rules = array(

		'album_id' => 'required|numeric|exists:albums,id',
		'image'=>'required|image'

		);
		
    	$validator = Validator::make(Input::all(), $rules);
    	if($validator->fails()){

    		return Redirect::route('add_image',array('id' => Input::get('album_id')))
    			->withErrors($validator)
				->withInput();
    	}

		$file = Input::file('image');
		$random_name = str_random(8);
		$destinationPath = 'albums/';
		$extension = $file->getClientOriginalExtension(); //We need to extension of the file because we'll rename the file.
		$filename=$random_name.'_album_image.'.$extension;
		$uploadSuccess = Input::file('image')->move($destinationPath, $filename);
		Images::create(array(
            'description' => Input::get('description'),
            'image' => $filename,
            'album_id'=> Input::get('album_id')
        ));

        return Redirect::route('show_album', array('id'=>Input::get('album_id')));
	}

	public function getDelete($id)
	{
		$image = Images::find($id);
              
                $album_id = $image->album_id;

		$image->delete();

        return Redirect::route('show_album', array('id'=>$album_id));
	}

	public function postMove()
	{
		$rules = array(

		'new_album' => 'required|numeric|exists:albums,id',
		'photo'=>'required|numeric|exists:images,id'

		);

    	$validator = Validator::make(Input::all(), $rules);
    	if($validator->fails()){

    		return Redirect::route('albums_index');
    	}
    	$image = Images::find(Input::get('photo'));
    	$image->album_id = Input::get('new_album');
		$image->save();
		return Redirect::route('show_album', array('id'=>Input::get('new_album')));
	}
}
