<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class balanceController extends Controller
{

public function index_balance(){
    $data=DB::table('balance')->selectRaw('id,descripcion,monto,tipo_mov,fecha_reg')->get();
    
    return view('balance.index',compact('data'));
}
public function addBalance(){
    return view('balance.agregar_movimiento');
}

public function insBalance( Request $request ){

    DB::table('balance')->insert(['descripcion'=>$request['descripcion'],
                                  'monto'=>$request['monto'],
                                  'tipo_mov'=>$request['tipo'],
                                  'fecha_reg'=>date('Y-m-d H:i:s'),
                                  'usuario_reg'=>'18483767-6']);

    return redirect()->back()->with('message', 'Ingreso Correcto');

}

public function updBalance(Request $request){

    DB::table('balance')
        ->where('id',$request['id_mod'])
        ->update([
            'descripcion'=>$request['descripcion_mod'],
            'monto'=>$request['monto_mod'],
            'tipo_mov'=>$request['tipo_mod']]);

    return redirect()->back()->with('message', 'Modificado Correctamente');

}

}

?>