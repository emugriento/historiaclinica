@extends('layouts.main')

@section('content-header')
     <h1>
        Modulos
        <small>Editar módulo</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-home"></i> Escritorio</a></li>
        <li><a href="{{ route('modulos.index') }}">Módulos</a></li>
        <li class="active">Editar módulo</li>
      </ol>
@endsection

@section('content')

  <div class="box box-default">
        <div class="box-header with-border">
			<div class="box-header">
				  <a href="{{ route('modulos.index') }}"><button type="submit" class="btn btn-default btn-sm">Ver lista de módulos</button></a>
			</div>
          	<div class="box-tools pull-right">
          		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          	</div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        @include('flash::message')
          <div class="row">
           {!! Form::open(['class' => '','method' => 'PUT','route' => ['modulos.update',$modulo],'role' => 'form','files' => true]) !!}
              
               	<div class="col-md-12">
	            	<div class="form-group col-md-1">
	            	  {!! Form::label('orden','Orden') !!}
	                  {!! Form::text('orden', $modulo->orden,['class' => 'form-control']) !!}
	                </div>

	                <div class="form-group col-md-2">
	            	  {!! Form::label('icono','Icono') !!}
	                  {!! Form::text('icono',$modulo->icono,['placeholder' => '','class'=>'form-control']) !!}
	                </div>

	                <div class="form-group col-md-4">
	            	  {!! Form::label('descripcion','Nombre en el Menú') !!}
	                  {!! Form::text('descripcion',$modulo->descripcion,['placeholder' => '','class'=>'form-control']) !!}
	                </div>

	                 <div class="form-group col-md-2">
	            	  {!! Form::label('site','Controlador') !!}
	                  {!! Form::text('site',$modulo->site,['placeholder' => '','class'=>'form-control']) !!}
	                </div>
	            </div>

	            <div class="col-md-12">
	            	<div class="form-group col-md-9">
  	                {!! Form::label('roles','Roles') !!}
  	                 {!! Form::select('roles[]',$roles,$modulo_role,['class' => 'form-control select2','style' => 'width: 100%','data-placeholder' => 'Seleccione','multiple' => 'true' ]) !!}
  	              
	            </div>

	            
 				<div class="col-md-12">
		            <div class="box-footer">
	              		  <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
	              		  <button type="reset" class="btn btn-default btn-sm">Reset</button>
	              	</div>
	            </div>

            {!! Form::close() !!}
           
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
 @endsection