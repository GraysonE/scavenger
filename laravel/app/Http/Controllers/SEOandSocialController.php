<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\MetaTag;
use Scavenger\SocialMedia;
use Scavenger\Movie;
use DB;
use Scavenger\User;
use Scavenger\Section;
use Scavenger\CallToAction;
use Scavenger\Video;
use Scavenger\Person;
use Scavenger\Image;
use Auth;
use Scavenger\Helpers\Helper;

class SEOandSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($movie_id)
    {
	    
	    $movie = Movie::findOrFail($movie_id);
	    $meta = MetaTag::where('movie_id', "=", "$movie_id")->firstOrFail();
	    
	    $sections = Section::where('movie_id', "=", "$movie_id")->get();
	    
	    $currentUser = Auth::user();
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	
	    $editMovie = true;

	    $socials = SocialMedia::where('movie_id', "=", "$movie_id")->get();

	    $socialMediaImage = Image::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', NULL)
		->where('site_location', '=', 'social')
		->get()->first();
	    
	    $variables = compact('meta', 'movie', 'currentUser', 'sections', 'lastInitial', 'editMovie', 'socials', 'socialMediaImage');
	    
	    
	    
        return view('seoandsocial.index', $variables);
	    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($movie_id)
    {
        $data = new SocialMedia();
        	$data->movie_id = $movie_id;
	        $data->tag = 'custom';
	        $data->url = 'http://newexample.com';
        $data->save();
        
        return redirect("admin/movies/$movie_id/edit/seo-social");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id)
    {
	    
	    $pureRequest = $request;

	    if ($pureRequest->hasFile('image') && $pureRequest->file('image')->isValid()) {
	 	        
 	        // Delete any images before inserting
 	        Image::where('site_location', '=', 'social' )
 	        		->where('movie_id', '=', $movie_id )
 	        		->take(1)
 					->delete();
 	        
 	        $file = $pureRequest->file('image');
 			
 			$this->validate($pureRequest, [
 		       'image' => 'required|image|mimes:jpeg,jpg,png|max:1500'
	        ]);
 			
			
			$movie = Movie::findOrFail($movie_id);
 			
 			$movieTitle = Helper::movieTitle($movie->title);
			
			$imgPath = public_path()."/movies/$movieTitle/img";
			
			$filename = $file->getClientOriginalName();
			
 	        $file->move($imgPath, $filename);
 	        
 	        $image = new Image();
 	        
 	        $image->title = $pureRequest->get('title');
			$image->path = "/movies/$movieTitle/img/$filename";
 	        
 	        $image->site_location = 'social';
 	        $image->movie_id = $movie_id;
 	        $image->section_id = NULL;
 			$image->save();
	 	        
		}

	    
	    
	    
	    $movie = Movie::findOrFail($movie_id);
	    
        $allFields = $request->all();
        
        $count = count($allFields);
        
//         dd($allFields);
        
        // Meta Tags
        
        $metaTag = MetaTag::where('movie_id', "=", "$movie_id")->firstOrFail();
        
        $metaTag->canonical = $allFields['canonical'];
        $metaTag->category = $allFields['category'];
        $metaTag->copyright = $allFields['copyright'];
        $metaTag->description = $allFields['description'];
		$metaTag->keywords = $allFields['keywords'];
		$metaTag->language = $allFields['language'];
		$metaTag->owner = $allFields['owner'];
		$metaTag->publisher = $allFields['publisher'];
		$metaTag->rating = $allFields['rating'];
		$metaTag->url = $allFields['url'];
		$metaTag->movie_id = $movie->id;
		
		$metaTag->save();



		
		if (!isset($allFields["tag"])) {
			
			// Social Media REPEATER
			$keys = array_keys($allFields);
	
			$k=0;
			$socialMedia_ids = array();
			foreach ($keys as $key) {
				
				if (preg_match('/^socialURL/', $key)) {
					$keyExploded = explode('-', $key);
					$socialMedia_ids[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}
			
			foreach($socialMedia_ids as $socialMedia_id) {
				
		        $socialMediaUpdate = SocialMedia::findOrFail($socialMedia_id);
		        
		        $this->validate($request, [
		            "socialURL-$socialMedia_id" => 'required|url|min:4'
		        ]);
		        
		        $socialMediaUpdate->url = $allFields["socialURL-$socialMedia_id"];
		        $socialMediaUpdate->movie_id = $movie_id;
		        
		        $socialMediaUpdate->save();  
			}
			
			// Open Graph Meta Data
			$k=0;
			$og_ids = array();
			foreach ($keys as $key) {
				
				if (preg_match('/^id/', $key)) {
					$keyExploded = explode('-', $key);
					$og_ids[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}	
			
			foreach($og_ids as $og_id) {
				
		        $ogUpdate = SocialMedia::findOrFail($og_id);
		        
		        if ($ogUpdate->tag == 'og_title') {
			        $ogUpdate->url = $allFields["og_title"];
		        } elseif ($ogUpdate->tag == 'og_type') {
			        $ogUpdate->url = $allFields["og_type"];
		        } elseif ($ogUpdate->tag == 'og_image') {
			        $ogUpdate->url = $allFields["og_image"];
		        } elseif ($ogUpdate->tag == 'og_url') {
			        $ogUpdate->url = $allFields["og_url"];
		        }
		        
		        $ogUpdate->movie_id = $movie_id;
		        $ogUpdate->save();  
			}			
			
		} else {
			
			$socialMedia = new SocialMedia();
			
			$socialMedia->tag = $allFields["tag"];
	        $socialMedia->url = $allFields["url"];
	        $socialMedia->movie_id = $movie->id;
	        $socialMedia->save(); 
		}
		
              
        
        
        // analytics
        $movie->google_analytics_id = $allFields['google_analytics_id'];
        $movie->mobile_google_analytics_id = $allFields['mobile_google_analytics_id'];
        
        $movie->save();
        
        
        return redirect("admin/movies/$movie_id/edit/seo-social");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($movie_id)
    {
        
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
    public function destroy($movie_id, $socialMedia_id)
    {
        $socialMedia = SocialMedia::where('id', $socialMedia_id);
        
        $socialMedia->take(1)->delete();
        
        return redirect("admin/movies/$movie_id/edit/seo-social");
    }
    
    public function duplicate(Request $request, $movie_id, $socialMedia_id)
    {
        $socialMedia = SocialMedia::findOrFail($socialMedia_id)->toArray();
        
        $socialMedia['movie_id'] = $movie_id;
	    
//   	    dd($socialMedia);
	    
	    SocialMedia::create($socialMedia);	    
	    
	    
	    return redirect("admin/movies/$movie_id/edit/seo-social");

    }
}
