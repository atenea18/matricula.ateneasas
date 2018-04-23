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
    
        {{-- Matricula --}}
        <li class="">
            <a  href="{{route('institution.enrollment.show')}}">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-archive fa-stack-1x "></i>
                </span>
                Matricula
            </a>
        </li>

        <!-- Grupo -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-group" aria-expanded="true" aria-controls="collapse-group">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i>
                </span>
                Grupos
            </a>
            <ul id="collapse-group" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('group.index')}}">Grupos</a></li>
                <li><a href="{{route('subgroup.index')}}">Subgrupos</a></li>
            </ul>
        </li>

        {{-- <li class="">
            <a href="{{route('group.index')}}">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i>
                </span>
                Grupos
            </a>
        </li> --}}
		
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

        <!-- Funcionarios -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-manager" aria-expanded="true" aria-controls="collapse-manager">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-user-circle fa-stack-1x "></i>
                </span> 
                Funcionarios
            </a>
            <ul id="collapse-manager" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('institution.list.teacher')}}">Docentes</a></li>
                {{-- <li><a href="{{route('headquarter.index')}}">Ver Sedes</a></li> --}}
            </ul>
        </li>

        <!-- Asignación Académica -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-manager2" aria-expanded="true" aria-controls="collapse-manager2">
                <span class="fa-stack fa-lg pull-left"><i class="fas fa-plus fa-stack-1x"></i>
                </span>
                Asignación Académ.
            </a>
            <ul id="collapse-manager2" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('areaasignature.index')}}">Grupos</a></li>
                <li><a href="{{route('assignment.subgroup.index')}}">Subgrupos</a></li>

            </ul>
        </li>

        <!-- Documentos -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-files" aria-expanded="true" aria-controls="collapse-files">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-file-alt fa-stack-1x "></i>
                </span> 
                Reportes
            </a>
            <ul id="collapse-files" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('sheet')}}">Planillas</a></li>
                <li><a href="{{route('constancy.index')}}">Constancias</a></li>
                {{-- <li><a href="{{route('notebook.index')}}">Boletines</a></li> --}}
            </ul>
        </li>

        <!-- Configuración -->
        <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#collapse-config" aria-expanded="true" aria-controls="collapse-config">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x "></i>
                </span> 
                Configuración
            </a>
            <ul id="collapse-config" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('evaluationParameter.index')}}">Parametros de Eval.</a></li>
                <li><a href="{{route('period.index')}}">Periodos</a></li>
                <li><a href="{{route('scaleEvaluation.index')}}">Escala Valorativa</a></li>
            </ul>
        </li>

	</ul>

</div>