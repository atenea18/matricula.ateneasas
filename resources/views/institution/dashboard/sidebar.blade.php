<div id="sidebar-wrapper" style="background-color: #286090;">
	<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
        <!--<li class="" style="color: #ffffff; text-align: right ">
            &commat;tenea
        </li>-->
		<!-- Inicio -->
		<li>
            <a href="{{route('institution.home')}}"> 
            	<span class="fa-stack fa-lg pull-left">
            		<i class="fa fa-home fa-stack-1x "></i>
            	</span>
            	Inicio
            </a>
        </li>

        <li class="">
            <a  href="{{route('enrollment.lists', 1)}}">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-archive fa-stack-1x "></i>
                </span>
                Matricula
            </a>
        </li>

        <!-- Grupo -->
        <li class="">
            <a href="{{route('group.index')}}">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i>
                </span>
                Grupos
            </a>
        </li>
		
        <!-- Sede -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-headquarter" aria-expanded="true" aria-controls="collapse-headquarter">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-building fa-stack-1x "></i>
                </span> 
                Sedes
            </a>
            <ul id="collapse-headquarter" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('headquarter.create')}}">Crear</a></li>
                <li><a href="{{route('headquarter.index')}}">Ver Sedes</a></li>
            </ul>
        </li>




	</ul>

</div>