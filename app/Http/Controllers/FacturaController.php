<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Factura;
use App\Usuario;
use App\Salario;
use App\Periodo;
use App\Report;

class FacturaController extends Controller
{
   
   /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $consultor = null;
        $periodo = null;
        
        $request = $request->all();
        
        $periodos =  Periodo::getPeriodos();
        $usuarios = Usuario::getListaUsuarioPermiso();
        
        if(isset($request['consultor'])) {
           $consultor = $request['consultor'];
        }
        if(isset($request['periodo'])) {
           $periodo = $request['periodo'];
        }
        
        
        
        
        if (is_null($consultor)) {
            $consultor = $usuarios[0]->co_usuario;
            $consultor = 'anapaula.chiodaro';
        }
        if (is_null($periodo)) {
            $periodo = $periodos[0]->periodo;
            $periodo = '2007-8';
        }

        $facturas   = Factura::getTablaFacturas($consultor, $periodo);
        $chart_pie  = Report::getPieChart($consultor, $periodo);   
        $chart_bar  = Report::getBarChart($consultor, $periodo);   
        $salario    = Salario::getSalario($consultor);
        
 
        return view('pages.commercial_performance')
           -> with('consultor', $consultor)
           -> with('periodo', $periodo)        
           -> with('usuarios', $usuarios)
           -> with('periodos', $periodos)
           -> with('facturas', $facturas)
           -> with('chart_pie', $chart_pie)
           -> with('chart_bar', $chart_bar)
           -> with('salario', $salario);
    }
}
