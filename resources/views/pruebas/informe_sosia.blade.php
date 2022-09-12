@extends('layouts.app')

@section('content')

<link href="css/oht.css" rel="stylesheet" type="text/css" />
<link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<br>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-head">
            </div>
            <div class="card-body">
                <table id="#tabla_informe" class="table">
                    <thead>
                        <th>ID</th>
                    </thead>
                    <tbody>
                        @foreach($arr_datos as $d)
                            <tr>
                                <td>
                                    {{$d->id}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script>
<script>
        $(document).ready(function() {
        $('#tabla_informe').DataTable({
            "order": [[5, 'desc']],
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
         });


    });
</script>
@endsection
@endsection
