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
        //AuthenticationController::check_session_backend();
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


    public function read_quota_hourly_test(){
        $data_objs = [];
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
                #and c.updated_at > CURDATE()
                #and c.updated_at > (CURDATE() - INTERVAL 1 DAY)
                and c.updated_at > "2019-12-29 00:00:00"
                and c.updated_at < "2019-12-29 23:59:59"


            group by hour(c.updated_at), b.sortercode
            order by c.updated_at, b.sortercode
        ');

        $errors = DB::select('
            select 
                hour(eb.created_at) as hour, 
                count(eb.id) as total
            from conveyor.error_barcode as eb 
            where 
            #    eb.created_at > curdate()
                eb.created_at > "2019-12-29 00:00:00"
            and eb.created_at < "2019-12-29 23:59:59"

            group by hour(eb.created_at)
            order by eb.created_at
        ');

       
        $time_range_data = [];

        foreach( $quota as $key => $value ){
                
   #         echo $value->code_id." " .$value->sortercode. " " .$value->title. " " . $value->hour ." ". $value->total . " " .$value->year . " " . $value->month . " " . $value->day . "<br>";
            echo $value->code_id." " .$value->sortercode. " " .$value->title. " " . $value->hour ." ". $value->total . "<br>";
   
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

        echo "<br>";

        foreach( $errors as $key => $value ){
                
            echo "0"." "."ERR". " "."Error"." ".$value->hour ." ". $value->total. "<br>";

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

        echo "<br>";
//        echo "Label <br>";

        $fix_time_range = [];
        $labels = [];

        for ($x = min($time_range_data); $x <= max($time_range_data); $x++) {
            echo date( 'g:i A', strtotime( strval($x).":00" ) ). " ". $x . "<br>";
            $time = date( 'g:i A', strtotime( strval($x).":00" ));
            $fix_time_range[] = [
                "index" => $x,
                "time" => $time
            ];

            $labels[] = $time;
        } 

          

        // echo "Series <br>";

        // foreach( $series as  $value ){
                
        //     echo $value."<br>";

        // }

 
        echo "<br>";        
        echo "Data List Plain <br>";

        $data = [];
        $series_array_obj = [];
      
        foreach( $data_list_fix as $key => $value ){
            echo $value['code_id']." " .$value['sorter_code']. " " .$value['title']. " " . $value['hour'] ." ". $value['total'] . "<br>";

            $data_series = [
                "id" =>$value['code_id'],
                "title" => $value['title']
            ];

            if(!in_array( $data_series, $series_array_obj) ){
               
                $series_array_obj[] = $data_series;

            }
        }

        // $series_index = 0;

        // foreach( $data_list_fix as $key => $value ){
            
        // }

        echo "<br>"; 
        var_dump($series_array_obj);
        echo "<br>";

        $collection_series = new Collection($series_array_obj);
        $sorted_series = $collection_series->sortBy('id');

        echo "<br>"; 
        var_dump($sorted_series);
        echo "<br>";

        // $series = [];

        // foreach( $sorted_series as $key => $value ){
        //     $series[] = $value['title'];
        // }

        $collection_series_pluck = collect($sorted_series)->pluck('title'); 
        $series = $collection_series_pluck->all();

        echo "<br>";
        echo "Series <br>";
        var_dump($series);
        echo "<br>";

        echo "<br>"; 
        echo "Labels <br>";
        var_dump($labels);
        echo "<br>";   

        // $collection_data = new Collection($data_list_fix);
        // $search_data = $collection_data->search(
        //     [
        //         "hour" => 13,
        //         "code_id" => 0,
        //     ]
        // );

        // var_dump($search_data);

        
        return null;
    }

    public function read_quota_hourly(){
        
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
