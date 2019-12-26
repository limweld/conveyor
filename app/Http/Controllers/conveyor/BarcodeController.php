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


class BarcodeController extends Controller
{
    public function __construct()
    {
        AuthenticationController::check_session_backend();
    }

    public function create(Request $request)
    {   $data_obj = "touch";

        return $data_obj;
    }

    public function read(Request $request,$id)
    {  

        $barcodes = DB::select('select barcode_id from barcode where status="Unscanned" and deleted_at is null and batch_id = ?', [$id]);
        $batch_barcode = DB::select('select batch_id, sortercode, created_at from batch_barcode where deleted_at is null and batch_id = ?', [$id]);

        if( $batch_barcode != null ){

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set default header data
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,   $batch_barcode[0]->sortercode.' '.$batch_barcode[0]->batch_id.' '. $batch_barcode[0]->created_at, 'Conveyor @ ProjectDesign 2020');

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, 16, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(8);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, 0);

            $pdf->AddPage();

            $style = array(
                'position' => '',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => true,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 0,
            );

            foreach ($barcodes as $key=>$barcode) {
                $data_obj = [];
                $data_obj = $barcode;

                $row = $key + 1;

                $pdf->write1DBarcode($data_obj->barcode_id, 'C39', '', '', '', 21 , 0.28, $style, $row%3 == 0 ? 'N' : 'T');
            }


            $pdf->Output('Barcodes', 'I');

        }
                    
    }

    public function update(Request $request)
    {   $data_obj = null;

        return $data_obj;
    }

    public function delete(Request $request)
    {   $data_obj = null;

        return $data_obj;
    }
}
