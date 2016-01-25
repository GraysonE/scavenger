<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\MetaTag;
use Scavenger\SocialMedia;
use Scavenger\Movie;
use Scavenger\Design;
use DB;
use Scavenger\User;
use Scavenger\Section;
use Scavenger\CallToAction;
use Scavenger\Video;
use Scavenger\Person;
use Scavenger\Image;
use Storage;
use Auth;
use Scavenger\Helpers\Helper;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($movie_id)
    {
        $movie = Movie::findOrFail($movie_id);
	    $meta = MetaTag::where('movie_id', "=", "$movie_id")->get();
	    
// 	    dd($meta);
	    $sections = Section::where('movie_id', "=", "$movie_id")->get();
	    
	    $currentUser = Auth::user();
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	
	    $editMovie = true;

	    $social = SocialMedia::where('movie_id', "=", "$movie_id")->get();
	    
	    $design = Design::where('movie_id', "=", "$movie_id")->firstOrFail();
	    
	    $poster = Image::where('site_location', '=', 'poster' )
 	        		->where('movie_id', '=', $movie_id )
 	        		->get()
 	        		->first();
 	        		
 	    $comingSoon = Image::where('site_location', '=', 'coming-soon' )
 	        		->where('movie_id', '=', $movie_id )
 	        		->get()
 	        		->first();
	    
	    $variables = compact('meta', 'movie', 'currentUser', 'sections', 'lastInitial', 'editMovie', 'social', 'design', 'poster', 'comingSoon');
	    
	    
	    
        return view('designs.index', $variables);
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
     * Store a design image resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function upload(Request $request, $movie_id)
	{
		$pureRequest = $request;
	    
		$image = $pureRequest->file('image');

    	if (isset($image)) {
		    if ($image->isValid()) {
		 	        
	 	        // Delete any images before inserting
	 	        Image::where('site_location', '=', $pureRequest->get('site_location') )
	 	        		->where('movie_id', '=', $movie_id )
	 					->delete();
	 	        
	 	        $file = $pureRequest->file('image');
	 	        
	 			$this->validate($pureRequest, [
	 		       'image' => 'required|image|mimes:jpeg,jpg,png|max:1500'
		        ]);
	 			
				
				$movie = Movie::findOrFail($movie_id);
 			
	 			$movieTitle = Helper::movieTitle($movie->title);
				
				$imgPath = public_path()."/movies/$movieTitle/img";
				
				$filename = $file->getClientOriginalName();
				
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
	 	        $image->section_id = NULL;
	 			$image->save();
		 	        
			}
	    }
	    
	    return redirect("admin/movies/$movie_id/edit/design");
	}



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id)
    {
	    
	    $section_id = $request->get('section_id');
	    $design_id = $request->get('id');
// 	    dd($design_id);
	    
	    if ($section_id) { // for ajax
		    
		    if (!$design_id) {
			    
			    $design = new Design();
			    $design->custom_background = $request->get('color');
			   
			    $design->section_id = $request->get('section_id');
			    $design->movie_id = $movie_id;
			    $design->save();
			    
		    } else {
			    
			    $design = Design::findOrFail($design_id);
			    $design->custom_background = $request->get('color');
			    $design->section_id = $request->get('section_id');
			    $design->movie_id = $movie_id;
			    $design->save();
			    
		    }
		    
		    
		    
	    } else { // for normal form input
		    $design = Design::findOrFail($design_id);
		    $design->movie_id = "$movie_id";
		    $design->section_id = NULL;
		    
		    $design->global_navigation_font = $request->get('global_navigation_font');
		    $design->header_font = $request->get('header_font');
		    $design->paragraph_font = $request->get('paragraph_font');
		    $design->footer_font = $request->get('footer_font');
		    
		    $design->custom_background = $request->get('custom_background');
		    $design->desktop_background_color = $request->get('desktop_background_color');
		    $design->mobile_background_color = $request->get('mobile_background_color');
			$design->desktop_buttons_color = $request->get('desktop_buttons_color');
			$design->mobile_buttons_color = $request->get('mobile_buttons_color');
			$design->desktop_header_color = $request->get('desktop_header_color');
			$design->mobile_header_color = $request->get('mobile_header_color');
			$design->desktop_paragraph_color = $request->get('desktop_paragraph_color');
			$design->mobile_paragraph_color = $request->get('mobile_paragraph_color');
	        
	        
// 	        dd($design);
	        
	        $design->save();
	        return redirect("admin/movies/$movie_id/edit/design");
	    }
	    
        
	    
	    
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
    public function destroy($movie_id, $id)
    {
        $image = Image::findOrFail($id);
        
        $path = $image->path;
        $pathExploded = explode('/movies/', $path);
        
        $otherImages = Image::where('path', '=', $image->path)->get();
        
        $otherImageCount = count($otherImages);
        
        if($otherImageCount < 2) {
	        Storage::disk('movies')->delete($pathExploded[1]);
        }
        
        $image->delete();
        
        return redirect("/admin/movies/$movie_id/edit/design");
    }
}
