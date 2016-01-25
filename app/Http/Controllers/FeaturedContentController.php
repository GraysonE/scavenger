<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\FeaturedContent;
use DB;
use Scavenger\Image;
use Scavenger\Movie;
use Storage;
use Scavenger\Helpers\Helper;

class FeaturedContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($movie_id, $section_id)
    {
        $data = new FeaturedContent();
        	$data->type = $_GET['type'];
        	$data->movie_id = $movie_id;
        	$data->section_id = $section_id;
	        $data->name = 'Example Name';
	        
	        if ($data->type == 'award') {
		        $data->url = '';
	        } elseif ($data->type == 'review') {
		        $data->url = 'John Doe';
	        } elseif (($data->type == 'featured') || ($data->type == 'partner') || ($data->type == 'rating')) {
		        $data->url = 'http://example.com';
	        }
	        
	        

        $data->save();
        
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
		
		
		// MOVIE LEGAL LINE
		
		if (isset($request['legal_line'])) {
			$data = Movie::findOrFail($movie_id);
			$data->legal_line = $request['legal_line'];
			$data->save();
		}
		
		
		
// 		dd($request);
	    $keys = array_keys($request);

		$k=0;
		$ids = array();
		foreach ($keys as $key) {
			
			if (preg_match('/^name/', $key)) {
				$keyExploded = explode('-', $key);
				$ids[$k] = $keyExploded[1];
				
				$k++;
				
			}
			
		}
		
		
		foreach($ids as $id) {
			
			
			// Featured Content Processing
			
	        $data = FeaturedContent::findOrFail($id);
	        
	        if (($data->type == 'featured') || ($data->type == 'partner') || ($data->type == 'rating')) {
		        
		        $this->validate($pureRequest, [
	 		       "name-$id" => 'required|min:1|max:255',
	 			   "url-$id" => 'url|max:255',
		        ]);
		        
		        $data->url = $pureRequest["url-$id"];
		        
		    } else {
			    $this->validate($pureRequest, [
	 		       "name-$id" => 'required|min:1|max:255',
	 			   "url-$id" => 'max:255',
		        ]);
		    }
	        
	        

	        $data->movie_id = $movie_id;
	        $data->section_id = $section_id;
	        $data->name = $pureRequest["name-$id"];
	        $data->cta_text = $pureRequest["cta_text-$id"];
	        $data->body = $pureRequest["body-$id"];
	        $data->save();  
	        
	        
	        // IMAGE PROCESSING
			if ($pureRequest->file("image-$id")) {
			
				if ($pureRequest->file("image-$id")->isValid()) {
		 	         	        
		 	        $file = $pureRequest->file("image-$id");
				
					$movie = Movie::findOrFail($movie_id);
 			
		 			$movieTitle = Helper::movieTitle($movie->title);
					
					$imgPath = public_path()."/movies/$movieTitle/img";
					
					$filename = $file->getClientOriginalName();
					
		 	        $file->move($imgPath, $filename);
		 	        
		 	        $image = Image::where('movie_id', $movie_id)
		 	        		->where('site_location', '=', $id)
		 					->get()
		 					->first();
// 		 	        dd($image);

		 	        if(!$image){
			 	        $image = new Image();
		 	        }
		 	        
// 		 	        dd($image);
		 	        
		 	        $image->title = $pureRequest->get("title-$id");
		 	        $image->path = "/movies/$movieTitle/img/$filename";
		 	        $image->site_location = $id;
		 	        $image->movie_id = $movie_id;
		 	        $image->section_id = $section_id;
		 	        
		 			$image->save();
			 	}
				
			}
			
	        
	        
		}
		
		
		
		
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }



	/**
     * Upload credit image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function creditImage(Request $request, $movie_id, $section_id)
    {
	    $pureRequest = $request;
        if ($pureRequest->file('image')->isValid()) {
	 	        
 	        // Delete any images before inserting
 	        Image::where('movie_id', $movie_id)
 	        		->where('section_id', $section_id)
 	        		->where('site_location', '=', 'credit' )
 	        		->take(1)
 					->delete();
 	        
 	        $file = $pureRequest->file('image');
 			
 			$this->validate($pureRequest, [
 		       'image' => 'required|image|mimes:jpeg,jpg,png|max:1500'
	        ]);
 			
			$movie = Movie::findOrFail($movie_id);
 			
		 	$movieTitle = Helper::movieTitle($movie->title);
					
			$imgPath = public_path()."/movies/$movieTitle/img";
			
			if (file_exists($imgPath.$filename)) {
		        $filenameExploded = explode('.', $filename);
		        $filename = $filenameExploded[0]."-1".$filenameExploded[1];
		    }
			
 	        $file->move($imgPath, $filename);
 	        
 	        $image = new Image();
 	        
 	        $image->title = $pureRequest->get('title');
 	        $image->path = "/movies/$movieTitle/img/$filename";
 	        $image->site_location = $pureRequest->get('site_location');
 	        $image->movie_id = $movie_id;
 	        $image->section_id = $section_id;
 			$image->save();
 	        
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
    public function destroy($movie_id, $section_id, $id)
    {
	    
        $featuredContent = FeaturedContent::findOrFail($id);
        
        $image = Image::where('site_location', '=', $id)->get()->first();
        
        $delete = $featuredContent->delete();
        
        if ($image) {
	        $path = $image->path;
	        $pathExploded = explode('/movies/', $path);
	        $otherImages = Image::where('path', '=', $image->path)->get();
	        $otherImageCount = count($otherImages);
	        
	        if($otherImageCount < 2) {
		        Storage::disk('movies')->delete($pathExploded[1]);
	        }
	        $image->delete();
        }
        
        
        
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }
    
    
   
    
}
