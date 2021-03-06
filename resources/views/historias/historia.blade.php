ç@extends('layouts.main')

@section('content-header')
     <h1>
        Historias
        <small>Historia del paciente</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-home"></i> Escritorio</a></li>
        <li><a href="{{ route('historias.index') }}">Historias</a></li>
        <li class="active">Historia del paciente</li>
      </ol>
@endsection

@section('content')

  <div class="box box-default">
        <div class="box-header with-border">
			<div class="box-header">
				  <a href="{{ route('historias.index') }}"><button type="submit" class="btn btn-default btn-sm">Ver lista de historias</button></a>
			</div>
			<div class="box-header">
              <h3 class="box-title">{{ $user->primernombre.' '.$user->segundonombre.' '.$user->primerapellido.' '.$user->segundoapellido.' : '.$user->tipodocumento.' '.$user->numerodocumento }}</h3>
          	</div>  
          	<div class="box-tools pull-right">
          		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          	</div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        @include('flash::message')
          <div class="row">
            

            <div class=col-md-12>
                <div class="form-group col-md-1">
                  <img class='profile-user-img img-responsive' src="{{ asset('images/users/'.$user->imagen) }}" />
                </div>
            </div>


            <div class="col-md-12">
		          <!-- Custom Tabs (Pulled to the right) -->
		          <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs pull-right">
		              <li class="active"><a href="#tab_1-1" data-toggle="tab">Ocupacional</a></li>
		              <li><a href="#tab_2-2" data-toggle="tab">Pediatría</a></li>
		              <li><a href="#tab_3-2" data-toggle="tab">Ginecología</a></li>
		             
		              <li class="pull-left header"><i class="fa fa-th"></i> Historias</li>
		            </ul>
		            <div class="tab-content">
		              <div class="tab-pane active" id="tab_1-1">
		                <b>How to use:</b>

		                <p>Exactly like the original bootstrap tabs except you should use
		                  the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.</p>
		                A wonderful serenity has taken possession of my entire soul,
		                like these sweet mornings of spring which I enjoy with my whole heart.
		                I am alone, and feel the charm of existence in this spot,
		                which was created for the bliss of souls like mine. I am so happy,
		                my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
		                that I neglect my talents. I should be incapable of drawing a single stroke
		                at the present moment; and yet I feel that I never was a greater artist than now.
		              </div>
		              <!-- /.tab-pane -->
		              <div class="tab-pane" id="tab_2-2">
		                The European languages are members of the same family. Their separate existence is a myth.
		                For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
		                in their grammar, their pronunciation and their most common words. Everyone realizes why a
		                new common language would be desirable: one could refuse to pay expensive translators. To
		                achieve this, it would be necessary to have uniform grammar, pronunciation and more common
		                words. If several languages coalesce, the grammar of the resulting language is more simple
		                and regular than that of the individual languages.
		              </div>
		              <!-- /.tab-pane -->
		              <div class="tab-pane" id="tab_3-2">
		                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
		                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
		                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
		                It has survived not only five centuries, but also the leap into electronic typesetting,
		                remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
		                sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
		                like Aldus PageMaker including versions of Lorem Ipsum.
		              </div>
		              <!-- /.tab-pane -->
		            </div>
		            <!-- /.tab-content -->
		          </div>
		          <!-- nav-tabs-custom -->
		        </div>
		        <!-- /.col -->
         		
               


          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
 @endsection

 @section('javascript')
 <script>

 	$('.datepicker').daterangepicker({
        singleDatePicker: true,
        format: 'DD-MM-YYYY',
        calender_style: "picker_2",
        showDropdowns: true,
         "singleDatePicker": true,
          "showDropdowns": true,
          "showWeekNumbers": true,
          "locale": {
            "format": 'DD/MM/YYYY',
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "Desde",
            "toLabel": "hasta",
            "customRangeLabel": "Custom",
            "weekLabel": "S",
            "daysOfWeek": ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa' ],
            "monthNames": ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            "firstDay": 1
          },
          "showCustomRangeLabel": false

      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
      });

  
 </script>
 @endsection