<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 

class Report extends Model
{
    protected $table = 'cao_fatura';
    public $timestamps = false;
    
    static function getPieChart($usuario = null, $periodo = null) {
        if(!$usuario || !$periodo) {
            return array();
        }
        $arr = explode('-', $periodo);
        $year = $arr[0];
        $month = $arr[1];

        $pie = DB::table('cao_fatura')
                ->join('cao_os'     , 'cao_fatura.co_os'     , '=', 'cao_os.co_os')
                ->select(
                       DB::raw('sum(total) as total')
                     //, DB::raw('sum(valor) as valor')
                     , DB::raw('sum((cao_fatura.valor - (cao_fatura.valor * cao_fatura.total_imp_inc/100))) as impuesto')
                     //, DB::raw('sum(((cao_fatura.valor - (cao_fatura.valor * cao_fatura.total_imp_inc/100)) * (cao_fatura.comissao_cn/100))) as comision')
                 )
                ->where('cao_os.co_usuario', 'LIKE', $usuario)
                ->where(DB::raw('YEAR(cao_fatura.data_emissao)'), '=', $year)
                ->where(DB::raw('MONTH(cao_fatura.data_emissao)'), '=', $month)
                ->get();

         return json_decode(json_encode($pie), true); ;   
    }
    
    static function getBarChart($usuario = null, $periodo = null) {
        if(!$usuario || !$periodo) {
            return array();
        }
        $arr = explode('-', $periodo);
        $year = $arr[0];
        $month = $arr[1];

        $bar = DB::table('cao_fatura')
                ->join('cao_os'     , 'cao_fatura.co_os'     , '=', 'cao_os.co_os')
                ->select(
                       DB::raw('sum(total) as total')
                     //, DB::raw('sum(valor) as valor')
                     , DB::raw('sum((cao_fatura.valor - (cao_fatura.valor * cao_fatura.total_imp_inc/100))) as impuesto')
                     , 'cao_os.co_usuario'
                 )
                ->where('cao_os.co_usuario', 'LIKE', $usuario)
                ->where(DB::raw('YEAR(cao_fatura.data_emissao)'), '=', $year)
                ->where(DB::raw('MONTH(cao_fatura.data_emissao)'), '=', $month)
                ->groupBy('cao_os.co_usuario')
                ->get();

         return json_decode(json_encode($bar), true); 
    }
    
}