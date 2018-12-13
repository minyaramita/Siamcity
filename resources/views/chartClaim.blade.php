@extends('layouts.report')
@section('report')
<div class="container">
            <div class="row mt-3">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-body " style="padding:70px;">
                                     <div id="chart1"></div>
                                           {!! $chart1 !!}
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection     