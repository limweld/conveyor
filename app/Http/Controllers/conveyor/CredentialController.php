<?php

namespace App\Http\Controllers\conveyor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;


class CredentialController extends Controller
{
    public function __construct()
    {
        AuthenticationController::check_session_backend();
    }

    public function create_connection(Request $request)
    {
        $connection = DB::insert('insert into credential_streamer ( ip_address, port, topic, username, password, protocol_type, description, created_at) values ( ?, ?, ?, ?, ?, ?, ?, ? )', 
            [
                $request->ip_address,
                $request->port,
                $request->topic,
                $request->username,
                $request->password,
                $request->protocol_type,
                $request->description,
                Carbon::now()
            ]
        );

        $data_obj['validity'] = ($connection != 0) ? true : false;
        $data_obj['message'] = ($connection != 0) ? "Update Success" : "Update Fail!";

        return response()->json($data_obj);
        
    }

    public function read_connection(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $errors_list = DB::select(
            'select * FROM conveyor.credential_streamer where topic like ? limit ?,?',
            [
                '%'.$request->search.'%',
                $from,
                $limit
            ]
        );

        return response()->json($errors_list);					
    }

    public function read_connection_count(Request $request)
    {
        $batch_count = DB::select(
            'select count(*) as totalrows from conveyor.credential_streamer where topic like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($batch_count[0]);					        
    }

    public function update_connection(Request $request)
    {
        
        $connection = DB::update('update conveyor.credential_streamer set ip_address = ?, port = ?, topic = ?, username = ?, password = ?, protocol_type = ?, description = ?, updated_at = NOW() where id = ?', 
            [
                $request->ip_address,
                $request->port,
                $request->topic,
                $request->username,
                $request->password,
                $request->protocol_type,
                $request->description,
                $request->id
            ]
        );

        $data_obj['validity'] = ($connection != 0) ? true : false;
        $data_obj['message'] = ($connection != 0) ? "Update Success" : "Update Fail!";

        return response()->json($data_obj);
    }

    public function delete_connection(Request $request)
    {
        $data_obj = [];
        $connection = DB::delete('delete from conveyor.credential_streamer where id = ?',
            [
                $request->id
            ]
        );

        $data_obj['validity'] = ($connection != 0) ? true : false;
        $data_obj['message'] = ($connection != 0) ? "Delete Success" : "Delete Fail!";

        return response()->json($data_obj);
    }
}