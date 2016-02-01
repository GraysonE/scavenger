<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\User;
use Scavenger\GlobalCta;
use Scavenger\GlobalSocialMedia;
use Scavenger\Image;
use Auth;
use Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $users = User::all();
	    
// 	    dd($users);
	    $editMovie = false;
	    
	    $currentUser = Auth::user();
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	        
	    $globalCTAs = GlobalCta::orderBy('sort_order', 'asc')->get();
	    $globalSocialMedia = GlobalSocialMedia::all();
	    
	    $globalSocialMediaImage = Image::where('movie_id', '=', NULL)
		    ->where('section_id', '=', NULL)
		    ->where('site_location', '=', 'global_social')
		    ->get()->first();
	    
// 	    dd($globalSocialMediaImage);
	       
	    $bladeVariables = compact('currentUser', 'lastInitial', 'editMovie', 'users', 'globalCTAs', 'globalSocialMedia', 'globalSocialMediaImage');
	    
        return view('settings.index', $bladeVariables); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($column)
    {
	    
	    $globalCTA = new GlobalCta();
	        $globalCTA->text = 'New example';
	        $globalCTA->url = 'http://newexample.com';
	        $globalCTA->target = '_blank';
	        $globalCTA->column = $column;
	        $globalCTA->sort_order = 0;
        $globalCTA->save();
        
        return redirect('/settings');
    }

	public function createSocial()
    {
	    
	    $globalCTA = new GlobalSocialMedia();
	        $globalCTA->tag = 'custom';
	        $globalCTA->url = 'http://newexample.com';
        $globalCTA->save();
        
        return redirect('/settings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $pureRequest = $request;
    		    
	    if ($pureRequest->hasFile('image') && $pureRequest->file('image')->isValid()) {
	 	        
 	        // Delete any images before inserting
 	        Image::where('site_location', '=', 'global_social' )
 	        		->take(1)
 					->delete();
 	        
 	        $file = $pureRequest->file('image');
 			
 			$this->validate($pureRequest, [
 		       'image' => 'required|image|mimes:jpeg,jpg,png|max:1500'
	        ]);
 			
			
			$imgPath = public_path()."/uploads/img/";
			
			$filename = $file->getClientOriginalName();
			
 	        $file->move($imgPath, $filename);
 	        
 	        $image = new Image();
 	        
 	        $image->title = $pureRequest->get('title');
 	        $image->path = "/uploads/img/$filename";
 	        
 	        $image->site_location = 'global_social';
 	        $image->movie_id = NULL;
 	        $image->section_id = NULL;
 			$image->save();
	 	        
		}
	    
	    
	    $allFields = $request->all();
	    
	    if (isset($allFields['master'])) {
			
			// Footer Global CTAs REPEATER
			$keys = array_keys($allFields);
	
			$k=0;
			$globalCTAs = array();
			foreach ($keys as $key) {
				
				if (preg_match('/^url/', $key)) {
					$keyExploded = explode('-', $key);
					$globalCTAs[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}
			
			foreach($globalCTAs as $globalCTA) {
				
		        $globalCTAUpdate = GlobalCta::findOrFail($globalCTA);
		        
		        $this->validate($request, [
		            "text-$globalCTA" => 'required|max:255|min:2',
		            "url-$globalCTA" => 'required|url|min:6',
		            "target-$globalCTA" => 'required|min:1'
		        ]);
		        
		        $globalCTAUpdate->text = $allFields["text-$globalCTA"];
		        $globalCTAUpdate->url = $allFields["url-$globalCTA"];
		        $globalCTAUpdate->target = $allFields["target-$globalCTA"];
		        
		        $globalCTAUpdate->save();  
			}
			
			
			// Global Social Media REPEATER
	
			$k=0;
			$globalSocialMedia = array();
			foreach ($keys as $key) {
				
				if (preg_match('/^socialURL/', $key)) {
					$keyExploded = explode('-', $key);
					$globalSocialMedia[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}
			
			foreach($globalSocialMedia as $social) {
				
		        $globalSocialMediaUpdate = GlobalSocialMedia::findOrFail($social);
		        
		        $this->validate($request, [
		            "socialURL-$social" => 'required|url|min:6'
		        ]);
		        
		        $globalSocialMediaUpdate->url = $allFields["socialURL-$social"];
		        
		        $globalSocialMediaUpdate->save();  
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
				
		        $ogUpdate = GlobalSocialMedia::findOrFail($og_id);
		        
		        if ($ogUpdate->tag == 'og_title') {
			        $ogUpdate->url = $allFields["og_title"];
		        } elseif ($ogUpdate->tag == 'og_type') {
			        $ogUpdate->url = $allFields["og_type"];
		        } elseif ($ogUpdate->tag == 'og_image') {
			        $ogUpdate->url = $allFields["og_image"];
		        } elseif ($ogUpdate->tag == 'og_url') {
			        $ogUpdate->url = $allFields["og_url"];
		        }
		        
		        $ogUpdate->save();  
			}
						
		    
	    } else {
		    
		    if ($request->get('tag')) {
		    
			    $globalSocialMedia = $request->all();
				
	        	GlobalSocialMedia::create($globalSocialMedia);
	        	
		    } else {
			    
			    $globalCTA = $request->all();
			    
				$this->validate($request, [
		            "target" => 'required|min:1',
		            "title" => 'required|min:2',
		            "url" => 'required|url|min:4'
		        ]);
	        
	        	GlobalCta::create($globalCTA);
	        	
		    }
		    
	    }
	    
	    if (isset($allFields['pre-edit'])) {
		    
		    $globalSocialMedia['tag'] = 'facebook';
		    $globalSocialMedia['url'] = $allFields['facebook'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'twitter';
		    $globalSocialMedia['url'] = $allFields['twitter'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'tumblr';
		    $globalSocialMedia['url'] = $allFields['tumblr'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'instagram';
		    $globalSocialMedia['url'] = $allFields['instagram'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'og_title';
		    $globalSocialMedia['url'] = $allFields['og_title'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'og_type';
		    $globalSocialMedia['url'] = $allFields['og_type'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'og_image';
		    $globalSocialMedia['url'] = $allFields['og_image'];
	        GlobalSocialMedia::create($globalSocialMedia);
	        
	        $globalSocialMedia['tag'] = 'og_url';
		    $globalSocialMedia['url'] = $allFields['og_url'];
	        GlobalSocialMedia::create($globalSocialMedia);
		    
	    }
	    
// 	    dd($allFields);
	    
	    
        
        
        return redirect('/settings');
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
    public function destroy($id)
    {
	    $type = $_GET['type'];
	    
	    if ($type == 'social') {
		    $socialMedia = GlobalSocialMedia::where('id', $id);
			$socialMedia->take(1)->delete();
	    } elseif ($type == 'cta') {
		    $cta = GlobalCta::where('id', $id);
			$cta->take(1)->delete();
	    }
        
        
        return redirect("settings");
    }
    
    
    public function duplicate($id)
    {
	    
	    $type = $_GET['type'];
	    
	    if ($type == 'social') {
		    
		    $socialMedia = GlobalSocialMedia::findOrFail($id)->toArray();
		    $socialMedia['tag'] = 'custom';
		    GlobalSocialMedia::create($socialMedia);
		    
	    } elseif ($type == 'cta') {
		    
		    $cta = GlobalCta::findOrFail($id)->toArray();
		    GlobalCta::create($cta);
			
	    }
	    
	    
	    return redirect("settings");

    }
    
    public function destroyImage($image_id)
    {
        
        $image = Image::findOrFail($image_id);
        
        $path = $image->path;
        $pathExploded = explode('/img/', $path);
        
        $otherImages = Image::where('path', '=', $image->path)->get();
        $otherImageCount = count($otherImages);
        
        if($otherImageCount < 2) {
	        Storage::disk('local')->delete($pathExploded[1]);
        }
        
        $image->delete();
        
        if (isset($_GET['movie_id'])) {
	        $movie_id = $_GET['movie_id'];
	        return redirect("admin/movies/$movie_id/edit/seo-social");
        } else {
	        return redirect("settings");
        }
        
    }
}
