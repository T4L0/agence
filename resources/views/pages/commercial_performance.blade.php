@extends('layouts.default')
@section('content')
<!--<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item bottom30">
        <a class="nav-link active" id="pills-pagos-tab" data-toggle="pill" href="#pills-pagos" role="tab" aria-controls="pills-pagos" aria-expanded="true">Pagados</a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link disabled" id="pills-naopagos-tab" data-toggle="pill" href="#pills-naopagos" role="tab" aria-controls="pills-home" aria-expanded="true">No pagados</a>
    </li>

</ul> -->
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade  " id="pills-naopagos" role="tabpanel" aria-labelledby="pills-naopagos-tab">
        ...
    </div>
    <div class="tab-pane active show" id="pills-pagos" role="tabpanel" aria-labelledby="pills-pagos-tab"> 

        <form action="/commercial_performance"  method="post"> 
        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon3">Consultor</span>
                        <select id="slcConsultor" name="consultor" class="form-control" >
                           @forelse ($usuarios as $u)
                                @if ( $u->co_usuario == $consultor)
                                    <option selected value="{{ $u->co_usuario }}">{{ $u->no_usuario }}</option>
                                    
                                @else 
                                    <option value="{{ $u->co_usuario }}">{{ $u->no_usuario }}</option>
                                @endif
                            @empty
                            <option value="3">(N/A)</option>
                            @endforelse
                        </select>
                        <select id="slcPeriodos" name="periodo" class="form-control" >
                           @forelse ($periodos as $p)
                                @if ( $p->periodo == $periodo)
                                    <option selected value="{{ $u->co_usuario }}">{{ $p->periodo }}</option>
                                    
                                @else 
                                    <option value="{{ $p->periodo }}">{{ $p->periodo }}</option>
                                @endif
                            @empty
                            <option value="3">(N/A)</option>
                            @endforelse
                        </select>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span class="input-group-btn">
                        <input class="btn btn-secondary float-right btn-md" type="submit" value="Buscar">
                    </span>
                </div>
            </div>
        </div>

        </form>
                  
        
        <div class="row top30">
            
            <?php 
            $total = 0;
            $valor = 0;
            $vi = 0;
            $vvic = 0;
            $empresa = '';
            if(isset($facturas[0]->no_fantasia)) {
                $empresa = $facturas[0]->no_fantasia; ?>
            <table class="table">
              <thead>
                <tr>
                  <th colspan=8>{{ $empresa }}</th>
                </tr>
                <tr>
                  <th>Sistema Web</th>
                  <th>OS</th>
                  <th>NF</th>
                   <th>Emisión</th>
                  <th>Total</th>
                  <th>Valor (V)</th>
                  <th>V-Imp (I)</th>
                  <th>(V–(V*I))*C</th>
                </tr>
              </thead>
              <tbody>
            @foreach($facturas as $f)
                @if ($empresa != $f->no_fantasia)
                     <?php $empresa = $f->no_fantasia ?>
              </tbody>
            </table>
            <table class="table table-sm">
              <thead>
                <tr>
                   <th colspan=8>{{ $empresa }}</th>
                </tr>
                <tr>
                  <th>Sistema Web</th>
                  <th>OS</th>
                  <th>NF</th>
                   <th>Emisión</th>
                  <th>Total</th>
                  <th>Valor (V)</th>
                  <th>V-Imp (%)</th>
                  <th>(V–(V*I))*C</th>
                </tr>
              </thead>
              <tbody>
        
                @endif
                
                <?php   $total += $f->total;
                        $valor += $f->valor;
                        $vi += $f->impuesto;
                        $vvic += $f->comision;

                
                ?>
                 <tr>
                    <td>{{ $f->no_sistema }}</td>
                    <td>{{ $f->ds_os }}</td>
                    <td>{{ $f->num_nf }}</td>
                    <td>{{ $f->data_emissao }}</td>
                    <td>{{ $f->total }}</td>
                    <td>{{ $f->valor }}</td>
                    <td>{{ $f->impuesto }}</td>
                    <td>{{ $f->comision }}</td>
                </tr>
             @endforeach
              </tbody>
            </table>
            
            <?php } else {
              echo "No data" ;
            } ?>
        </div>
        
        
        @isset($salario['no_usuario'])
            <div class="card top30">
            <div class="card-header">
            {{ $salario['no_usuario'] }}
            </div>
            <div class="card-body">
            <blockquote class="blockquote mb-0">
     
            <footer class="blockquote-footer">Salario Bruto <cite title="Source Title">R$ {{ $salario['brut_salario']  }} </cite></footer>
            <footer class="blockquote-footer">Salario Liquido <cite title="Source Title">R$ {{ $salario['liq_salario'] }} </cite></footer>
            </blockquote>
            </div>
            </div>
        @endisset
        
        <div class="row top30">

            <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#PieChart">Gráfico Torta</button>
        </div>
        
      
    </div>
  </div>
</div>

 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- Modal Pie Chart -->
<div class="modal modal-xl fade " id="PieChart" tabindex="-1" role="dialog" aria-labelledby="PieChartModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="PieChartModalLabel">Gráfico Torta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
          <script type="text/javascript">

          // Load the Visualization API and the corechart package.
          google.charts.load('current', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.charts.setOnLoadCallback(drawChart);

          // Callback that creates and populates a data table,
          // instantiates the pie chart, passes in the data and
          // draws it.
          function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Etiqueta');
            data.addColumn('number', 'Pesos');
            data.addRows([
              
              ['Total', {{$chart_pie[0]['total']}}],
              ['Impuestos', {{$chart_pie[0]['impuesto']}}],
              
            ]);
            // Set chart options
            var options = {'title':'Total vs Impuesto',
                           'width':450,
                           'height':500};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('PieChartGraph'));
            chart.draw(data, options);
          }
        </script>
        <div id="PieChartGraph"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
<!-- End Modal Pie Chart -->
@stop