<?php

namespace App\Http\Controllers\conveyor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GeneratorController extends Controller
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
        $faker = Faker::create('Faker\Provider\Barcode');
        $char_fake = Faker::create('Faker\Provider\Base');

        $barcode = false;
        $batch_id = str_shuffle(substr($faker->ean8,4) .''.  substr($faker->ean8,3) .''. substr($faker->ean8,6) .''.  strtoupper($char_fake->lexify('???'))); 

        try {
            
        $batch_barcode = DB::insert('insert into batch_barcode (batch_id, description, sortercode, total_rows,created_at) values (?, ?, ?, ?, ?)',
            [
                $batch_id, 
                $request->description, 
                $request->sortercode,
                $request->range,
                Carbon::now()
            ]
        );

        $x = 1;
        while($x <= $request->range ) {
            
            try {
              
                $code = substr($faker->ean8,4) .''.  substr($faker->ean8,3).''.  strtoupper($char_fake->lexify('???')) .''. substr($faker->ean8,6);
                  
                $barcode = DB::insert('insert into barcode ( barcode_id, batch_id, status, created_at ) values ( ?, ?, ?, ?)',
                    [ 
                        str_shuffle(substr($code,3)),
                        $batch_id,
                        'Unscanned',
                        Carbon::now()
                    ]
                );

                $x++;
              
            } catch (QueryException $e) {
                
            }
        } 

        } catch (QueryException $e) {

            return response()->json(false);
        }

        return response()->json($barcode);					
    }

    public function read_batch(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;
        
        $batch_list = DB::select(
            'select * from batch_barcode where deleted_at is null and batch_id like ? order by created_at desc limit ?,?',
            [
                '%'.$request->search.'%',
                $from,
                $limit
            ]
        );

        return response()->json($batch_list);					
    }

    public function read_scanned(Request $request)
    {
        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $scanned_list = DB::select(
            'select 
                c.barcode_id, 
                c.created_at, 
                c.status,
                b.sortercode
            from conveyor.barcode as c
            left join conveyor.batch_barcode as b on b.batch_id = c.batch_id
            where
                c.status = "Scanned" 
            and c.deleted_at is null 
            and c.barcode_id like ? 
            order by c.id desc 
            limit ?,?',
            [
                '%'.$request->search.'%',
                $from,
                $limit
            ]
        );

        return response()->json($scanned_list);					
    }

    public function read_unscanned(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $unscanned_list = DB::select(
            'select 
                c.barcode_id, 
                c.created_at, 
                c.status,
                b.sortercode
            from conveyor.barcode as c 
            left join conveyor.batch_barcode as b on b.batch_id = c.batch_id
            where 
                    c.status = "Unscanned" 
                and c.deleted_at is null 
                and c.barcode_id like ? 
            order by c.id desc 
            limit ?,?',
            [
                '%'.$request->search.'%',
                $from,
                $limit
            ]
        );

        return response()->json($unscanned_list);					
    }

    public function read_errors(Request $request)
    {

        $from = ($request->page - 1) * $request->range;
        $limit = $request->range;

        $errors_list = DB::select(
            'select id, barcode, created_at FROM conveyor.error_barcode where deleted_at is null and barcode like ? 
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

    public function read_batch_count(Request $request)
    {
        $batch_count = DB::select(
            'select count(*) as totalrows from batch_barcode where deleted_at is null and batch_id like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($batch_count[0]);					        
    }

    public function read_scanned_count(Request $request)
    {
        $scanned_count = DB::select(
            'select count(*) as totalrows from barcode where status = "Scanned" and deleted_at is null and barcode_id like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($scanned_count[0]);		
    }

    public function read_unscanned_count(Request $request)
    {
        $unscanned_count = DB::select(
            'select count(*) as totalrows from barcode where status = "Unscanned" and deleted_at is null and barcode_id like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($unscanned_count[0]);					
    }

    public function read_errors_count(Request $request)
    {
        $unscanned_count = DB::select(
            'select count(*) as totalrows from error_barcode where deleted_at is null and barcode like ?',
            [
                '%'.$request->search.'%'
            ]
        );

        return response()->json($unscanned_count[0]);					
    }

    public function update(Request $request)
    {
        $batch = DB::update('update batch_barcode set description = ?, updated_at = NOW() where batch_id = ?', 
            [
                $request->description,
                $request->batch_id
            ]
        );

        return response()->json($batch);					
    }

    public function delete(Request $request)
    {
        $batch = DB::update('update batch_barcode set  deleted_at = NOW() where batch_id = ?', 
            [
                $request->batch_id
            ]
        );

        if($batch){
            
            $barcode = DB::update('update barcode set  deleted_at = NOW() where batch_id = ?', 
                [
                    $request->batch_id
                ]
            );

            return response()->json($barcode);    
        }

        return response()->json(0);					
    
    }

    public function errors_delete(Request $request)
    {
        $error = DB::update('update error_barcode set  deleted_at = NOW() where id = ?', 
            [
                $request->errors_id
            ]
        );

        if($error){
            return response()->json($error);    
        }

        return response()->json(0);					
    
    }
}
