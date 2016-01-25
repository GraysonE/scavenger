<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\Person;
use Scavenger\Movie;
use Scavenger\Image;
use Scavenger\Helpers\Helper;

class AboutController extends Controller
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
        $data = new Person();
        	$data->movie_id = $movie_id;
        	$data->section_id = $section_id;
	        $data->first_name = 'First Name';
	        $data->last_name = 'Last Name';
	        $data->occupation = $_GET['occ'];
	        $data->biography = '';
	        $data->display = 1;
	        $data->role = 'Director/Actor';
	        $data->sort_order = 0;
        $data->save();
        
        $tab = $_GET['occ'];
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id#$tab");
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
        
       
        
        
        // MOVIE SYNOPSIS
        $data = Movie::findOrFail($movie_id);
        $data->about = $request['about'];
        $data->save();
        
        // PEOPLE
        $keys = array_keys($request);
			
		$k=0;
		$ids = array();
		foreach ($keys as $key) {
			
			if (preg_match('/^role/', $key)) {
				$keyExploded = explode('-', $key);
				$ids[$k] = $keyExploded[1];
				
				$k++;
				
			}
			
		}
		
		foreach($ids as $id) {
			
	        $update = Person::findOrFail($id);
	        
	        $this->validate($pureRequest, [
 		       "first_name-$id" => 'required|min:1|max:255',
 			   "last_name-$id" => 'required|min:1|max:255',
 			   "role-$id" => 'required|min:1|max:255',
	        ]);

	        $update->first_name = $request["first_name-$id"];
	        $update->last_name = $request["last_name-$id"];
	        $update->role = $request["role-$id"];
	        
	        if (isset($request["display-$id"])) {
		        $update->display = $request["display-$id"];
	        } else {
		        $update->display = 0;
	        }
	        
	        
	        $update->occupation = $request["occupation-$id"];
	        $update->biography = $request["biography-$id"];
	        $update->movie_id = $movie_id;
	        $update->section_id = $section_id;
	        $update->save();  
	        
	        
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

		 	        if(!$image){
			 	        $image = new Image();
		 	        }
		 	        
		 	        $image->path = "/movies/$movieTitle/img/$filename";
		 	        $image->site_location = $id;
		 	        $image->movie_id = $movie_id;
		 	        $image->section_id = $section_id;
		 	        
		 			$image->save();
			 	}
				
			}
	        
	        
	        
	        
		}
        
        
        $tab = isset($request['tab']) ? $request['tab'] : '';
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id#$tab");
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
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $movieID, $section_id, $person_id)
    {
        $person = Person::where('id', $person_id);
        $person->take(1)->delete();
        
        $tab = $request->get('tab');
        
        return redirect("admin/movies/$movieID/edit/sections/$section_id");
    }

}
