<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests;
use Image;
use App\User;
use App\Role;
use App\Pais;
use App\Departamento;
use App\Municipio;
use Carbon\Carbon;
use App\Paciente;
use App\Empresa;
use App\Arl;
use App\Afp;


class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users=User::ofType($request->search)->has('paciente')->orderby('numerodocumento','ASC')->paginate(15);
        return  view('pacientes.index')->with(['users'=>$users ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas=Empresa::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
        $arls=Arl::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
        $afps=Afp::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
        

        $paises=Pais::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        if(old('pais_id'))
        {
            $departamentos=Departamento::where('pais_id',old('pais_id'))->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        }else{
            $departamentos=['0'=>'Seleccione una opción'];
        }

        if(old('departamento_id'))
        {
            $municipios=Municipio::where('departamento_id',old('departamento_id'))->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        }else{
            $municipios=['0'=>'Seleccione una opción'];
        }
        $nacimiento=['municipios'=> $municipios,'departamentos'=> $departamentos,'paises'=> $paises];


        if(old('paisresidencia_id'))
        {
            $departamentos=Departamento::where('pais_id',old('paisresidencia_id'))->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        }else{
            $departamentos=['0'=>'Seleccione una opción'];
        }

        if(old('departamentoresidencia_id'))
        {
            $municipios=Municipio::where('departamento_id',old('departamentoresidencia_id'))->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        }else{
            $municipios=['0'=>'Seleccione una opción'];
        }
        $residencia=['municipios'=> $municipios,'departamentos'=> $departamentos,'paises'=> $paises];
   
   
        return  view('pacientes.create')->with(['nacimiento' => $nacimiento ,'residencia' => $residencia ,'empresas' => $empresas,'afps' => $afps,'arls' => $arls]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'imagen' => 'image', 
            'tipodocumento' => 'required|string|max:2',     
            'numerodocumento' => 'required|string|max:12|unique:users,numerodocumento',
            'email' => 'required|email|unique:users,email', 
            'activo' => 'required|boolean',  
            'primernombre' => 'required|string|max:50',          
            'segundonombre' => 'string|max:50',         
            'primerapellido' => 'required|string|max:50',        
            'segundoapellido' => 'string|max:50',  
            'genero'  => 'required|string|max:10',      
            'fechanacimiento' => 'required|date_format:d/m/Y',       
            'estadocivil' => 'required|string|max:20', 
            'ocupacion' => 'string|max:100',             
            'pais_id' => 'exists:paises,id',           
            'departamento_id' => 'exists:departamentos,id',
            'municipio_id' => 'exists:municipios,id',
            'paisresidencia_id' => 'exists:paises,id',           
            'departamentoresidencia_id' => 'exists:departamentos,id',
            'municipioresidencia_id' => 'exists:municipios,id', 
            'telefono' => 'required|string|max:13',   
            'direccion' => 'string|max:255',
            'foto' => 'image', 
            'empresa_id' => 'integer', 
            'arl_id' => 'integer', 
            'afp_id' => 'integer', 
        ]);
        if ($validator->fails()) {
            flash(implode('<br>',$validator->errors()->all()), 'danger');
            return redirect()->route('pacientes.create')->withInput();
        }


        $municipio_id = Municipio::where(['id'=>$request->municipio_id])->first();
        $municipioresidencia_id = Municipio::where(['id'=>$request->municipioresidencia_id])->first();

        $user= new User;
        $user->email                  = $request->email;
        $user->password               = bcrypt('123456');
        $user->tipodocumento          = $request->tipodocumento;          
        $user->numerodocumento        = $request->numerodocumento;
        $user->primernombre           = $request->primernombre;
        $user->segundonombre          = $request->segundonombre;
        $user->primerapellido         = $request->primerapellido;
        $user->segundoapellido        = $request->segundoapellido;
        $user->fechanacimiento        = Carbon::createFromFormat('d/m/Y',$request->fechanacimiento);
        $user->genero                 = $request->genero;
        $user->estadocivil            = $request->estadocivil;
        $user->municipio()->associate($municipio_id);
        $user->direccion              = $request->direccion;
        $user->ocupacion              = $request->ocupacion;
        $user->telefono               = $request->telefono;
        $user->activo                 = $request->activo;
        $user->save();
        $currentrole = Role::where(['id'=>4])->first();
        $user->roles()->attach($currentrole);
       

        if( $request->hasFile('imagen')){ 
            $imageName = $user->id . '.' . $request->file('imagen')->getClientOriginalExtension();
            Image::make($request->file('imagen'))->resize(null, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('images/users/'.$imageName);
            $user->imagen=$imageName;
            $user->save();
        }

        if( $request->hasFile('firma')){ 
            $imagefirma = $user->id . '.' . $request->file('firma')->getClientOriginalExtension();
            Image::make($request->file('firma'))->resize(null, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('images/firmas/'.$imagefirma);
            $user->firma=$imagefirma;
            $user->save();
        }

        $paciente = new Paciente;
        $paciente->user()->associate($user);
        $paciente->municipio()->associate($municipioresidencia_id);
        $paciente->empresa_id = $request->empresa_id;
        $paciente->afp_id = $request->afp_id;
        $paciente->arl_id = $request->arl_id;
        $paciente->save();


        flash('Se ha registrado '.$user->primernombre.' '.$user->primerapellido.' de forma exitosa!', 'success');
        return redirect()->route('pacientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::with('municipio.departamento.pais')->with('paciente')->where('id',$id)->first();
        $paises=Pais::all()->pluck('descripcion', 'id')->prepend('N/A', 0);
         //Nacimiento
        $departamentos=Departamento::where('pais_id',$user->municipio->departamento->pais->id)->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
        $municipios=Municipio::where('departamento_id',$user->municipio->departamento->id)->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');


        //residencia
        if($user->paciente->municipio_id)
        {
            $residenciadata=Municipio::with('departamento.pais')->where('id',$user->paciente->municipio_id)->first();
            $residencia=['pais_id'=> $residenciadata->departamento->pais->id,'departamento_id'=> $residenciadata->departamento->id,'municipio_id'=> $residenciadata->id];

           $departamentoresidencias=Departamento::where('pais_id', $residencia['pais_id'])->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
            $municipioresidencias=Municipio::where('departamento_id',$residencia['departamento_id'])->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
        } else{
            
            $residencia=['pais_id'=> '0','departamento_id'=> '0','municipio_id'=> '0'];
            $departamentoresidencias= ['0'=>'N/A'];
            $municipioresidencias= ['0'=>'N/A'];

        }   
         
          $empresas=Empresa::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
          $arls=Arl::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
          $afps=Afp::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);

        return  view('pacientes.show')->with(['user' => $user,'paises' => $paises,'departamentos' => $departamentos,'municipios' => $municipios,'residencia' => $residencia,'departamentoresidencias' => $departamentoresidencias,'municipioresidencias' => $municipioresidencias,'empresas' => $empresas,'arls' => $arls,'afps' => $afps]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function password($id)
    {
        $user   =   User::findOrFail($id);
        $user->password = bcrypt('123456');
        $user->save();
        flash('El password del paciente '.$user->primernombre.' '.$user->primerapellido.' ha sido restablecido de forma exitosa (132456)!', 'success');
        return redirect()->route('pacientes.index');
    }


    public function edit($id)
    {
        $user=User::with('municipio.departamento.pais')->with('paciente')->where('id',$id)->first();
       

        //COMBOS PACIENTES
        $paises=Pais::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('Seleccione una opción', 0);
        $departamentos=Departamento::where('pais_id',$user->municipio->departamento->pais->id)->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
        $municipios=Municipio::where('departamento_id',$user->municipio->departamento->id)->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');

        if($user->paciente->municipio_id)
        {
            $residenciadata=Municipio::with('departamento.pais')->where('id',$user->paciente->municipio_id)->first();

            $residencia=['pais_id'=> $residenciadata->departamento->pais->id,'departamento_id'=> $residenciadata->departamento->id,'municipio_id'=> $residenciadata->id];

            $departamentoresidencias=Departamento::where('pais_id', $residencia['pais_id'])->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
            $municipioresidencias=Municipio::where('departamento_id',$residencia['departamento_id'])->orderBy('descripcion', 'ASC')->pluck('descripcion', 'id');
        } else{

            $departamentoresidencias= ['0'=>'Seleccione una opción'];
            $municipioresidencias= ['0'=>'Seleccione una opción'];
            $residencia=['pais_id'=> '0','departamento_id'=> '0','municipio_id'=> '0'];


        }   
       
      
        $empresas=Empresa::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
        $arls=Arl::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);
        $afps=Afp::all()->sortBy('descripcion')->pluck('descripcion', 'id')->prepend('N/A', 0);

        $user=User::with('municipio.departamento.pais')->with('paciente')->where('id',$id)->first();


        return  view('pacientes.edit')->with(['user' => $user,'paises' => $paises,'departamentos' => $departamentos,'municipios' => $municipios,'residencia' => $residencia,'departamentoresidencias' => $departamentoresidencias,'municipioresidencias' => $municipioresidencias,'empresas' => $empresas,'arls' => $arls,'afps' => $afps ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user   =   User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'imagen' => 'image', 
            'tipodocumento' => 'required|string|max:2',     
            'numerodocumento' => 'required|string|max:12',Rule::unique('users')->ignore($user->numerodocumento),
            'email' => 'required|email|',Rule::unique('users')->ignore($user->email), 
            'activo' => 'required|boolean',  
            'primernombre' => 'required|string|max:50',          
            'segundonombre' => 'string|max:50',         
            'primerapellido' => 'required|string|max:50',        
            'segundoapellido' => 'string|max:50',  
            'genero'  => 'required|string|max:10',      
            'fechanacimiento' => 'required|date_format:d/m/Y',       
            'estadocivil' => 'required|string|max:20', 
            'ocupacion' => 'string|max:100',             
            'pais_id' => 'exists:paises,id',           
            'departamento_id' => 'exists:departamentos,id',
            'municipio_id' => 'exists:municipios,id',
            'paisresidencia_id' => 'exists:paises,id',           
            'departamentoresidencia_id' => 'exists:departamentos,id',
            'municipioresidencia_id' => 'exists:municipios,id', 
            'telefono' => 'required|string|max:13',   
            'direccion' => 'string|max:255',   
            'firma' => 'image',
            'empresa_id' => 'integer', 
            'arl_id' => 'integer', 
            'afp_id' => 'integer',               


        ]);
        if ($validator->fails()) {
            flash(implode('<br>',$validator->errors()->all()), 'danger');
            return redirect()->route('pacientes.edit',$user->id);
        }

        $municipio_id = Municipio::where(['id'=>$request->municipio_id])->first();
        $municipioresidencia_id = Municipio::where(['id'=>$request->municipioresidencia_id])->first();

        $user->email                  = $request->email;
        $user->tipodocumento          = $request->tipodocumento;          
        $user->numerodocumento        = $request->numerodocumento;
        $user->primernombre           = $request->primernombre;
        $user->segundonombre          = $request->segundonombre;
        $user->primerapellido         = $request->primerapellido;
        $user->segundoapellido        = $request->segundoapellido;
        $user->fechanacimiento        = Carbon::createFromFormat('d/m/Y',$request->fechanacimiento);
        $user->genero                 = $request->genero;
        $user->estadocivil            = $request->estadocivil;
        $user->municipio()->associate($municipio_id);
        $user->direccion              = $request->direccion;
        $user->ocupacion              = $request->ocupacion;
        $user->telefono               = $request->telefono;
        $user->activo                 = $request->activo;
        $user->save();

        if( $request->hasFile('imagen')){ 
            $imageName = $user->id . '.' . $request->file('imagen')->getClientOriginalExtension();
            Image::make($request->file('imagen'))->resize(null, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('images/users/'.$imageName);
            $user->imagen=$imageName;
            $user->save();
        }

        if( $request->hasFile('firma')){ 
            $imagefirma = $user->id . '.' . $request->file('firma')->getClientOriginalExtension();
            Image::make($request->file('firma'))->resize(null, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('images/firmas/'.$imagefirma);
            $user->firma=$imagefirma;
            $user->save();
        }

        $paciente = Paciente::where('user_id',$user->id)->first();
        $paciente->municipio()->associate($municipioresidencia_id);
        $paciente->empresa_id = $request->empresa_id;
        $paciente->afp_id = $request->afp_id;
        $paciente->arl_id = $request->arl_id;
        $paciente->save();

        flash('Edición realizada de forma exitosa!', 'success');
        return redirect()->route('pacientes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        flash('El paciente '.$user->primernombre.' '.$user->primerapellido.' se ha eliminado de forma exitosa!', 'danger');
        return redirect()->route('pacientes.index');
    }
}
