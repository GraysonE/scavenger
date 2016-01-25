<?php

namespace Scavenger\Http\Controllers;

use View;
use Storage;
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
use Scavenger\Design;
use Scavenger\Ticket;
use Scavenger\FeaturedContent;
use Scavenger\ReleaseDate;
use Scavenger\GlobalCta;
use Scavenger\GlobalSocialMedia;
use Scavenger\TagLine;
use Scavenger\Helpers\Helper;
use Auth;

class PublishingController extends Controller
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
	    
//  	    dd($meta);
	    $sections = Section::where('movie_id', "=", "$movie_id")->orderBy('sort_order', "asc")->get();
	    
	    $currentUser = Auth::user();
	    	$lastInitial = substr($currentUser->last_name, 0, 1);
	    	
	    $editMovie = true;

	    $socials = SocialMedia::where('movie_id', "=", "$movie_id")->get();
// 	    dd($socials->isEmpty());
	    
	    
	    $variables = compact('meta', 'movie', 'currentUser', 'sections', 'lastInitial', 'editMovie', 'socials');
	    
	    
	    
        return view('publishing.edit', $variables);
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
        //
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
        //
    }
    
    
    
    
    public function createHTML($movie_id, $publish_all = FALSE)
    {
      date_default_timezone_set("America/Los_Angeles"); // needed for release date comparisons?

      //a mapping of block type to model class names
      $modelMap = array(
        'mainmarquee' => 'CallToAction',
        'ticketing' => 'Ticket',
        'videos' => 'Video',
        'aboutthefilm' => 'Person',
        'partners' => 'FeaturedContent',
        'gallery' => 'Image',
        'featuredcontent' => 'FeaturedContent',
        'reviewsandawards' => 'FeaturedContent', //and Awards
        'releasedates' => 'ReleaseDate',
        'footer' => 'FeaturedContent'
      );

      $movie = Movie::findOrFail($movie_id);
      
      
      /*
       * Derive path from movie title
       */
       
       $separator = "-";
		$ignore_words = ''; //add any words to ignore from the movie title, e.g. 'a, an, as, at, the'
		$ignore_words_regex = preg_replace(array('/^[,\s]+|[,\s]+$/', '/[,\s]+/'), array('', '\b|\b'), $ignore_words);
       
      $movieDir = Helper::movieTitle($movie->title);

      //grab all dev sections
      $sections = Section::where('movie_id', '=', $movie_id)->orderBy('sort_order', 'asc')->get();
      
      //find all sections for each server first
      $servers = array('dev'=>array());
      if($publish_all)
      {
        $servers = array('live'=>array(), 'qa' => array(), 'dev' => array()); //dev last for preview
      }
      foreach($sections as $section)
      {
        //$sectionName = str_replace('sections.contentblocks.', '', $section->view);
        
        $servers['dev'][] = $section->id;        
        if($section->qa && isset($servers['qa']))      { $servers['qa'][] = $section->id; }
        if($section->live && isset($servers['live']))  { $servers['live'][] = $section->id; }
      }

      foreach ($servers as $key=>$server)
      {
        //find movies to show on this server depending on if sections exist in it
        $movies = Movie::leftJoin('sections as s', 'movies.id', '=', 's.movie_id');
        
        if($key=='qa' || $key=='live') { $movies->where('s.'.$key, '=', 1); }
        
        $movies = $movies->groupBy('movies.id')
              ->orderBy('movies.created_at', 'asc')
              ->select('movies.*')
              ->get();

        foreach($movies as $i=>$m)
        {
          //grab poster/listings images
          $results = DB::table('images')->where('movie_id', '=', $m->id)
              ->whereIn('images.site_location', array('poster', 'coming-soon'))
              ->get();        

          $images = array();
          foreach($results as $result)
          {
            $images[$result->site_location] = $result->path;
          }
          $movies[$i]->images = $images;


          //find path
          $movies[$i]->url = Helper::movieTitle($m->title);;

          
          //grab release dates          
          //find the single most current announcement
          $today = new \DateTime('today');
          $today->add(new \DateInterval('PT23H59M59S')); //11:59:59PM of today

          $current = ReleaseDate::where('movie_id', '=', $m->id)
              ->where('date', '<=', $today) //compare to end of today
              ->orderBy('date', 'desc')
              ->select('date', 'type', 'text')
              ->limit(1)
              ->get();
          
          //find all future announcements to cache in static pages
          $future = ReleaseDate::where('movie_id', '=', $m->id)
              ->where('date', '>', new \DateTime('today'))
              ->orderBy('date', 'asc')
              ->select('date', 'type', 'text')
              ->get();
          
          //grab the 'actual' official release date
          $official = ReleaseDate::where('movie_id', '=', $m->id)
              ->where('actual', '=', TRUE)
              ->select('date')
              ->first();

          $releasedates = array_merge($current->toArray(), $future->toArray());
          $releasedates = json_encode($releasedates);
          $official = !empty($official) ? $official->date : '';
          
          $movies[$i]->release_dates = $releasedates;
          $movies[$i]->official_release = $official;
          
          if($movies[$i]->id == $movie->id)
          {
            $movie->release_dates = $releasedates;
          }
        }
        
      
        $bladeVariables = array(
          'movie' => $movie,
          'server'=> ($key == 'live') ? '' : '/'.$key,
          'basepath'=>url(),
          'sections' => array()
        );

        foreach($sections as $section)
        {
          $blockType = str_replace('sections.contentblocks.', '', $section->view); //'sections.contentblock.someblocktype'
          
          //if block exists on this server
          if(array_search($section->id, $server) !== FALSE)
          {
            if(isset($modelMap[$blockType]))
            {
              $modelName = $modelMap[$blockType];
              $model = 'Scavenger\\'. $modelName;
              
              switch($modelName)
              {
                //all the similar queries can go together
                case 'CallToAction':
                case 'Video':                 
                  $block = $model::where('movie_id', '=', $movie_id)
                      ->where('section_id', '=', $section->id)
                      ->orderBy('sort_order', 'asc')
                      ->get();
                  break;

                case 'FeaturedContent':             
                  $block = $model::where("featured_contents.movie_id", '=', $movie_id)
                      //->leftJoin('images', "featured_contents.id", '=', 'images.site_location')                      
                      ->leftJoin('images', function($join)
                         {
                             $join->on('images.site_location', '=', 'featured_contents.id');
                             $join->on('images.section_id','=', 'featured_contents.section_id');
                         })
                      ->where('featured_contents.section_id', '=', $section->id)
                      ->orderBy('featured_contents.sort_order', 'asc')
                      ->get();
                  break;
                
                case 'Person':                
                  $block = array();
                  $block['crew'] = $model::leftJoin('images', function($join)
                         {
                             $join->on('images.site_location', '=', 'people.id');
                             $join->on('images.section_id','=', 'people.section_id');
                         })
                      ->where('people.movie_id', '=', $movie_id)
                      ->where('people.section_id', '=', $section->id)
                      ->where('people.occupation', '=', 'crew')
                      ->orderBy('people.sort_order', 'asc')
                      ->get();
                  $block['crew_bio_count'] = $model::where('biography', '!=', '')
                      ->whereNotNull('biography')
                      ->where('people.occupation', '=', 'crew')
                      ->count();

                  $block['cast'] = $model::leftJoin('images', function($join)
                         {
                             $join->on('images.site_location', '=', 'people.id');
                             $join->on('images.section_id','=', 'people.section_id');
                         })
                      ->where('people.movie_id', '=', $movie_id)
                      ->where('people.section_id', '=', $section->id)
                      ->where('people.occupation', '=', 'cast')
                      ->orderBy('people.sort_order', 'asc')
                      ->get();
                  $block['cast_bio_count'] = $model::where('biography', '!=', '')
                      ->whereNotNull('biography')
                      ->where('people.occupation', '=', 'cast')
                      ->count();
                  break;
                
                case 'Ticket':
                  $block = $model::where('movie_id', '=', $movie_id)
                      ->where('section_id', '=', $section->id)
                      ->where('on', '=', TRUE)
                      ->get();
                  break;

                //gallery images only
                case 'Image':
                  $block = $model::where('movie_id', '=', $movie_id)
                      ->where('section_id', '=', $section->id)
                      //->where('site_location', '>', 0)
                      ->orderBy('sort_order', 'asc')
                      ->get();
                  break;

                default:
                  break;
              }

              if(!empty($block))
              {
                //also check if custom bg for this section
                $bgColor = Design::where('movie_id', "=", $movie_id)->where('section_id', '=', $section->id)->first();
                $bgImage = Image::where('movie_id', '=', $movie_id)->where('section_id', '=', $section->id)->where('site_location', '=', NULL)->first();

                //inserted in section sort order
                $bladeVariables['sections'][] = (object)array(
                  'id' => $section->id,
                  'title' => $section->title,
                  'weight'=>$section->sort_order,
                  'display'=>$section->display,
                  'type' => $blockType,
                  'bgcolor' => (!empty($bgColor) && !empty($bgColor->custom_background)) ? $bgColor->custom_background : '#ffffff',
                  'bgimage' => (!empty($bgImage) && !empty($bgImage->path)) ? $bgImage->path : FALSE,
                  'content' => $block
                );
              }
            }
          }
        }
        
        //also grab all images independent of sections if any
        $data = Image::where('movie_id', '=', $movie_id)
            ->where('section_id', '=', NULL)
            ->where('site_location', '=', NULL)
            ->orderBy('sort_order', 'asc')
            ->get();
        $bladeVariables['images'] = $data;
        
        //also grab Design settings for fonts, colors
        $data = Design::where('movie_id', '=', $movie_id)
            ->where('section_id', '=', NULL)
            ->first();
        if(!empty($data))
        {
          $data->global_navigation_font_name = str_replace('+', ' ', preg_replace('/([^:]+)(:[0-9]+)?$/', '$1', $data->global_navigation_font));
          $data->header_font_name = str_replace('+', ' ', preg_replace('/([^:]+)(:[0-9]+)?$/', '$1', $data->header_font));
          $data->paragraph_font_name = str_replace('+', ' ', preg_replace('/([^:]+)(:[0-9]+)?$/', '$1', $data->paragraph_font));
          $data->footer_font_name = str_replace('+', ' ', preg_replace('/([^:]+)(:[0-9]+)?$/', '$1', $data->footer_font));
        }
        $bladeVariables['design'] = $data;
        
        //also grab Metatag settings
        $data = MetaTag::where('movie_id', '=', $movie_id)->first();
        $bladeVariables['metatags'] = $data;

        //also grab SEO and Social settings
        $seosocial = SocialMedia::where('movie_id', '=', $movie_id)->get();
        $social_img = Image::where('movie_id', '=', $movie_id)->where('site_location', '=', 'social')->first();
        $seosocial->social_image = !empty($social_img->path) ? $social_img->path : '';
        $bladeVariables['seosocial'] = $seosocial;
        
        //dd($bladeVariables['seosocial']);
        
        //ALSO grab global settings
        $footerVariables = array();
        $data = GlobalCta::orderBy('column', 'asc')->orderBy('sort_order', 'asc')->get();
        $footerVariables['global_cta'] = $data;

        $data = Image::where('movie_id', '=', $movie_id)
                      ->where('site_location' , '=', 'credit')
                      ->first();
        $bladeVariables['credit_image'] = $data;


        $global_social_media = GlobalSocialMedia::get();
        $global_social_img = Image::where('site_location', '=', 'global_social')->first();
        $global_social_media->social_image = !empty($global_social_img->path) ? $global_social_img->path : '';

        $footerVariables['global_social_media'] = $global_social_media;
        $bladeVariables['global_social_media'] = $global_social_media;

        

        $key = ($key == 'live') ? '' : '/'.$key;

        // SAVE EVERYTHING TO STATIC FILES
        $listingsVariables = array(
          'type'=>'in_theaters', 
          'movies'=>$movies, 
          'server'=>$key,
          'global_social_media'=>$global_social_media, 
          'basepath'=>url()
        );

        
        //update movie listings pages like In Theaters/At Home/Coming Soon
        $view = view('static-listings')->with($listingsVariables);
        Storage::disk('static-html')->put('shared' . $key . '/in-theaters.html', $view);
        $listingsVariables['type'] = 'coming_soon';
        $view = view('static-listings')->with($listingsVariables);
        Storage::disk('static-html')->put('shared' . $key . '/coming-soon.html', $view);
        $listingsVariables['type'] = 'at_home';
        $view = view('static-listings')->with($listingsVariables);
        Storage::disk('static-html')->put('shared' . $key . '/at-home.html', $view);

        
        $view = view('static-menu')->with(array('movies'=>$movies, 'server'=>$key, 'basepath'=>url()));
        Storage::disk('static-html')->put('shared' . $key . '/menu.html', $view);
        
        $view = view('static-footer')->with($footerVariables);
        Storage::disk('static-html')->put('shared' . $key . '/footer.html', $view);

        //load last
        $view = view('static-html')->with($bladeVariables);
//         dd(Storage::disk('movies'));
        Storage::disk('static-html')->put($movieDir . $key . '/index.html', $view);
      }
        
      return $view;
    }
    
    
    
    
    
    public function env(Request $request, $movie_id)
    {
	    if (isset($_POST['qa'])){
		    
		    $QA_sections =  $_POST['qa'];
		    foreach($QA_sections as $id) {
		        $data = Section::where('id', $id)->get()->first();
		        
		        ($data->id == $id) ? $data->qa = 1 : $data->qa = 0;
		        
		        //echo $data->title . ' ' . $data->qa . '<br>';
		        $data->movie_id = $movie_id;
		        $data->save();
        	}
        	
        	$sections = Section::where('movie_id', $movie_id)->get();
	        foreach ($sections as $section) {
		        if (!in_array($section->id, $QA_sections)) {
			        //echo "<br>0<br>";
			        $section->qa = 0;
			        $section->save();
			    }
	        }
	    } else {
		    $sections = Section::where('movie_id', $movie_id)->get();
	        foreach ($sections as $section) {
			        $section->qa = 0;
			        $section->save();
	        }
	    }
        
        

      if (isset($_POST['live'])){

        $live_sections = $_POST['live'];

        foreach($live_sections as $id) {
            $data = Section::where('id', $id)->get()->first();

            ($data->id == $id) ? $data->live = 1 : $data->live = 0;

            //echo $data->title . ' ' . $data->live . '<br>';
            $data->movie_id = $movie_id;

            $data->save();
        }

        $sections = Section::where('movie_id', $movie_id)->get();
            foreach ($sections as $section) {
              if (!in_array($section->id, $live_sections)) {
                //echo "<br>0<br>";
                $section->live = 0;
                $section->save();
              }
            }

      } else {
		    $sections = Section::where('movie_id', $movie_id)->get();
	        foreach ($sections as $section) {
		        $section->live = 0;
		        $section->save();
	        }
	    }
      
      if(isset($_POST['qa']) || isset($_POST['live']))
      {
        $this->createHTML($movie_id, TRUE);
      }
        

      return redirect("admin/movies/$movie_id/edit/publishing");   
    }
    
}
