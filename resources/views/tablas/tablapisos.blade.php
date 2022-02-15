<style>
    p:last-child {
        margin: 0px
    }

    a {
        color: #71748d
    }

    a:hover {
        color: #ff407b;
        text-decoration: none
    }

    a:active,
    a:hover {
        outline: 0;
        text-decoration: none
    }

    .btn-secondary {
        color: #fff;
        background-color: #ff407b;
        border-color: #ff407b
    }

    .btn {
        font-size: 14px;
        padding: 9px 16px;
        border-radius: 2px
    }

    .tab-vertical .nav.nav-tabs {
        float: left;
        display: block;
        margin-right: 0px;
        border-bottom: 0
    }

    .tab-vertical .nav.nav-tabs .nav-item {
        margin-bottom: 6px
    }

    .tab-vertical .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        background: #fff;
        padding: 17px 49px;
        color: #fff;
        background-color: #00CCC2;
        -webkit-border-radius: 4px 0px 0px 4px;
        -moz-border-radius: 4px 0px 0px 4px;
        border-radius: 4px 0px 0px 4px
    }

    .tab-vertical .nav-tabs .nav-link.active {
        color: #00CCC2;
        background-color: #D6FFFD !important;
        border-color: transparent !important
    }

    .tab-vertical .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 4px !important;
        border-top-right-radius: 0px !important
    }
    .tab-vertical .tab-content {
        overflow: auto;
        -webkit-border-radius: 0px 4px 4px 4px;
        -moz-border-radius: 0px 4px 4px 4px;
        border-radius: 0px 4px 4px 4px;
        background: #fff;
        padding: 30px
    }
    .thing {
    box-shadow: 0 15px 30px 0 rgba(0,0,0,0.11),
        0 5px 15px 0 rgba(0,0,0,0.08);
    background-color: #ffffff;  
    transition: border-left 200ms ease-in-out, padding-left 200ms ease-in-out;
    }
    .thing:hover {
    padding-left: 0.6rem;
    border-left: 0.6rem solid #00CCC2;
    }
</style>
<div class="container d-flex justify-content-center mt-20">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-5">
        <div class="tab-vertical">
            <ul class="nav nav-tabs" id="myTab3" role="tablist">
                @foreach($salas as $s)
                <li class="nav-item"> 
                    <a  class="nav-link" id="home-vertical-tab" data-toggle="tab" href="#{{$s->ID_Sala}}" role="tab" aria-controls="home" aria-selected="true">
                    <i class="fas fa-clinic-medical"></i>
                    Sala - {{$s->SAL_NumPieza}}</a>
                </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent3">
                @foreach($salas as $s)
                <div class="tab-pane fade show" id="{{$s->ID_Sala}}" role="tabpanel" aria-labelledby="home-vertical-tab">
                    @foreach($data as $d)
                        @if($s->ID_Sala == $d->IDSala)
                            @if($d->ESTADO == 'OCUPADA')
                                <div class="card thing" style="cursor: pointer " onclick="modal_ingreso('{{$d->Nombre}}','{{$d->NumPaciente}}','{{$d->SER_OBJ_Codigo}}','{{$d->dh}}');">
                                    <div class="card-header" style="color:#00a29b ">
                                            @if(in_array($d->NumPaciente, $entrega))
                                                <div style="float: right;" >
                                                    <i class="fas fa-check-circle" style="color:#15CB15;"></i> <label style="color:#15CB15;"> Entrega Realizada</label> 
                                                </div>
                                            @else
                                                <div style="float: right;" >
                                                    <i class="fas fa-times-circle" style="color:#fd7e72;"></i> <label style="color:#fd7e72;"> Entrega No Realizada</label> 
                                                </div>
                                            @endif
                                    <i class="fas fa-bed" style="color:#00a29b "></i> {{$d->NombreCama}}</div>
                                    <div class="card-body ">
                                    <h5 class="card-title">{{$d->Nombre}}</h5>
                                    <p class="card-text">RUT: {{$d->RUT}}<br> Ficha: {{$d->Ficha}}</p>
                                    </div>
                                </div>
                            @elseif($d->ESTADO == 'BLOQUEADA')
                                <div class="card border-default">
                                    <div class="card-header">{{$d->NombreCama}}</div>
                                    <div class="card-body text-default">
                                    <h5 class="card-title">Cama Bloqueada</h5>
                                    </div>
                                </div>
                            @elseif($d->ESTADO == 'DISPONIBLE')
                                <div class="card border-success">
                                    <div class="card-header">{{$d->NombreCama}}</div>
                                    <div class="card-body text-success">
                                    <h5 class="card-title">Cama Disponible</h5>
                                    </div>
                                </div>
                            @elseif($d->ESTADO == 'RESERVADA')
                            <div class="card border-warning">
                                <div class="card-header">{{$d->NombreCama}}</div>
                                <div class="card-body text-warning">
                                <h5 class="card-title">Cama Disponible</h5>
                                </div>
                            </div>
                            @endif
                        @endif
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

