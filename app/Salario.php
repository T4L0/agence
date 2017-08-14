<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 

class Salario extends Model
{
    protected $table = 'cao_salario';
    public $timestamps = false;
    

    static function getSalario($usuario = null) {
        if(!$usuario) {
           return 0;
        }
        $salario = DB::table('cao_salario')
                ->join('cao_usuario', 'cao_salario.co_usuario', '=', 'cao_usuario.co_usuario')
                ->select('cao_usuario.no_usuario','cao_salario.co_usuario', 'brut_salario', 'liq_salario')
                ->where('cao_salario.co_usuario', 'LIKE', $usuario)
                ->get();

        $salario = json_decode(json_encode($salario),true);
        return isset($salario[0])?$salario[0]:null;
    }
    
}