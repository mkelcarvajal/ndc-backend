<style>
    html {
        font-family: Arial, Helvetica, sans-serif;
    }
    table,th,td {
        font-size: 10px;
        border:solid 1px;
        width: 100%;
        border-collapse: collapse;

    }


</style>
<html >
    <img src="img/ndc.JPG" height="50" width="100">
    <br>
    <center><b>CERTIFICADO DE ASISTENCIA Y APROBACIÓN Nº {{$data[0]->cod_certificado}} - <?php echo date("Y") ?></b></center>
    <br>
    <table >
        <thead>
            <tr>
                <th style="text-align: left">
                    <b>NOMBRE DEL CURSO</b>
                </th>
                <th colspan="3">
                    <b>{{$data[0]->curso}}</b>
                </th>
                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>DURACIÓN</b></td>
                <td style="text-align: center" colspan="3">{{$data[0]->horas_curso}} HORAS CRONOLÓGICAS</td>
            </tr>
            <tr>
                <td><b>CÓDIGO SENCE</b></td>
                <td style="text-align: center" colspan="3">SIN CÓDIGO SENCE</td>
            </tr>
            <tr>
                <td><b>LUGAR DE EJECUCIÓN</b></td>
                <td style="text-align: center" colspan="3">PLATAFORMA VIRTUAL MICROSOFT TEAMS</td>
            </tr>
            <tr>
                <td><b>FECHA INICIO</b></td>
                <td style="text-align: center">{{date("d/m/Y",strtotime($data[0]->fecha_ini))}}</td>
                <td style="text-align: center"><b>FECHA TERMINO</b></td>
                <td style="text-align: center">{{date("d/m/Y",strtotime($data[0]->fecha_fin))}}</td>
            </tr>
            <tr>
                <td><b>HORA INICIO</b></td>
                <td style="text-align: center">08:00</td>
                <td style="text-align: center"><b>HORA TERMINO</b></td>
                <td style="text-align: center">17:00</td>
            </tr>
            <tr>
                <td><b>FACILITADOR OTEC - CESSO</b></td>
                <td style="text-align: center" colspan="3">PATRICIO TRIGO</td>
            </tr>
            <tr>
                <td><b>ORGANISMO EJECUTOR - OTEC</b></td>
                <td style="text-align: center" colspan="3">NDC PERSSO GROUP SPA RUT N°76.881.275-6</td>
            </tr>
            <tr>
                <td><b>OC Nº</b></td>
                <td style="text-align: center" colspan="3">**</td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 10px;">
        Se extiende a <b>CODELCO CHILE, DIVISIÓN SALVADOR, RUT 61.704.000-K </b>, 
        el presente certificado de asistencia y calificación del siguiente personal, 
        en el curso denominado: <b>ENTRENAMIENTO LABORAL EN SEGURIDAD Y SALUD OCUPACIONAL MINERA/NCH 3262</b>, 
        on fecha de inicio el 18 de abril de 2022 y de término el 20 de abril 2022, con una duración de 24 horas cronológicas.
    </p>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:30px" >N°</th>
                <th rowspan="2" style="width:300px;">NOMBRE COMPLETO</th>
                <th rowspan="2" style="width: 65px;">RUN</th>
                <th rowspan="2" style="width: 60px">SAP</th>
                <th rowspan="2" style="width: 80px">ORGANIZACIÓN</th>
                <th rowspan="2" style="width: 70px">ASISTENCIA</th>
                <th colspan="2" style="width: 80px;">EVALUACIONES (%)</th>
                <th rowspan="2" style="width: 100px">EVALIACIÓN FINAL (%)</th>
                <th rowspan="2" style="width: 80px">CALIFICACIÓN</th>
            </tr>
            <tr>
                <th style="width:50px;">1</th>
                <th style="width: 50px;">2</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            <?php $cont = 1; ?>
            @foreach($data as $d)
                <tr>
                    <td>{{$cont}}</td>
                    <td>{{$d->nombre}}</td>
                    <td>{{$d->rut}}</td>
                    <td>{{$d->sap}}</td>
                    <td>{{$d->empresa}}</td>
                    <td>{{$d->asistencia}}%</td>
                    <td>{{$d->nota_ini}}%</td>
                    <td>{{$d->nota_fin}}%</td>
                    <td>{{$d->nota_fin}}%</td>
                    <td>{{$d->calificacion}}</td>
                </tr>
                <?php $cont++; ?>
            @endforeach
        </tbody>
    </table>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
</html>
