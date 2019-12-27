<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Auth;


class UserController extends Controller
{
    public function __construct()
    {
        //AuthenticationController::check_session_backend();
    }

    public function create_user(Request $request)
    {

        $data_obj = [];
        $err = (empty($request->username) ? "Username":"")." ". (empty($request->password) ? "Password":"" )." ". (empty($request->firstname) ? "Firstname":"")." ".(empty($request->lastname) ? "Lastname" : "");
        
        if(!empty($request->username) && !empty($request->password) && !empty($request->firstname) && !empty($request->lastname)){

            $user_list = DB::select(
                'select * from user where username = ?',
                [
                    $request->username
                ]
            );

            if( empty($user_list) ){

                $hasher = new BcryptHasher();
                $hash = $hasher->make($request->password);
        
                $batch_barcode = DB::insert('insert into user (username,password,firstname,lastname,created_at) values (?, ?, ?, ?, ?)',
                    [
                        $request->username, 
                        $hash, 
                        $request->firstname,
                        $request->lastname,
                        Carbon::now()
                    ]
                );
        
                $data_obj['validity'] = true;
                $data_obj['message'] = "Success Insert!";
        
                 return response()->json($data_obj);

            }

            $data_obj['validity'] = false;
            $data_obj['message'] = "Username Exist !";
    
             return response()->json($data_obj);
          
        }

        $data_obj['validity'] = false;
        $data_obj['message'] = "Required Field ". $err. ".";

         return response()->json($data_obj);

    }

    public function read_user(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $errors_list = DB::select(
            'select id, username, firstname, lastname, created_at, updated_at FROM conveyor.user where deleted_at is null and username like ? 
            order by id desc 
            limit ?,?',
            [
                '%'.$request->search.'%',
                $from,
                $limit
            ]
        );

        return response()->json($errors_list);					
    }

    public function read_user_count(Request $request)
    {
        $batch_count = DB::select(
            'select count(*) as totalrows from user where deleted_at is null and username like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($batch_count[0]);					        
    }

    public function update_user(Request $request)
    {
        $data_obj = [];
        $err = (empty($request->username) ? "Username":"")." ". (empty($request->firstname) ? "Firstname":"")." ".(empty($request->lastname) ? "Lastname" : "");
        
        if(!empty($request->username) && !empty($request->firstname) && !empty($request->lastname)){

            $user_list = DB::select(
                'select * from user where username = ?',
                [
                    $request->username
                ]
            );

            if( !empty($user_list) ){
            
                if( $user_list[0]->id == $request->id ){

                    $user = DB::update('update user set username = ?,firstname = ?, lastname = ?, updated_at = NOW() where id = ?', 
                        [
                            $request->username,
                            $request->firstname,
                            $request->lastname,
                            $request->id
                        ]
                    );
        
                    if(!empty($request->password)){

                        $hasher = new BcryptHasher();
                        $hash = $hasher->make($request->password);

                        $password = DB::update('update user set password = ?, updated_at = NOW() where id = ?', 
                            [
                                $hash,
                                $request->id
                            ]
                        );

                    }

                    $data_obj['validity'] = true;
                    $data_obj['message'] = "Update Success!";
            
                     return response()->json($data_obj);
                }

                $data_obj['validity'] = false;
                $data_obj['message'] = "Username Exist on other Account !";
        
                 return response()->json($data_obj);
                
            }

            if( empty($user_list) ){
                $user = DB::update('update user set username = ?,firstname = ?, lastname = ?, updated_at = NOW() where id = ?', 
                        [
                            $request->username,
                            $request->firstname,
                            $request->lastname,
                            $request->id
                        ]
                    );
        
                    if(!empty($request->password)){

                        $hasher = new BcryptHasher();
                        $hash = $hasher->make($request->password);

                        $password = DB::update('update user set password = ?, updated_at = NOW() where id = ?', 
                            [
                                $hash,
                                $request->id
                            ]
                        );

                    }

                    $data_obj['validity'] = true;
                    $data_obj['message'] = "Update Success!";
            
                     return response()->json($data_obj);
            }

            $data_obj['validity'] = false;
            $data_obj['message'] = "Username Exist on other Account !";
    
             return response()->json($data_obj);
    
        }

        $data_obj['validity'] = false;
        $data_obj['message'] = "Required Field ". $err. ".";

         return response()->json($data_obj);
    }

    public function delete_user(Request $request)
    {
        $data_obj = [];
        $user = DB::delete('delete from user where id = ?',
            [
                $request->id
            ]
        );

        $data_obj['validity'] = ($user != 0) ? true : false;
        $data_obj['message'] = ($user != 0) ? "Delete Success" : "Delete Fail!";

        return response()->json($data_obj);
    }

}