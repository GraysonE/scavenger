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
    
}
