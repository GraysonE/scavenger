<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\User;
use Scavenger\ModelAccount;
use Auth;
use DB;

class SortController extends Controller
{
    
    public function modelAccountSortOrder(Request $request)
    {
	    	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = ModelAccount::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    public function sectionSortOrder(Request $request, $movie_id)
    {
	    	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = Section::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    public function videoSortOrder(Request $request, $movie_id, $section_id)
    {
	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = Video::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    public function settingSortOrder(Request $request)
    {
	     
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = GlobalCta::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    public function ctaSortOrder(Request $request, $movie_id, $section_id)
    {
	    	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = CallToAction::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    
    public function castAndCrewSortOrder(Request $request, $movie_id, $section_id)
    {
	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    
		    if (preg_match('/^person/', $id)) {
				$exploded = explode('-', $id);
			    $item = Person::find($exploded[1]);
	  		    $item->sort_order = $i;
	 		    $item->save();
			    $i++;
			}

	    }
	    
    }
    
    public function gallerySortOrder(Request $request, $movie_id, $section_id)
    {
	    	    
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = Image::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
    public function featuredSortOrder(Request $request, $movie_id, $section_id)
    {
	        
	    $i=1;
	    foreach($request->get('order') as $id) {
		    $item = FeaturedContent::find($id);
  		    $item->sort_order = $i;
 		    $item->save();
		    $i++;
	    }
	    
    }
    
}
