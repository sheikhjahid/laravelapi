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

     public function addRole(Request $request)
     {

     	$inputData = $request->all();
     	return Role::create($inputData);
       
    }//end of class

    public function addPermissions(Request $request)
    {
    	$inputData = $request->all();
    	return Permission::create($inputData);

    }//end of function

   public function assignRole(Request $request)
   {

   		$credentials = $request->only('email','name');
   		$rules = [
   			'email' => 'required|email|max:255',
   			'name' => 'required|max:255'
   		];
   		$validator = Validator::make($credentials,$rules);
   		if($validator->fails())
   		{
   			return response()->json([
   				'success' => false,
   				'message'=>$validator->messages(),
   				'status_code'=>404
   		         ]);
   		}//end of if
   		else
   		{	
   		$email = User::where('email',$request->email)->first();
   		$role = Role::where('name',$request->name)->first();
   		$data = $email->attachRole($role);
   		return response()->json([
   			                  'messages'=>'Role Assigned Successfully!!',
   			                  'data'=>$data,
   			                  'status_code'=>200
   		                       ]);
   	    }//end of else
   }//end of function

   public function assignPermission(Request $request)
   {

   		$credentials = $request->only('name','display_name');

   		$rules = [

   			'name' => 'required|max:255',
   			'display_name' => 'required|max:255'

   		];

   		$validator = Validator::make($credentials,$rules);

   		if($validator->fails())
   		{
   			return response()->json([
   				'success' => fail,
   				'messages' => $validator->messages(),
   				'status_code' => 404	

   		        ]);	
   		}	
   		else
   		{
   			$name = Role::where('name',$request->name)->first();
   			$display_name = Permission::where('display_name',$request->display_name)->first();
   			$data = $name->attachPermission($display_name);
   			return response()->json([
   				'messages' => 'Permission assigned successfully',
   				'data' => $data,
   				'status_code' => 200

   			]);
   		}//end of if
   		
   }//end of function

    public function allUser()
    {
    	return User::all();
    }//end of function

    public function searchUser(Request $request)
    {
    	$searchInput = $request->name;
    	if (User::getUser($searchInput)==true)
    		{
    			return response()->json(['message'=>'Found User!!',
    									 'data' => User::getUser($searchInput),
    									 'status_code' => 200]);
    		}//end of if
    		
    }//end of function

    public function updateUser(Request $request, $id)
    {
    	$inputData = $request->all();
    	if (User::updateData($id,$inputData)==1)
    		{
    			return response()->json([
    				'message' => 'User Details updated successfully!!',
    				'data' => User::find($id)
    			]);
    		}
    }//end of function

    public function deleteUser($id)
    {
    	return User::deleteData($id);
    }//end of function

}//end of class
