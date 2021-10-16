@extends('layouts.app')

@section('content')
<link href="css/oht.css" rel="stylesheet" type="text/css" />

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
                                                                  <div class="col-2 align-items-start">
                                                                      <br><br>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1{{$contador}}">
                                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                                          Default radio
                                                                        </label>
                                                                    </div>  
                                                                      <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2{{$contador}}" checked>
                                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                                          Default checked radio
                                                                        </label>
                                                                      </div>
                                                                      <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3{{$contador}}">
                                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                                          Default radio
                                                                        </label>
                                                                      </div>
                                                                      <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4{{$contador}}">
                                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                                          Default radio
                                                                        </label>
                                                                      </div>
                                                                  </div>
                                                                  @if($p->imagen_pregunta == '0')
                                                                  @else
                                                                  <div class="col-2">
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
