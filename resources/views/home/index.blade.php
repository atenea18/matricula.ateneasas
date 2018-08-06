@extends('layouts.app')

@section('content')
@include('layouts.menu')

{{-- BANNER --}}
<section class="main_banner">
	<div class="container">
		<div class="row text-center">
			<div class="col-md-6">
				<h1>Titulo</h1>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
				</p>
				<a href="{{route('institution.login')}}" class="btn btn-warning btn-lg">Comienza Ya!</a>
			</div>
			<div class="col-md-6">
				<img src="{{asset('img/mac.png')}}" alt="" width="300">
			</div>
		</div>
	</div>
</section>

{{--  --}}
<section>
	<div class="container">
		<div class="row text-center">
			<h2>¿Que es Ágora?</h2>
		</div>
		<div class="row">
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
			</p>
		</div>
	</div>
</section>

{{--  --}}
<section class="section_can">
	<div class="container">
		<div class="row text-center">
			<h2>¿Que puedo hacer con Ágora?</h2>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-3">
						<i class="fa fa-users fa-5x"></i>
					</div>
					<div class="col-md-9">
						<h3>Adminsitración de usuarios</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod mpor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-3">
						<i class="fa fa-check-circle fa-5x"></i>
					</div>
					<div class="col-md-9">
						<h3>Evalua</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod mpor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-3">
						<i class="fa fa-chart-bar fa-5x"></i>
					</div>
					<div class="col-md-9">
						<h3>Estadisticas</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod mpor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-3">
						<i class="fa fa-clock fa-5x"></i>
					</div>
					<div class="col-md-9">
						<h3>Ahorra tiempo</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod mpor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

{{--  --}}
<section class="section_customer">
	<div class="container_fluid">
		<div class="row text-center">
			<div class="col-md-12">
				<h2>Nuestros clientes</h2>
			</div>
		</div>
		<div class="row">
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			{{--  --}}
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100/676a6c/ffffff" alt="">
			</div>
			<div class="img">
				<img src="http://via.placeholder.com/100x100" alt="">
			</div>
		</div>
	</div>
</section>

{{--  --}}
<section class="main_footer">
	<footer>
		<div class="container_fluid">
			<div class="row text-center">
				<div class="col-md-12">
					<p>
						Todos los derechos reservado &copy; 2018 Agora
					</p>
				</div>
			</div>
		</div>
	</footer>
</section>
@endsection