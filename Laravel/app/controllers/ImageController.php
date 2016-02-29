<?php

class ImageController extends BaseController {

	public $restful = true;

	
	public function getIndex()
	{
		//Let's load the form view
		return View::make('images.index');
	}

	public function postIndex()
	{	
		
		//Let's validate the form first with the rules which are set at the model
		$validation = Validator::make(Input::all(), Photo::$upload_rules);

		//If the validation fails, we redirect the user to the index page, with the error messages 
		if($validation->fails()) {
			return Redirect::to('/image')
				->withInput()
				->withErrors($validation);
		} else {
			//If the validation passes, we upload the image to the database and process it
			$image = Input::file('image');

			//This is the original uploaded client name of the image
			$filename = $image->getClientOriginalName();
			//Because Syfony API does not provide file name without extension, we will be using raw PHP here
			$filename = pathinfo($filename, PATHINFO_FILENAME);

			//We should salt and make an url-friendly version of the file name
			//(In ideal application, you should check the file name to be unique)
			$fullname = Str::slug(Str::random(8).$filename).'.'.$image->getClientOriginalExtension();

			//We upload the image first to the upload folder, then get make a thumbnail from the uploaded image
			$upload = $image->move(Config::get('image.upload_folder'),$fullname);
			
			//Our model that we've created is named Images, this library has an alias named Image, don't mix them two!
			//These parameters are related to the image processing class that we've included, not really related to Laravel
			Image::make(Config::get('image.upload_folder').'/'.$fullname)
				->resize(Config::get('image.thumb_width'), null)
				->save(Config::get('image.thumb_folder').'/'.$fullname);


			//If the file is now uploaded, we show an error message to the user, else we add a new column to the database and show the success message
			if($upload) {
				//image is now uploaded, we first need to add column to the database
				$insert_id = DB::table('photos')->insertGetId(
				    array(
				    	'title' => Input::get('title'),
				    	'image' => $fullname
				    )
				);

				//Now we redirect to the image's permalink
				return Redirect::to(URL::to('snatch/'.$insert_id))
					->with('success','Your image is uploaded successfully!');
			} else {
				//image cannot be uploaded
				return Redirect::to('/image')
					->withInput()
					->with('error','Sorry, the image could not be uploaded, please try again later');
			}
		}
	}

	public function getSnatch($id) {
		//Let's try to find the image from database first
		$image = Photo::find($id);
		//If found, we load the view and pass the image info as parameter, else we redirect to main page with error message
		if($image) {
			return View::make('images.permalink')->with('image',$image);
		} else {
			return Redirect::to('/image')->with('error','Image not found');
		}
	}

	public function getAll(){
		//Let's first take all images with a pagination feature
		$all_images = DB::table('images')->orderBy('id','desc')->paginate(6);

		//Then let's load the view with found data and pass the variable to the view
		return View::make('images.all_images')
			->with('images',$all_images);
	}

	public function getDelete($id) {
		//Let's first find the image
		$image = Images::find($id);

		//If there's an image, we will continue to the deleting process
		if($image) {
			//First, let's delete the images from FTP
			File::delete(Config::get('image.upload_folder').'/'.$image->image);
			File::delete(Config::get('image.thumb_folder').'/'.$image->image);

			//Now let's delete the value from database
			$image->delete();

			//Let's return to the main page with a success message
			return Redirect::to('/image')
				->with('success','Image deleted successfully');

		} else {
			//Image not found, so we will redirect to the index page with an error message flash data.
			return Redirect::to('/image')
				->with('error','No image with given ID found');
		}
	}

}
