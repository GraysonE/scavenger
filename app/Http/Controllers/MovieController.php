<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\User;
use Scavenger\Movie;
use Scavenger\Design;
use Scavenger\Section;
use Scavenger\MetaTag;
use Scavenger\SocialMedia;
use DB;
use Auth;
use Scavenger\ReleaseDate;
use Carbon\Carbon;
use Storage;
use Scavenger\Helpers\Helper;

class MovieController extends Controller
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
    public function store(Request $request)
    {
      
      $this->validate($request, [
           "release_date" => 'required|date|min:1'
       ]);
      
      $request = $request->all();
      $request['user_id'] = Auth::user()->id;
      
      
      
      // NEW MOVIE
      $request['legal_line'] = '&copy; 2015 STX Entertainment. All Rights Reserved.';
      $movie = Movie::create($request);
      
      $movie_id = $movie->id;
      
      $movieTitle = Helper::movieTitle($movie->title);
      
      
      
      Storage::disk('movies')->makeDirectory($movieTitle.'/img');
      
      
      
      
      
      // SECTIONS
      $sections = [
        'movie_id' => "$movie_id", 
        'title' => 'Main Marquee & CTA', 
        'view' => 'sections.contentblocks.mainmarquee', 
        'sort_order' => '2', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.mainmarquee')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
// 	    dd($design);
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Ticketing', 
        'view' => 'sections.contentblocks.ticketing', 
        'sort_order' => '3', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.ticketing')
        ->get()
        ->first()
        ->id;
      
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Videos', 
        'view' => 'sections.contentblocks.videos', 
        'sort_order' => '4', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.videos')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'About the Film', 
        'view' => 'sections.contentblocks.aboutthefilm', 
        'sort_order' => '5', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.aboutthefilm')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Partners', 
        'view' => 'sections.contentblocks.partners', 
        'sort_order' => '6', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.partners')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Gallery', 
        'view' => 'sections.contentblocks.gallery', 
        'sort_order' => '7', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.gallery')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Featured Content', 
        'view' => 'sections.contentblocks.featuredcontent', 
        'sort_order' => '8', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.featuredcontent')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Reviews & Awards', 
        'view' => 'sections.contentblocks.reviewsandawards', 
        'sort_order' => '9', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.reviewsandawards')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Release Dates', 
        'view' => 'sections.contentblocks.releasedates', 
        'sort_order' => '10', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.releasedates')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      
      $sections = [
        'movie_id' => "$movie_id",
        'title' => 'Movie Footer', 
        'view' => 'sections.contentblocks.footer', 
        'sort_order' => '11', 
        'display' => '1', 
        'qa' => false, 
        'live' => false
      ];
      
      Section::create($sections);
      
      $section_id = Section::where('movie_id', '=', $movie_id)
        ->where('view', '=', 'sections.contentblocks.videos')
        ->get()
        ->first()
        ->id;
        
      $design = [
        'movie_id' => "$movie_id",
        'section_id' => "$section_id",
        'custom_background' => '#04c1d4'
      ];
      
      Design::create($design);
      
      
      
      
      // RELEASE DATE
      $releaseDate = Carbon::parse($request['release_date'])->format('Y-m-d');
      $releaseDateArray['date'] = $releaseDate;
      $releaseDateArray['movie_id'] = "$movie_id";
      
        $releaseDateSection_id = Section::where('movie_id', "=", "$movie_id")
          ->where('view', "=", "sections.contentblocks.releasedates")
        ->get()->first();
    
      
      $releaseDateArray['movie_id'] = "$movie_id";
      $releaseDateArray['section_id'] = "$releaseDateSection_id->id";
      $releaseDateArray['actual'] = 1;
      $releaseDateArray['type'] = 'in_theaters';
      $releaseDateArray['text'] = 'In Theaters Now';
      
      ReleaseDate::create($releaseDateArray);
      
      
      
      // DESIGNS
      $design = [
        'movie_id' => "$movie_id", 
        'global_navigation_font' => 'Lato', 
        'header_font' => 'Lato', 
        'paragraph_font' => 'Lato', 
        'footer_font' => 'Lato', 
        'custom_background' => '', 
        'desktop_background_color' => '#333', 
        'mobile_background_color' => '#333', 
        'desktop_buttons_color' => '#497959', 
        'mobile_buttons_color' => '#497959', 
        'desktop_header_color' => '#333', 
        'mobile_header_color' => '#333', 
        'desktop_paragraph_color' => '#333', 
        'mobile_paragraph_color' => '#333'
      ];
      
      Design::create($design);
      
      // DESIGNS
      $metaTag = [
        'movie_id' => "$movie_id", 
        'canonical' => NULL, 
        'category' => NULL, 
        'copyright' => NULL, 
        'description' => NULL, 
        'keywords' => NULL, 
        'language' => NULL, 
        'owner' => NULL, 
        'publisher' => NULL, 
        'rating' => NULL, 
        'url' => NULL, 
      ];
      
      MetaTag::create($metaTag);
      
      
	    $socialMedias = [
	          
	        ['movie_id' => "$movie_id", 'tag' => 'facebook', 'url' => 'http://facebook.com/movie'], 
	        ['movie_id' => "$movie_id", 'tag' => 'twitter', 'url' => 'http://twitter.com/movie'], 
	        ['movie_id' => "$movie_id", 'tag' => 'tumblr', 'url' => 'http://tumblr.com/movie'], 
	        ['movie_id' => "$movie_id", 'tag' => 'instagram', 'url' => 'http://instagram.com/movie'], 
	        ['movie_id' => "$movie_id", 'tag' => 'og_title', 'url' => ''], 
	        ['movie_id' => "$movie_id", 'tag' => 'og_type', 'url' => ''], 
	        ['movie_id' => "$movie_id", 'tag' => 'og_image', 'url' => ''], 
	        ['movie_id' => "$movie_id", 'tag' => 'og_url', 'url' => ''], 
        
	    ];
	    
	    foreach ($socialMedias as $socialMedia) {
	      SocialMedia::create($socialMedia);
	    }
	      
	      
	  // Create new movie image directory
// 	  $storage = Storage::disk('uploads/img');
// 	      dd($storage);
      
      return redirect('admin');
      
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($movie_id)
    {
        
      
      if (Auth::check())
    {
      $currentUser = Auth::user();
        $lastInitial = substr($currentUser->last_name, 0, 1);
        
        $movie = Movie::findOrFail($movie_id);
        
      $sections = $movie->sections()->orderBy('sort_order', 'asc')->get();
//   				$currentSection = Section::findOrFail($section_id);
        
        
        $editMovie = true;
                
        return view('movies.edit', compact('currentUser', 'lastInitial', 'movie', 'sections', 'editMovie')); 
    } else {
      return view('auth.login'); 
    }
      
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
	    $movie = Movie::findOrFail($movie_id);
        
        $movie->about = $request->get('about');
        $movie->title = $request->get('title');
        $movie->updated_at = Carbon::now();
        
        $movie->save();
            
        $section_id = $request->get('section_id');
        
        if (NULL !== $request->get('about')) {
        	return redirect("admin/movies/$movie_id/edit/sections/$section_id");
        } else {
	        return redirect("admin");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($movie_id)
    {
        $movie = Movie::findOrFail($movie_id);
        
        $movieTitle = Helper::movieTitle($movie->title);
      
      
		Storage::disk('movies')->deleteDirectory($movieTitle);
        
        $movie->delete();
        
        return redirect("/admin");
        
        
    }
    
    
    
}
