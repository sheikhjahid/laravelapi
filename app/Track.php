<?php

namespace App;

use App\Track;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
	
	protected $fillable = [

		'name',
		'singer/band',
		'productions',
		'uploaded_at'

	];

	public static function insertData($data)
	{

		return Track::create($data); 

	}//end of function

	public static function updateData($id,$data)
	{

		return Track::where('id',$id)->update($data);

	}//end of function

	public static function deleteData($id)
	{
		return Track::where('id',$id)->delete();
	}//end of function

	public static function getSearch($data)
	{

		return Track::where('name','LIKE',"%{$data}%")->get();

	}//end of fuunctions

}//end of class
