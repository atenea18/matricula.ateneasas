<div id="sidebar-wrapper" >
	<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
		<!-- Inicio -->
		<li>
            <a href="{{route('admin.home')}}"> 
            	<span class="fa-stack fa-lg pull-left">
            		<i class="fa fa-home fa-stack-1x "></i>
            	</span>
            	Home
            </a>
        </li>
		
        <!-- Evaluación -->
        <li>
            <a href="{{route('teacher.evaluation')}}"> 
                <span class="fa-stack fa-lg pull-left">
                    <i class="fa fa-check fa-stack-1x "></i>
                </span>
                Evaluacion
            </a>
        </li>

        <!-- Estadísticas -->
        <li>
            <a href="{{route('teacher.statistics')}}">
                <span class="fa-stack fa-lg pull-left">
                    <i  class="far fa-chart-bar fa-stack-1x"></i>
                </span>
                Estadísticas
            </a>
        </li>

        <!-- Planilla -->
        <li>
            <a href="{{route('teacher.evaluation')}}"> 
                <span class="fa-stack fa-lg pull-left">
                    <i class="fa fa-clipboard fa-stack-1x "></i>
                </span>
                Planillas
            </a>
        </li>

        <!-- Configuracion -->
        <li>
            <a href="{{route('teacher.evaluation')}}"> 
                <span class="fa-stack fa-lg pull-left">
                    <i class="fa fa-cog fa-stack-1x "></i>
                </span>
                Configuracion
            </a>
        </li>

        {{-- Areas --}}
       {{--  <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#areas_asig" aria-expanded="true" aria-controls="areas_asig">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-book fa-stack-1x "></i>
                </span> 
                Areas y Asignaturas
            </a>
            <ul id="areas_asig" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="">Areas</a></li>
                <li><a href="">Asignaturas</a></li>
            </ul>
        </li> --}}
        
        {{-- Carga de Archivos --}}
        {{-- <li class="">
            <a data-toggle="collapse" data-parent="#menu" href="#import_files" aria-expanded="true" aria-controls="import_files">
                <span class="fa-stack fa-lg pull-left"><i class="fa fa-upload fa-stack-1x "></i>
                </span> 
                Carga de archivos
            </a>
            <ul id="import_files" class="nav-pills nav-stacked collapse collapseable" style="list-style-type:none;">
                <li><a href="{{route('import.old_students.form')}}">Estudiantes antigüos</a></li>
                <li><a href="{{route('import.old_teachers.form')}}">Docentes antigüos</a></li>
            </ul>
        </li> --}}
    </ul>
</div>