<?php

namespace App\Http\Controllers\conveyor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use TCPDF;
use TCPDFBarcode;
use Auth;


class CredentialController extends Controller
{
    public function __construct()
    {
        //AuthenticationController::check_session_backend();
    }

    public function create_connection(Request $request)
    {

    }

    public function read_connection(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $errors_list = DB::select(
            'select id, username, firstname, lastname, created_at created_at FROM conveyor.user where deleted_at is null and username like ? 
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

    public function read_connection_count(Request $request)
    {
        $batch_count = DB::select(
            'select count(*) as totalrows from user where deleted_at is null and username like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($batch_count[0]);					        
    }

    public function update_connection(Request $request)
    {

    }

    public function delete_connection(Request $request)
    {

    }
}