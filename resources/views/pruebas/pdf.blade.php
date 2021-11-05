<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Reporte Prueba OHT - Electrica</title>


    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #EBFFEF;
            color:black;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" >

                <h2>{{$data->nombre_r}} {{$data->apellido_r}}</h2>
                <b>RUT: {{$data->rut_r}}</b> 
                    <br /><br />
                <b>Fecha Prueba: {{date('d/m/Y',strtotime($data->fecha_r))}}</b>

                </td>
            <td align="center">
                
                <img src="loginpu/img/ndc.png" style="margin-left: 80px;">

            </td>
            <td align="right" style="width: 40%;">

                <h3>NDC PERSSO GROUP</h3>
                <pre>
                    https://ndc.cl
                    Baquedano 239, Oficina 203, Antofagasta
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">

    <center><h2>{{$data->nombre_e}}</h2></center>
    <table class="table" style="  margin-left: auto; margin-right: auto;" width="70%">
        <thead style="background-color:#FDE59B">
            <tr><th align="center"><h2>Rendimiento en su Cargo</h2></th></tr>
        </thead>
        <tbody>
            <tr style="background-color: #FFFAEB">
                <td align="center"><h2>{{round($rendimiento)}}%</h2></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%" class="table table-bordered" >
        <thead style="background-color:#ebffef">
        <tr>
            <th style="background-color: #d0e1f3">Total Preguntas</th>
            <th style="background-color: #d0e1f3">Correctas</th>
            <th style="background-color: #d0e1f3">Incorrectas</th>
        </tr>
        </thead>
        <tbody style="background-color: #F4F5F5">
        <tr>
            <td align="center"><h3>{{$total_preguntas}}</h3></td>
            <td align="center"><h3>{{$total}}</h3></td>
            <td align="center"><h3>{{$incorrectas}}</h3></td>

        </tr>
        </tbody>
    </table>

  
    <table style="width:100%">
        <tr style="background-color: #d0e1f3">
            <th></th>
            <th>Resp. Correctas</th>
            <th>Resp. Totales</th>
            <th>Rendimiento como Electromecánico</th>
        </tr>
        <tr>
            <th style="background-color: #fabbbc">Categoria A</th>
            <td align="center" style="background-color:#F4F5F5">{{$categoria_a}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{$a}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{round($porc_a)}}%</td>

        </tr>
        <tr>
            <th style="background-color: #d5f1bf">Categoria B</th>
            <td align="center"  style="background-color:#F4F5F5">{{$categoria_b}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{$b}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{round($porc_b)}}%</td>

        </tr>
        <tr>
            <th style="background-color: #d0e1f3">Categoria C</th>
            <td align="center"  style="background-color:#F4F5F5">{{$categoria_c}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{$c}}</td>
            <td align="center"  style="background-color:#F4F5F5">{{round($porc_c)}}%</td>

        </tr>
      </table>

</div>
<br>
<br>
<center>
        <h3>Comparación de Rendimiento (%)</h3>
        <img  width="80%" height="300" src="https://quickchart.io/chart?c=
            {
                type:'bar',
                options: {
                    plugins: {
                      datalabels: {
                        anchor: 'center',
                        align: 'center',
                        color: 'black',
                        font: {
                          weight: 'normal',
                        },
                      },
                    },
                  },
                title:'Comparación de Rendimiento',
                data:{labels:['Categoria A','Categoria B','Categoria C'],
                    datasets:[{label:'',
                        data:[{{round($porc_a)}},{{round($porc_b)}},{{round($porc_c)}}],
                        backgroundColor:['rgb(250,187,188)','rgb(213,241,191)','rgb(208,225,243)']
                    }]
                },

            }">
</center>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; NDC PERSSO GROUP - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                
            </td>
        </tr>

    </table>
</div>
</body>

</html>
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [0, 10, 5, 2, 20, 30, 45]
        }]
    },

    // Configuration options go here
    options: {}
});
</script>
@endsection

