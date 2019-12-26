<?php

namespace App\Http\Controllers\conveyor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        AuthenticationController::check_session_backend();
    }

    public function create(Request $request)
    {

    }

    public function read(Request $request)
    {
        
    }

    public function read_quota(Request $request)
    {
        $data_obj = [];

        $quota = DB::select('
        
            select 
                s.id,
                s.title,
                b.sortercode,  
                count(b.sortercode) as total 
            from conveyor.barcode as c

            left join conveyor.batch_barcode as b on b.batch_id = c.batch_id
            left join conveyor.sort_code as s on s.code = b.sortercode

            where 
                c.status = "Scanned"
                and DATE(c.updated_at) = CURDATE()	  
                and c.deleted_at is null

            group by b.sortercode 
            order by s.id asc    

        ');

        $error = DB::select('select count(*) as total from error_barcode where DATE(created_at) = CURDATE() and deleted_at is null');

        $data_obj['quota'] = $quota;
        $data_obj['error'] = $error;

        return response()->json($data_obj);

    }

    public function read_error(Request $request)
    {
        
    }


    public function update(Request $request)
    {
        
    }

    public function delete(Request $request)
    {
        
    }
}
