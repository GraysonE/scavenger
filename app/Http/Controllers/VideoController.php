<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\Video;
use Validator;

class VideoController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id, $section_id)
    {
	    // New video
		if ($request->get('new')) {
			
			$request = $request->all();
        
			$validator = Validator::make($request, [
	            'title' => 'required|min:1|max:255',
	            'youtube_id' => 'required|min:1|max:12',
	        ]);
	
	        if ($validator->fails()) {
	            return redirect("admin/movies/$movie_id/edit/sections/$section_id")
	                        ->withErrors($validator)
	                        ->withInput();
	        }
        
        
		    $request['section_id'] = $section_id;
		    $request['movie_id'] = $movie_id;
		    
		    Video::create($request);
		    
		} else {
			
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
				
		        $update = Video::findOrFail($id);
		        
		        $this->validate($pureRequest, [
	 		       "title-$id" => 'required|min:1|max:255',
	 			   "youtube_id-$id" => 'required|min:1|max:255',
		        ]);
	
		        $update->title = $request["title-$id"];
		        $update->youtube_id = $request["youtube_id-$id"];
		        $update->movie_id = $movie_id;
		        $update->section_id = $section_id;
		        $update->save();  
			}
			
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
    public function destroy($movieID, $section_id, $video_id)
    {
        $video = Video::where('id', $video_id);
        
        $video->take(1)->delete();
        
        return redirect("admin/movies/$movieID/edit/sections/$section_id");
    }
    
     public function duplicate(Request $request, $movie_id, $section_id, $video_id)
    {
        $video = Video::findOrFail($video_id)->toArray();
	    
	    Video::create($video);	    
	    
	    
	    return redirect("admin/movies/$movie_id/edit/sections/$section_id");

    }
    
    
}
