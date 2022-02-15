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

    .thing2{
    box-shadow: 0 15px 30px 0 rgba(0,0,0,0.11),
        0 5px 15px 0 rgba(0,0,0,0.08);
    background-color: #ffffff;  
    transition: border-left 200ms ease-in-out, padding-left 200ms ease-in-out;
    }
    .thing2:hover {
    padding-left: 0.6rem;
    border-left: 0.6rem solid #EFB643;
    }

</style>

@if($paciente->estado == 04)
<div class="card thing" style="cursor: pointer " onclick="modal_ingreso('{{$paciente->nombre}}','{{$paciente->pacnum}}','{{$paciente->cod_cama}}');">
    <div class="card-header" style="color:#00a29b "> <h4>{{$paciente->piso}}</h4> <i class="fas fa-bed" style="color:#00a29b "></i> {{$paciente->cama}}</div>
    <div class="card-body ">
    <h5 class="card-title">{{$paciente->nombre}}</h5>
    <p class="card-text">RUT: {{$paciente->rut}}<br> Ficha: {{round($paciente->ficha)}}</p>
    </div>
</div>
@elseif($paciente->estado == 07)
<div class="card thing2" style="cursor: pointer " >
    <div class="card-header" style="color:#EFB643 "> <h4>Paciente en Pre-Ingreso</h4> <i class="fas fa-bed" style="color:#EFB643 "></i> </div>
    <div class="card-body ">
    <h5 class="card-title">{{$paciente->nombre}}</h5>
    <p class="card-text">RUT: {{$paciente->rut}}<br> Ficha: {{round($paciente->ficha)}}</p>
    </div>
</div>
@endif