<?php

namespace App\Http\Controllers\Api;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth,Mail;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{

	public function register(Request $request)
	{
		$credentials = $request->only('name', 'email', 'password');

		$rules = [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users'
		];
		$validator = Validator::make($credentials, $rules);
		if($validator->fails()) {
			return response()->json(['success'=> false, 'error'=> $validator->messages()]);
		}
		$name = $request->name;
		$email = $request->email;
		$password = $request->password;
		$user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_verified' => 1, ]);

		$subject = "Please verify your email address...you may login!! ";
		Mail::send('pages.mailer',['name' => $name, 'password' => $password],
			function($mail) use ($email, $name, $subject){
				$mail->from(env('MAIL_FROM_ADDRESS'),"Itobuz-Tech");
				$mail->to($email, $name);
				$mail->subject($subject);
			});

		return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.', 'data'=> $user]);		
	}//end of function

	public function login(Request $request)
	{
		 // grab credentials from the request
		$credentials = $request->only('email', 'password');

		try {
            // attempt to verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
			return response()->json(['error' => 'could_not_create_token'], 500);
		}

        // all good so return the token
		return response()->json(compact('token'));
	}//end of function

	public function getAuthenticatedUser()
	{
		try {

			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json(['user_not_found'], 404);
			}

		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

			return response()->json(['token_expired'], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

			return response()->json(['token_invalid'], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

			return response()->json(['token_absent'], $e->getStatusCode());

		}

		// the token is valid and we have found the user via the sub claim
		return response()->json(compact('user'));
	}//end of function


	public function logout()
	{
		$data = JWTAuth::parseToken()->invalidate();
		return response()->json(['message'=>'User Logged Out Successfully!!',
			'data' => $data,
		]);
     }//end of function

     public function addRole()
     {

     	$admin = new Role();
     	$admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        $general_user = new Role();
        $general_user->name = 'user';
        $general_user->display_name = 'Application_User';
        $general_user->description = 'Application User is only allowed to view and play Tracks';
        $general_user->save();

        return response()->json(['data_user'=>$general_user]);
       
    }//end of class

    public function addPermissions(Request $request)
    {
    	$inputData = $request->all();
    	return Permission::insertData($inputData);

    }//end of function

    public function assignRole()
    {
    	$user = User::where('email','jahid@itobuz.com')->first();

    	$admin = Role::where('name','admin')->first();

    	return $user->attachRole($admin);

    }//end of function

    public function assignPermission()
    {

    	$create_users = Permission::where('name','Create Users')->first();
        
    	$edit_users   = Permission::where('name','Edit Users')->first();

    	$delete_users = Permission::where('name','Delete Users')->first();

    	$admin_role   = Role::where('name','admin')->first();

    	return $admin_role->attachPermissions(array($create_users,$edit_users,$delete_users));
    	
    }//end of function

}//end of class
