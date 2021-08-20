@extends('layouts.app')

@section('content')

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Noticias</h5>
                            </div>
                            <div class="card-body">
                                <iframe style="border:none;" height="600" width="600" data-tweet-url="https://twitter.com/Cobreloa_SADP" src="data:text/html;charset=utf-8,%3Ca%20class%3D%22twitter-timeline%22%20href%3D%22https%3A//twitter.com/Cobreloa_SADP%3Fref_src%3Dtwsrc%255Etfw%22%3ETweets%20by%20Cobreloa_SADP%3C/a%3E%0A%3Cscript%20async%20src%3D%22https%3A//platform.twitter.com/widgets.js%22%20charset%3D%22utf-8%22%3E%3C/script%3E%0A"></iframe>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
@endsection