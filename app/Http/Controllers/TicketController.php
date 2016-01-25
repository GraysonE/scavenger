<?php

namespace Scavenger\Http\Controllers;

use Illuminate\Http\Request;
use Scavenger\Http\Requests;
use Scavenger\Http\Controllers\Controller;
use Scavenger\Ticket;

class TicketController extends Controller
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
        $tickets = $request->all();
        
        
        
        if ((isset($tickets['movietickets_id'])) || (isset($tickets['fandango_id'])) || (isset($tickets['powster_id']))) {
	        
	        $powster = Ticket::findOrFail($tickets['powster_id']);
	        $powster->value = $tickets['powster_url'];
	        $powster->provider = 'powster';
	        $powster->on = $tickets['powster_display'];
	        $powster->movie_id = $movie_id;
	        $powster->section_id = $section_id;
	        $powster->save();
	        
	        
	        $fandango = Ticket::findOrFail($tickets['fandango_id']);
	        $fandango->value = $tickets['fandango'];
	        $fandango->provider = 'fandango';
	        $fandango->on = $tickets['simple_display'];
	        $fandango->movie_id = $movie_id;
	        $fandango->section_id = $section_id;
	        $fandango->save();
	        
	        $movietickets = Ticket::findOrFail($tickets['movietickets_id']);
	        $movietickets->value = $tickets['movietickets'];
	        $movietickets->provider = 'movietickets';
	        $movietickets->on = $tickets['simple_display'];
	        $movietickets->movie_id = $movie_id;
	        $movietickets->section_id = $section_id;
	        $movietickets->save();
	        
        } else {
	        $ticketUpdate = new Ticket();
	        $ticketUpdate->value = $tickets['powster_url'];
	        $ticketUpdate->provider = 'powster';
	        $ticketUpdate->on = $tickets['powster_display'];
	        $ticketUpdate->movie_id = $movie_id;
	        $ticketUpdate->section_id = $section_id;
	        $ticketUpdate->save();
	        
	        $ticketUpdate = new Ticket();
	        $ticketUpdate->value = $tickets['fandango'];
	        $ticketUpdate->provider = 'fandango';
	        $ticketUpdate->on = $tickets['simple_display'];
	        $ticketUpdate->movie_id = $movie_id;
	        $ticketUpdate->section_id = $section_id;
	        $ticketUpdate->save();
	        
	        $ticketUpdate = new Ticket();
	        $ticketUpdate->value = $tickets['movietickets'];
	        $ticketUpdate->provider = 'movietickets';
	        $ticketUpdate->on = $tickets['simple_display'];
	        $ticketUpdate->movie_id = $movie_id;
	        $ticketUpdate->section_id = $section_id;
	        $ticketUpdate->save();
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
    public function destroy($id)
    {
        //
    }
}
