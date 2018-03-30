<?php

namespace App\Http\Controllers\Api;

use App\Track;
//use App\Http\Requests\TrackRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MusicController extends Controller
{
	public function index()
	{
		$track_list = Track::all();
		return response()->json(['data'=>$track_list]);

    }//end of function

    public function add(Request $request)
    {
    	$inputData = $request->all();

    	return Track::insertData($inputData);

    }//end of function

    public function search($id)
    {
        return Track::find($id);

    }//end of function

    public function update($id, Request $request)
    {

        //$findId = Track::find($id);
        $inputData = $request->all();
        if(Track::updateData($id,$inputData)==1)
        {
            return Track::find($id);
        }//end of if
        else
        {
            return "Cannot Update Data";
        }//end of else

    }//end of function

    public function delete($id)
    {

        $findId = Track::find($id);
        if(Track::deleteData($id)==1)
        {
            return "Track Deleted Successfully!!";
        }//end of if
        else
        {
            return "Could not delete Track!!";
        }//end of else

    }//end of function



}//end of class
