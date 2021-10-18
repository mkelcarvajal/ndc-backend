@extends('layouts.app')

@section('content')
<link href="css/oht.css" rel="stylesheet" type="text/css" />
<style>

.funkyradio div {
  clear: both;
  overflow: hidden;
}

.funkyradio label {
  width: 100%;
  border-radius: 3px;
  border: 1px solid #D1D3D4;
  font-weight: normal;
}

.funkyradio input[type="radio"]:empty,
.funkyradio input[type="checkbox"]:empty {
  display: none;
}

.funkyradio input[type="radio"]:empty ~ label,
.funkyradio input[type="checkbox"]:empty ~ label {
  position: relative;
  line-height: 2.5em;
  text-indent: 3.25em;
  margin-top: 2em;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.funkyradio input[type="radio"]:empty ~ label:before,
.funkyradio input[type="checkbox"]:empty ~ label:before {
  position: absolute;
  display: block;
  top: 0;
  bottom: 0;
  left: 0;
  content: '';
  width: 2.5em;
  background: #D1D3D4;
  border-radius: 3px 0 0 3px;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
  color: #888;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #C2C2C2;
}

.funkyradio input[type="radio"]:checked ~ label,
.funkyradio input[type="checkbox"]:checked ~ label {
  color: #777;
}

.funkyradio input[type="radio"]:checked ~ label:before,
.funkyradio input[type="checkbox"]:checked ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #333;
  background-color: #ccc;
}

.funkyradio input[type="radio"]:focus ~ label:before,
.funkyradio input[type="checkbox"]:focus ~ label:before {
  box-shadow: 0 0 0 3px #999;
}

.funkyradio-default input[type="radio"]:checked ~ label:before,
.funkyradio-default input[type="checkbox"]:checked ~ label:before {
  color: #333;
  background-color: #ccc;
}

.funkyradio-primary input[type="radio"]:checked ~ label:before,
.funkyradio-primary input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #337ab7;
}

.funkyradio-success input[type="radio"]:checked ~ label:before,
.funkyradio-success input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5cb85c;
}

.funkyradio-danger input[type="radio"]:checked ~ label:before,
.funkyradio-danger input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #d9534f;
}

.funkyradio-warning input[type="radio"]:checked ~ label:before,
.funkyradio-warning input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #f0ad4e;
}

.funkyradio-info input[type="radio"]:checked ~ label:before,
.funkyradio-info input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5bc0de;
}

</style>
<div class="row page-title">
    <div class="col-12">
         {{-- <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <select class="select2" id="select_prueba" name="select_prueba" >
                                    <option>Seleccione una prueba</option>
                                @foreach($pruebas as $p)
                                    <option value="{{$p->id}}">{{$p->nombre_prueba}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                    </div>
                </div>
         </div>
         <br> --}}
         <div class="card">
            <div class="card-body">
                        <div class="container col-12">
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-12
                                    col-lg-12 col-xl-12 text-center p-0 mt-3 mb-2">
                                    <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                                        <form id="form">
                                       
                                            <ul id="progressbar">
                                                <li class="active" id="step0">
                                                    <strong>Inicio</strong>
                                                </li>
                                                <?php $num=1 ?>
                                                @foreach($topicos as $t)
                                                <li id="step{{$num}}"><strong>Topico {{$num}}</strong></li>
                                                <?php $num++ ?>
                                                @endforeach
                                            </ul>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div> <br>
                                            <fieldset>
                                                <h2>Bienvenido! recuerda tomarte tu tiempo!</h2>
                                                <input type="button" name="next-step" 
                                                    class="next-step" value="Siguiente" />
                                            </fieldset>
                                            @foreach($topicos as $t)
                                            <fieldset>
                                                <h2>{{$t->nombre}}</h2>
                                                <br><br>
                                                <?php $contador=1 ?>
                                                
                                                @foreach($preguntas as $p)
                                                
                                                    @if($t->id == $p->id_topico)
                                                    <div class="card">
                                                        <div class="card-header" style="color:white; background-color:green">
                                                            {{$contador}}.- {{$p->texto_pregunta}} 
                                                        </div>
                                                        <div class="card-body">
                                                          <blockquote class="blockquote mb-0">
                                                              <div class="row d-flex justify-content-center">
                                                                  <div class="col-8 align-items-start">
                                                                      <?php $letra='a'; ?>
                                                                      <?php $numero=0; ?>

                                                                    @foreach($alternativas as $a)
                                                                        @if($p->id == $a->id)
                                                                        <div class="funkyradio">
                                                                            <div class="funkyradio-success">
                                                                                <input type="radio" name="radio[{{$numero}}]"  />
                                                                                <label for="radio[{{$numero}}]">{{$letra}}.- {{$a->texto_alternativa}} </label>
                                                                            </div>
                                                                        </div>  
                                                                            <?php $letra++ ?>

                                                                        @endif

                                                                    @endforeach

                                                                  </div>
                                                                  @if($p->imagen_pregunta == '0')
                                                                  @else
                                                                  <div class="col-4">
                                                                    <img src="img_pruebas/{{$p->imagen_pregunta}}" style="float: right" width="200" height="200">
                                                                  </div> 
                                                                  @endif
                                                                  
                                                              </div>
                                                            {{-- <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer> --}}
                                                          </blockquote>
                                                        </div>
                                                      </div>
                                                    @endif
                                                    <?php $contador++ ?>
                                                @endforeach
                                                <input type="button" name="next-step" 
                                                    class="next-step" value="Siguiente" />
                                                <input type="button" name="previous-step" 
                                                    class="previous-step" 
                                                    value="Anterior" />
                                            </fieldset>
                                            @endforeach
                                            <fieldset>
                                                <div class="finish">
                                                    <h2 class="text text-center">
                                                        <strong>¡Terminado!</strong>
                                                    </h2>
                                                </div>
                                                <input type="button" name="previous-step" 
                                                    class="previous-step" 
                                                    value="Anterior" />
                                                    <input type="button" name="next-step" 
                                                    class="next-step" value="Finalizar" />
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>     
    </div>
</div>
@section('script')

<script>
    $(document).ready(function() {
        $('.select2').select2({
            language: {
                    noResults: function() {
                    return "No hay resultados";        
                    },
                    searching: function() {
                    return "Buscando..";
                    }
                }
        });
    });
    $(document).ready(function () {
	var currentGfgStep, nextGfgStep, previousGfgStep;
	var opacity;
	var current = 1;
	var steps = $("fieldset").length;

	setProgressBar(current);

	$(".next-step").click(function () {


		currentGfgStep = $(this).parent();
		nextGfgStep = $(this).parent().next();

		$("#progressbar li").eq($("fieldset")
			.index(nextGfgStep)).addClass("active");

		nextGfgStep.show();
		currentGfgStep.animate({ opacity: 0 }, {
			step: function (now) {
				opacity = 1 - now;

				currentGfgStep.css({
					'display': 'none',
					'position': 'relative'
				});
				nextGfgStep.css({ 'opacity': opacity });
			},
			duration: 500
		});
		setProgressBar(++current);

	});

	$(".previous-step").click(function () {

		currentGfgStep = $(this).parent();
		previousGfgStep = $(this).parent().prev();

		$("#progressbar li").eq($("fieldset")
			.index(currentGfgStep)).removeClass("active");

		previousGfgStep.show();

		currentGfgStep.animate({ opacity: 0 }, {
			step: function (now) {
				opacity = 1 - now;

				currentGfgStep.css({
					'display': 'none',
					'position': 'relative'
				});
				previousGfgStep.css({ 'opacity': opacity });
			},
			duration: 500
		});
		setProgressBar(--current);
	});

	function setProgressBar(currentStep) {
		var percent = parseFloat(100 / steps) * current;
		percent = percent.toFixed();
		$(".progress-bar")
			.css("width", percent + "%")
	}

	$(".submit").click(function () {
		return false;
	})
});



</script>
@endsection
@endsection
