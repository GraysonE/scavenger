<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\ReleaseDate;
use Carbon\Carbon;

class ReleaseDateController extends Controller
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
      
      $data = new ReleaseDate();
      $data->text = "In Theaters Now";
      $data->date = Carbon::now();
      $data->movie_id = $movie_id;
      $data->section_id = $section_id;
      $data->save();
      
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
        $pureRequest = $request;
    $request = $request->all();

      $keys = array_keys($request);
//      dd($request);
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
            
          $data = ReleaseDate::findOrFail($id);
          
          $this->validate($pureRequest, [
           "text-$id" => 'required|min:1|max:255',
         "date-$id" => 'required|date|min:1',
          ]);

	        $data->movie_id = $movie_id;
	        $data->section_id = $section_id;
	        $data->text = $pureRequest["text-$id"];
	        $data->date = $pureRequest["date-$id"];
	        $data->type = $pureRequest["type-$id"];
	        $data->save();  	        
	        
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
    public function destroy($movie_id, $section_id, $release_date_id)
    {
        $data = ReleaseDate::where('id', $release_date_id);
        
        $data->take(1)->delete();
        
        return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }
    
    public function duplicate($movie_id, $section_id, $release_date_id)
    {
        $data = ReleaseDate::findOrFail($release_date_id)->toArray();
//      dd($data);
      ReleaseDate::create($data);
      
      return redirect("admin/movies/$movie_id/edit/sections/$section_id");
    }
}
