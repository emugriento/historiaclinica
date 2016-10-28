<?php

use Illuminate\Database\Seeder;
use App\Municipio;
use App\Departamento;
class MunicipioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departamento = Departamento::where([ 'descripcion' => 'Zulia' ])->first();
    	$municipio= New Municipio;
    	$municipio->descripcion = 'Maracaibo';
        $municipio->departamento()->associate($departamento);
		$municipio->save();
		
    	$municipio= New Municipio;
    	$municipio->descripcion = 'Cabimas';
        $municipio->departamento()->associate($departamento);
		$municipio->save();
        
        $municipio= New Municipio;
        $municipio->descripcion = 'Ciudad Ojeda';
        $municipio->departamento()->associate($departamento);
        $municipio->save();$departamento = Departamento::where([ 'descripcion' => 'Zulia' ])->first();
        
        $municipio= New Municipio;
        $municipio->descripcion = 'Lagunillas';
        $municipio->departamento()->associate($departamento);
        $municipio->save();

        $departamento = Departamento::where([ 'descripcion' => 'Merida' ])->first();
        $municipio= New Municipio;
        $municipio->descripcion = 'Merida';
        $municipio->departamento()->associate($departamento);
        $municipio->save();
    }
}
