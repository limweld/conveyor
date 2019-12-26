<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request){
                
        $data_obj = [];

        $user = DB::select(
            'select * from user where username = ? ',
            [
                $request->username
            ]
        );

        if($user != null){

            $hasher = new BcryptHasher();

            $isValid = $hasher->check($request->password, $user[0]->password);

            if( $isValid ){

                $faker = Faker::create('Faker\Provider\Barcode');
                $char_fake = Faker::create('Faker\Provider\Base');        
                $generate_session_key = str_shuffle(substr($faker->ean8,4) .''.  substr($faker->ean8,3) .''. substr($faker->ean8,6) .''.  strtoupper($char_fake->lexify('???'))); 
                $hash = $hasher->make($generate_session_key);
    
                $session_update = DB::update('update user set session = ? where username = ?', 
                    [
                        $hash,
                        $request->username
                    ]
                );

                session_start();
                $_SESSION["session_key"] = $hash;
                $_SESSION["fullname"] = $user[0]->lastname." ".$user[0]->firstname;
                $_SESSION["id"] = $user[0]->id;

                $data_obj['validity'] = true;
                $data_obj['message'] = "Login Success!";
                return $data_obj;    
            }

            $data_obj['validity'] = false;
            $data_obj['message'] = "Invalid Username or Password";
            return $data_obj;
        
        }

        $data_obj['validity'] = false;
        $data_obj['message'] = "Account Not Exist";
        return $data_obj;
        
    }


    public function logout(){
        $data_obj = [];

        session_start();

        $session_update = DB::update('update user set session = null where id = ?', 
            [
                $_SESSION["id"]
            ]
        );

        session_unset();
        session_destroy(); 

        $data_obj["validity"] = true;
        return response()->json($data_obj);
    }

    public function kill(){
        session_start();
        session_unset();
        session_destroy(); 
    }

    public static function check_session_backend(){
        session_start();
        if(empty($_SESSION["session_key"])){
            exit;
        }
    }

}
