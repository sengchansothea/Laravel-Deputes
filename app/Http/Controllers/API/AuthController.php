<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CompanyApi;
use App\Models\PersionalAccessToken;
use App\Models\User;
use App\Traits\HttpResponsesTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use HttpResponsesTrait;
    /** date: 26-07-2023
     * Register api mean
     * 1. validation: fullname, username, email, password
     * 2. create user in table users
     * 3. create token in table  personal_access_tokens
     * 4. return token key for using it to access api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
//        $validatedData = $request->validate([
//            'fullname' => 'required|string|max:255',
//            'username' => 'required|string|min:4|max:40',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:8',
//        ]);
//
//        if($validatedData->fails()){
//            return response()->json([
//                'status' => false,
//                'message' => 'validation error',
//                'errors' => $validatedData->errors()
//            ], 401);
//        }

        $user = User::create([
            "username" =>$request->username, //$validatedData['username'],
            'password' => Hash::make($request->password), //Hash::make($validatedData['password']),
            'k_fullname' =>$request->fullname, //$validatedData['fullname'],
            'email' =>$request->email, //$validatedData['email'],
            "banned" => 0,
//            "k_role_id" => 0,
//            "k_team" => 0,
//            "k_province" => 0,
//            "k_parents" => 1,
//            "last_ip" => "127.0.0.1",
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function update(Request $request)
    {
        $tokenable_id = $request->tokenable_id;
        //$tokenable_id= 10;
        //$plainTextToken = Str::random(40)."--".$tokenable_id;
        $result=DB::table("personal_access_tokens")
            ->where("tokenable_id", $tokenable_id)
            ->update(["token" => hash('sha256', $plainTextToken = Str::random(40)), "updated_at"=> date("Y-m-d G:i:s")]);
        //$userToken = PersionalAccessToken::where("tokenable_id", $tokenable_id)->first();
//        $userToken->token = hash('sha256', $plainTextToken = Str::random(40));
//        $userToken->updated_at = date("Y-m-d G:i:s");
//        $userToken->save();
        $key=0;
        $q=0;
        $token= "";
        if($result){
            $key= DB::table("personal_access_tokens")->select("id")->where("tokenable_id", $tokenable_id)->first()->id;
            //$key=$q->id;

            $token= $key."|".$plainTextToken;
        }

        return response()->json([
            'status' => true,
            'token' => $token,
            'token_type' => 'Bearer',
            'result' => $q
        ]);
    }

    /**
     * Login api for Company Account from LACMS
     *
     * @return \Illuminate\Http\Response
     */
    public function loginCompany(Request $request)
    {
        //dd($request->all());
       $response = loginCompany($request);
       return $response;


//       if($response->status() == 200){ // login success
//           $company_id = 123890;
//           //$company_id = 12801;
//           $company = CompanyApi::where("company_id", $company_id)->first();
//           $user = User::where("company_id", $company_id)->first();
//           $password = $request->password;
//           if($user == null){ // no user in table user
//               $username = "company_".$company_id."_update";
//               $email = $request->username;
//               $fullname = "company_".$company_id;
//               $user = User::create([
//                   "username" => $username, //$validatedData['username'],
//                   'password' => Hash::make($password), //Hash::make($validatedData['password']),
//                   'k_fullname' => $fullname, //$validatedData['fullname'],
//                   'email' => $email, //$validatedData['email'],
//                   "company_id" => $company_id,
//                   "k_role_id" => 0,
//                   "k_team" => 0,
//                   "k_province" => 0,
//                   "k_parents" => 1,
//                   "banned" => 0,
//               ]);
//           }
//           else{ // exists user in table user, so update
//               $user->password = Hash::make($password);
//               $user->save();
//           }
//
//
//           $token = $user->createToken('auth_token')->plainTextToken;
//           $data = [
//               "company" => $company,
//               "user" => $user
//           ];
//           dd($data);
//           return response()->json(
//               [
//                   'status' => 200,
//                   'token' => $token,
//                   'token_type' => 'Bearer',
//                   'message'=> "success",
//                   'data'=> $data
//               ], 200
//           );
//       }
//       else{ // fail to login
//           return response()->json(
//               [
//                   'status' => 400,
//                   'message'=> "fail to login",
//                   'data'=> null
//               ], 400
//           );
//       }

    }
    public function logoutCompany(Request $request){
        /* --- Revoke the token that was used to authenticate the current request. -- */
//            $request->user()->currentAccessToken()->delete();
        // Revoke all tokens...logout from all devices of a user
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out'
        ]);
    }
    /**
     * Login api for Officer
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login2(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['name'] =  $user->username;
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['token_type']="Bearer";
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }

    }

    public function me(Request $request)
    {
        return $request->user();
    }
}
