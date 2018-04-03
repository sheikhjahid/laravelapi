<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function updateData($id,$data)
    {

        return User::where('id',$id)->update($data);

    }//end of function

    public function deleteData($id)
    {
        return User::where('id',$id)->delete();
    }//end of function

    public static function getUser($name)
    {
        return User::where('name','LIKE',"%{$name}%")->get();    
    }//end of function 

    
}//end of class
