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
}