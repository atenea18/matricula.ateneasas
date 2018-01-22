<nav class="navbar navbar-default no-margin" id="nav">
	<div class="image">
      {{-- <img src="{{asset('img/institution/picture').'/'.Auth::guard('admin_guard')->user()->id.'.jpg'}}" alt="" class="img-responsive" width="35"> --}}
   	</div>
	<!-- <div class="container"> -->
		<div class="navbar-header fixed-brand">
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"  id="menu-toggle">
	           <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
	        </button>
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	          <span class="sr-only">Toggle navigation</span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </button>
        	{{-- <a class="navbar-brand" href="#">{{ Auth::guard('admin_guard')->user()->name }}</a> --}}
     	</div><!-- navbar-header-->

		<div class="collapse navbar-collapse" id="app-navbar-collapse">
			<!-- Left Side Of Navbar -->
			<ul class="nav navbar-nav">
				<li class="active" >
	         	<button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> 
	            	<span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
	          	</button>
	        </li>
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						{{ Auth::guard('admin_guard')->user()->name }} <span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="{{ url('/admin/logout') }}"
							onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
							Salir
						</a>

							<form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	<!-- </div> -->
</nav>