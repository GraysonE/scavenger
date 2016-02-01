<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\Http\Requests\SectionRequest;
use Scavenger\User;
use Scavenger\Movie;
use Scavenger\Section;
use Scavenger\CallToAction;
use Scavenger\Video;
use Scavenger\Person;
use Scavenger\Image;
use Scavenger\Design;
use Scavenger\Ticket;
use Scavenger\FeaturedContent;
use Scavenger\ReleaseDate;
use Auth;
use Validator;
use DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($movie_id, $section_id)
    {
       
		$sections = Section::get();
		  			
		$editMovie = true;
			  			

	    return view('sections.index', compact('sections', 'editMovie')); 
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id, $section_id)
    {
	    
		$sections = $request->all();
		
		
		$validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect("admin/movies/$movie_id/edit")
                        ->withErrors($validator)
                        ->withInput();
        }
        
		$sections['display'] = 1;
		$sections['published'] = 1;
		
		$section = Section::findOrFail($section_id);
		$view = $section->view;
		$sections['view'] = $view;
		
		Section::create($sections);
	    
	    return redirect("admin/movies/$movie_id/edit");
    }
    
    public function newSection(Request $request, $movie_id)
    {
	    
		$section = $request->all();
//  		dd($section);
		$validator = Validator::make($section, [
            'title' => 'required|min:1|max:255',
            'content_block' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return redirect("admin/movies/$movie_id/edit")
                        ->withErrors($validator)
                        ->withInput();
        }
		
			
		if (isset($section['custom_type'])) {
			$section['display'] = $section['custom_type'];
		} else {
			$section['display'] = '1';
		}
		
		$section['movie_id'] = $movie_id;
		$section['qa'] = '0';
		$section['live'] = '0';
		$section['sort_order'] = '0';
		$section['title'] = $section['title'];
		$section['view'] = $section['content_block'];
				
		Section::create($section);
			
		
	    
	    return redirect("admin/movies/$movie_id/edit");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($movie_id, $section_id)
    {
	    $currentUser = Auth::user();	    
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	
	    $movie = Movie::findOrFail($movie_id);
	    $sections = Section::where('movie_id', "=", "$movie_id")->orderBy('sort_order', 'asc')->get();
	    	$currentSection = Section::findOrFail($section_id);
	    	
	    $ctas = CallToAction::where('movie_id', "=", "$movie_id")
	    ->where('section_id', "=", "$section_id")
	    ->orderBy('sort_order', 'asc')
	    ->get();
	    
        $videos = Video::where('movie_id', "=", "$movie_id")
        ->where('section_id', "=", "$section_id")
        ->orderBy('sort_order', 'asc')
        ->get();
        
        $people = Person::where('movie_id', "=", "$movie_id")
        ->where('section_id', "=", "$section_id")
        ->orderBy('sort_order', 'asc')
        ->get();
        
        $tickets = Ticket::where('movie_id', "=", "$movie_id")
        ->where('section_id', "=", "$section_id")
        ->get();
        
		$images = Image::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->where('site_location', '=', NULL)
		->orderBy('sort_order', 'asc')
		->get();
		
		
		$featuredImages = Image::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->where('site_location', '>', "0")
		->get();
		
		$creditImage = Image::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->where('site_location', '=', "credit")
		->get()
		->first();
		
		$personImages = Image::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->where('site_location', '>', "0")
		->get();
		
		$featuredContents = FeaturedContent::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->orderBy('sort_order', 'asc')
		->get();
		
		$releaseDates = ReleaseDate::where('movie_id', "=", "$movie_id")
		->where('section_id', '=', "$section_id")
		->orderBy('date', 'asc')
		->get();
		
        $editMovie = true;
        
        $customBackgroundColor = Design::where('movie_id', "=", "$movie_id")
        		->where('section_id', '=', "$section_id")
        		->first();
		
// 		dd($customBackgroundColor);
		
		if ($customBackgroundColor) {
			
			$bladeVariables = compact('movie', 'ctas', 'sections', 'currentUser', 'lastInitial', 'editMovie', 'currentSection', 'videos', 'people', 'images', 'tickets', 'customBackgroundColor', 'featuredContents', 'featuredImages', 'releaseDates', 'creditImage', 'personImages');
			
		} else {
			
			$bladeVariables = compact('movie', 'ctas', 'sections', 'currentUser', 'lastInitial', 'editMovie', 'currentSection', 'videos', 'people', 'images', 'tickets', 'featuredContents', 'featuredImages', 'releaseDates', 'creditImage', 'personImages');
			
		}
			
        
        
        
	    
	    	    
	    	    
	   
	    return view($currentSection->view)->with($bladeVariables);
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($movie_id, $section_id)
    {
	    
	    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $movie_id)
    {
        
        $allFields = $request->all();
        
		$keys = array_keys($allFields);

		$k=0;
		foreach ($keys as $key) {
			
			if (preg_match('/^title/', $key)) {
				$keyExploded = explode('-', $key);
				$section_ids[$k] = $keyExploded[1];
				
				$k++;
				
			}
			
		}
		
		foreach($section_ids as $section_id) {
			
	        $section = Section::findOrFail($section_id);
	        
	        $section->title = $allFields["title-$section_id"];
	        $section->movie_id = $movie_id;
	        $section->save();  
		}
		
		return redirect("/admin/movies/$movie_id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($movie_id, $section_id)
    {
	    
        $section = Section::where('id', $section_id);
        
        $section->take(1)->delete();
        
        return redirect("admin/movies/$movie_id/edit");
    }
    
}
