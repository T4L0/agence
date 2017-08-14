<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 

class Periodo extends Model
{
    protected $table = 'cao_fatura';
    public $timestamps = false;
    

    static function getPeriodos() {

        $periodo = DB::table('cao_fatura')
                ->select( 
                    DB::raw('CONCAT(YEAR(data_emissao),"-", MONTH(data_emissao)) as periodo')
                )
                ->groupBy( 
                    DB::raw('CONCAT(YEAR(data_emissao),"-", MONTH(data_emissao))')
                )
                ->get();
       
        return  $periodo;
    }
    
}