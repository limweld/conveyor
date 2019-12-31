<?php

namespace App\Http\Controllers\conveyor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

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

    public function read_quota_hourly(){
 
        $data_obj = [];
        $data_list_fix = [];

        $quota = DB::select('
            select 
                s.id as code_id,
                s.title,
                b.sortercode,
                count(c.updated_at) as total,
                year(c.updated_at) as year,
                monthname(c.created_at) as month,
                day(c.updated_at) as day,
                hour(c.updated_at) as hour
                
            from conveyor.barcode as c 
            left join conveyor.batch_barcode as b on b.batch_id = c.batch_id 
            left join conveyor.sort_code as s on s.code = b.sortercode
            
            where 
                c.status = "Scanned" 
                and c.deleted_at is null
                and c.updated_at > CURDATE()

            group by hour(c.updated_at), b.sortercode
            order by c.updated_at, b.sortercode
        ');

        $errors = DB::select('
            select 
                hour(eb.created_at) as hour, 
                count(eb.id) as total
            from conveyor.error_barcode as eb 
            where 
                eb.created_at > curdate()
            group by hour(eb.created_at)
            order by eb.created_at
        ');

       
        $time_range_data = [];

        foreach( $quota as $key => $value ){
                
   
            $data_list_fix[] = [
                "code_id" => $value->code_id,
                "sorter_code" => $value->sortercode,
                "title" => $value->title,
                "hour" => $value->hour,
                "total" => $value->total 
            ];
            
            if(!in_array( $value->hour ,$time_range_data) ){
               
                $time_range_data[] = $value->hour;

            }

        }


        foreach( $errors as $key => $value ){
                

            $data_list_fix[] = [
                "code_id" => 0,
                "sorter_code" => "ERR",
                "title" => "Error",
                "hour" => $value->hour,
                "total" => $value->total 
            ];
        
            if(!in_array( $value->hour, $time_range_data) ){
               
                $time_range_data[] = $value->hour;

            }

        }

        $fix_time_range = [];
        $labels = [];

        for ($x = min($time_range_data); $x <= max($time_range_data); $x++) {

            $time = date( 'g:i A', strtotime( strval($x).":00" ));
            $fix_time_range[] = [
                "index" => $x,
                "time" => $time
            ];

            $labels[] = $time;
        } 


        $data = [];
        $series_array_obj = [];
      
        foreach( $data_list_fix as $key => $value ){

            $data_series = [
                "id" =>$value['code_id'],
                "title" => $value['title']
            ];

            if(!in_array( $data_series, $series_array_obj) ){
               
                $series_array_obj[] = $data_series;

            }
        }

        $collection_series = new Collection($series_array_obj);
        $sorted_series = $collection_series->sortBy('id');

        $collection_series_pluck = collect($sorted_series)->pluck('title'); 
        $series = $collection_series_pluck->all();

        $default_data = [];

        foreach($sorted_series as $key => $value){

            $matrix_data = [];

            for ($x = min($time_range_data); $x <= max($time_range_data); $x++) {
                $matrix_data[] = 0;
            }

            $default_data[] = $matrix_data;
        }

        foreach($data_list_fix as $key => $value){
            $default_data[$value['code_id']][$value['hour']- min($time_range_data)] = $value['total'];
        }

        $data_obj['series'] = $series; 
        $data_obj['labels'] = $labels;
        $data_obj['data'] = $default_data;
        
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
