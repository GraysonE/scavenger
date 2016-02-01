<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Auth;
use Scavenger\CallToAction;
use Validator;

class CtaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctas = CallToAction::get();
        dd($ctas);
        $editMovie = true;
        
        return view('sections.cta.index', compact('ctas', 'editMovie')); 
        
//         dd($cta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($movie_id, $section_id)
    {
// 	    dd('hello');
        $cta = new CallToAction();
        	$cta->movie_id = $movie_id;
        	$cta->section_id = $section_id;
	        $cta->text = 'New Call To Action';
	        $cta->url = 'http://newexample.com';
	        $cta->anchor = '';
	        $cta->sort_order = 0;
        $cta->save();
        
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
// 	    dd($request);
	    $pureRequest = $request;
	    $request = $request->all();
	    
	    
	    if (isset($request['new'])) {
		    
		    $validator = Validator::make($request, [
	            'text' => 'required|min:1|max:255',
	            'url' => 'url',
	        ]);
	
	        if ($validator->fails()) {
	            return redirect("admin/movies/$movie_id/edit/sections/$section_id")
	                        ->withErrors($validator)
	                        ->withInput();
	        }
		    $request['sort_order'] = 0;
		    $request['section_id'] = $section_id;
		    $request['movie_id'] = $movie_id;
		    CallToAction::create($request);	
		    
	    } else {
		    
		    $keys = array_keys($request);
			
			$k=0;
			$ids = array();
			foreach ($keys as $key) {
				
				if (preg_match('/^text/', $key)) {
					$keyExploded = explode('-', $key);
					$ids[$k] = $keyExploded[1];
					
					$k++;
					
				}
				
			}
			
			foreach($ids as $id) {
				
		        $update = CallToAction::findOrFail($id);
		        
		        $this->validate($pureRequest, [
	 		       "text-$id" => 'required|min:1|max:255',
	 			   "url-$id" => 'url',
		        ]);
	
		        $update->text = $request["text-$id"];
		        $update->url = $request["url-$id"];
		        $update->anchor = $request["anchor-$id"];
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
    public function edit($movie_id, $section_id, $cta_id)
    {
        $cta = CallToAction::findOrFail($cta_id);
        $editMovie = true;
        
        return view('sections.cta.edit', compact('cta', 'editMovie')); 
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
    public function destroy($movieID, $section_id, $cta_id)
    {
        $cta = CallToAction::where('id', $cta_id);
        
        $cta->take(1)->delete();
        
        return redirect("admin/movies/$movieID/edit/sections/$section_id");
    }
    
    public function duplicate(Request $request, $movie_id, $section_id, $cta_id)
    {
        $cta = CallToAction::findOrFail($cta_id)->toArray();
	    
	    $newCTA = CallToAction::create($cta);	    
	    
	    
	    return redirect("admin/movies/$movie_id/edit/sections/$section_id");

    }
}
