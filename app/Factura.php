<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 

class Factura extends Model
{
    protected $table = 'cao_fatura';
    public $timestamps = false;

    static function getTablaFacturas($usuario = null, $periodo = null) {
        // die($periodo);
        if(!$usuario || !$periodo) {
            return array();
        }
        $arr = explode('-', $periodo);
        $year = $arr[0];
        $month = $arr[1];
        
        
        //where YEAR(factura.data_emissao) = 2007 and MONTH(factura.data_emissao) = 9
        $facturas = DB::table('cao_fatura')
                ->join('cao_cliente', 'cao_fatura.co_cliente', '=', 'cao_cliente.co_cliente')
                ->join('cao_sistema', 'cao_fatura.co_sistema', '=', 'cao_sistema.co_sistema')
                ->join('cao_os'     , 'cao_fatura.co_os'     , '=', 'cao_os.co_os')
                ->select(
                       'cao_fatura.num_nf', 'cao_fatura.total', 'cao_fatura.valor', 'cao_fatura.data_emissao', 'cao_fatura.comissao_cn', 'cao_fatura.total_imp_inc'
                     , DB::raw('ROUND(cao_fatura.valor - (cao_fatura.valor * cao_fatura.total_imp_inc/100), 2) AS impuesto')
                     , DB::raw('ROUND(((cao_fatura.valor - (cao_fatura.valor * cao_fatura.total_imp_inc/100)) * (cao_fatura.comissao_cn/100)), 2) AS comision')
                     , 'cao_cliente.no_fantasia'
                     , 'cao_sistema.no_sistema'
                     , 'cao_os.co_usuario', 'cao_os.ds_os'
                 )
                ->where('cao_os.co_usuario', 'LIKE', $usuario)
                ->where(DB::raw('YEAR(cao_fatura.data_emissao)'), '=', $year)
                ->where(DB::raw('MONTH(cao_fatura.data_emissao)'), '=', $month)
                ->orderBy('cao_cliente.no_fantasia')
                ->get();
         return $facturas;   
    }
    
}