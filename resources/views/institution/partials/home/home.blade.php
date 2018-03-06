@extends('institution.dashboard.index')

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			  	</div>
			  	<div class="panel-body">
			  		<div class="conatiner-fluid">
			  			<div class="row">
			  				<div class="col-md-12 text-center">
			  					<img src="{{Storage::disk('uploads')->url(Auth::guard('web_institution')->user()->picture)}}" alt="" class="img-responsive" style="margin: 0 auto;">
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>
@endsection