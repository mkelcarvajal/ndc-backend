@extends('layouts.app')
@section('content')
<?php 
$total=0;
$egreso=0;
$ingreso=0;
?>
@include('modal.modal_mod_bal')

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col">
                
                        <div class="card">
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        @foreach($data as $d)
                                        @if($d->tipo_mov == 'Ingreso')
                                            <?php $ingreso=$ingreso+$d->monto ?>
                                        @endif
                                    @endforeach
                                        <h3 class="text-c-green">$ {{str_replace(",",".",number_format($ingreso))}}</h3>
                                        <h6 class="text-muted m-b-0"></h6>
                                    </div>
                                    <div class="col-4 text-right">
                                        <i class="fa fa-money-plus f-28"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-green">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <p class="text-white m-b-0">Total Ingresos</p>
                                    </div>
                                    <div class="col-3 text-right">
                                        <i class="fa fa-money text-white f-16"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        @foreach($data as $d)
                                            @if($d->tipo_mov == 'Egreso')
                                                <?php $egreso=$egreso+$d->monto ?>
                                            @endif
                                        @endforeach
                                        <h3 class="text-c-red">$ {{str_replace(",",".",number_format($egreso))}}</h3>
                                        <h6 class="text-muted m-b-0"></h6>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-red">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <p class="text-white m-b-0">Total Egresos</p>
                                    </div>
                                    <div class="col-3 text-right">
                                        <i class="fa fa-money text-white f-16"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        @foreach($data as $d)
                                            <?php
                                            $total=$ingreso-$egreso
                                            ?>
                                        @endforeach
                                        <h3 class="text-c-blue">$ {{str_replace(",",".",number_format($total))}}</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-blue">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <p class="text-white m-b-0">Total Balance</p>
                                    </div>
                                    <div class="col-3 text-right">
                                        <i class="fa fa-money text-white f-16"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    {{ session()->get('message') }}
                </div>
                @endif
                @if(session()->has('message_error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session()->get('message_error') }}
                    </div>
                 @endif
                <div class="row">
                    <div class="col-sm">
                        <div class="card">
                            <div class="card-header">
                                <h5>Balance</h5>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descripción</th>
                                                <th>Monto</th>
                                                <th>Fecha Registro</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $d)
                                                @if($d->tipo_mov == 'Ingreso')
                                                <tr style="background-color: #F3FFEB">
                                                    <td>{{$d->tipo_mov}}</td>
                                                    <td>{{$d->descripcion}}</td>
                                                    <td>$ {{str_replace(",",".",number_format($d->monto))}}</td>
                                                    <td>{{date('d/m/Y H:i',strtotime($d->fecha_reg))}}</td>
                                                    <td>
                                                        <button type="button" data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-danger btn-xs"><i class="fa fa-times-circle text-white f-16"></i></button>
                                                        <button type="button" data-toggle="tooltip" data-placement="top" title="Modificar" onclick="modal_modificar_bal({{$d->id}},'{{$d->descripcion}}','{{$d->monto}}','{{$d->tipo_mov}}');" class="btn btn-warning btn-xs"><i class="fa fa-pencil text-white f-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @else
                                                <tr style="background-color: #FFECEB">
                                                    <td>{{$d->tipo_mov}}</td>
                                                    <td>{{$d->descripcion}}</td>
                                                    <td>$ {{str_replace(",",".",number_format($d->monto))}}</td>
                                                    <td>{{date('d/m/Y H:i',strtotime($d->fecha_reg))}}</td>
                                                    <td>
                                                        <button type="button" data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-danger btn-xs"><i class="fa fa-times-circle text-white f-16"></i>
                                                        </button>
                                                        <button type="button" data-toggle="tooltip" data-placement="top" title="Modificar" onclick="modal_modificar_bal({{$d->id}},'{{$d->descripcion}}','{{$d->monto}}','{{$d->tipo_mov}}');" class="btn btn-warning btn-xs"><i class="fa fa-pencil text-white f-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>   
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div id="area-chart" class="col-sm-12">
                    <div class="card">
                            <div class="card-header">
                            <h5>Line Chart</h5>
                            <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                            </div>
                            <div class="card-body">
                                <div id="area-chart">
                                    <div id="myfirstchart"></div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="js/sweetalert2@11.js"></script>
<script type="text/javascript" src="js/balance.js"></script>
<script>
    var line = Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'myfirstchart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { year: '2008', value: 20 },
    { year: '2009', value: 10 },
    { year: '2010', value: 5 },
    { year: '2011', value: 5 },
    { year: '2012', value: 20 }
  ],
  resize: true,
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Value']
});



</script>
@endsection