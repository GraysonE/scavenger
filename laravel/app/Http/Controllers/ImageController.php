<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Image;
use Scavenger\Http\Controllers\Controller;
use Scavenger\Http\Requests\ImageRequest;
use Input;
use Validator;
use URL;
use Storage;
use Scavenger\Movie;
use Scavenger\Helpers\Helper;

class ImageController extends Controller
{
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($movie_id, $section_id)
    {
        
        
        return view('sections.images.edit.blade.php');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $movie_id)
    {
	    $pureRequest = $request;
	    
	    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : false;
	    $site_location = ($_POST['site_location'] != '') ? $_POST['site_location'] : NULL;
	    
	    
		if ($pureRequest->file('image')->isValid()) {
	 	        
 	        if ($section_id) {
	 	        // Delete any images before inserting
	 	        Image::where('movie_id', $movie_id)
	 	        		->where('section_id', $section_id)
	 	        		->where('site_location', '=', $site_location )
	 	        		->take(1)
	 					->delete();
 	        } else {
	 	        // Delete any images before inserting
	 	        Image::where('movie_id', $movie_id)
	 	        		->where('site_location', '=', $site_location )
	 	        		->take(1)
	 					->delete();
 	        }
	 	        
 	        
 	        $file = $pureRequest->file('image');
 			
 			$this->validate($pureRequest, [
 		       'image' => 'required|image|mimes:jpeg,jpg,png|max:1500'
	        ]);
 			
 			$movie = Movie::findOrFail($movie_id);
 			
 			$movieTitle = Helper::movieTitle($movie->title);
			
			$imgPath = public_path()."/movies/$movieTitle/img/";
			
			$filename = $file->getClientOriginalName();
			
			if (file_exists($imgPath.$filename)) {
		        $filenameExploded = explode('.', $filename);
		        $filename = $filenameExploded[0]."-1".$filenameExploded[1];
		    }
			
 	        $file->move($imgPath, $filename);
 	        
 	        $image = new Image();
 	        
 	        $image->title = $pureRequest->get('title');
 	        $image->path = "/movies/$movieTitle/img/$filename";
 	        
 	        $image->site_location = $site_location;
 	        $image->movie_id = $movie_id;
 	        $image->section_id = $section_id;
 			$image->save();
 	        
		}
		
		
		if ($section_id) {
			return redirect("admin/movies/$movie_id/edit/sections/$section_id");
		}
		
    }
    
    
    
    
    public function createMultiple(Request $request, $movie_id) {
	    
	    $pureRequest = $request;

	    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : false;
	    $site_location = ($_POST['site_location'] != '') ? $_POST['site_location'] : NULL;
	    $files = ($pureRequest->file('image')) ? $pureRequest->file('image') : NULL;
	    
	    foreach ($files as $file) {
		    
		    if ($file->isValid()) {
	 			
	 			$movie = Movie::findOrFail($movie_id);
	 			
	 			$movieTitle = Helper::movieTitle($movie->title);
				
				$imgPath = public_path()."/movies/$movieTitle/img/";
				
				$filename = $file->getClientOriginalName();
				
				if (file_exists($imgPath.$filename)) {
			        $filenameExploded = explode('.', $filename);
			        $filename = $filenameExploded[0]."-1".$filenameExploded[1];
			    }
				
	 	        $file->move($imgPath, $filename);
	 	        
	 	        $image = new Image();
	 	        
	 	        $image->title = $pureRequest->get('title');
	 	        $image->path = "/movies/$movieTitle/img/$filename";
	 	        
	 	        $image->site_location = $site_location;
	 	        $image->movie_id = $movie_id;
	 	        $image->section_id = $section_id;
	 			$image->save();
	 	        
			}
	    }
	    
	    return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }
    
    
    
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id, $section_id)
    {
	    $pureRequest = $request;
	    $request = $request->all();		
			
		$keys = array_keys($request);
		
		$k=0;
		$ids = array();
		foreach ($keys as $key) {
			
			if (preg_match('/^title/', $key)) {
				$keyExploded = explode('-', $key);
				$ids[$k] = $keyExploded[1];
				
				$k++;
				
			}
			
		}
		
		foreach($ids as $id) {
			
	        $update = Image::findOrFail($id);
	        $update->title = $request["title-$id"];
	        $update->movie_id = $movie_id;
	        $update->section_id = $section_id;
	        $update->save();  
		}
		
		return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($movie_id, $section_id, $image_id)
    {
        
        $image = Image::findOrFail($image_id);
        
        $path = $image->path;
        $pathExploded = explode('/movies/', $path);
        
        $otherImages = Image::where('path', '=', $image->path)->get();
        
        $otherImageCount = count($otherImages);
        
        if($otherImageCount < 2) {
	        Storage::disk('movies')->delete($pathExploded[1]);
        }
        
        $image->delete();
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }
}
