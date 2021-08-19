<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class socioController extends Controller
{

public function index(){

    return view('socios.agregar_socio');

}


}

?>