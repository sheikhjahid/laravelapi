<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
   
	public static function insertData($data)
	{

		return Permission::create($data);

	}//end of function

}//end of class
