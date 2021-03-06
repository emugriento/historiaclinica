<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historia_ocupacional extends Model
{
     use SoftDeletes;
	
    protected $table='historia_ocupacionales';

    protected $fillable = [
        'medico_paciente_id','escolaridad_id','tipo_examen_id','turno_id','actividad_id','lateralidad_id','numerohijos','numeropersonascargo','cargoactual','peso','talla','imc','ta','fc','fr',
    ];

    protected $dates = ['deleted_at'];

    public function medico_paciente()
    {
        return $this->belongTo('App\Medico_paciente','medico_paciente_id');
    }

    public function escolaridad()
    {
        return $this->belongTo('App\Escolaridad','escolaridad_id');
    }

    public function tipo_examen()
    {
        return $this->belongTo('App\Tipo_examen','tipo_examen_id');
    }

    public function turno()
    {
        return $this->belongTo('App\Turno','turno_id');
    }

    public function actividad()
    {
        return $this->belongTo('App\Actividad','actividad_id');
    }

    public function lateralidad()
    {
        return $this->belongTo('App\Lateralidad','lateralidad_id');
    }


    public function antecedente_ocupacionales()
    {
        return $this->hasMany('App\Antecedente_ocupacional','historia_ocupacional_id');
    }

    public function patologicos()
    {
        return $this->hasMany('App\Patologico','historia_ocupacional_id');
    }

    public function habito_fumadores()
    {
        return $this->hasMany('App\Habito_fumador','historia_ocupacional_id');
    }

    public function habito_licores()
    {
        return $this->hasMany('App\Habito_licor','historia_ocupacional_id');
    }

    public function habito_medicamentos()
    {
        return $this->hasMany('App\Habito_medicamento','historia_ocupacional_id');
    }

    public function inmunizaciones()
    {
        return $this->hasMany('App\Inmunizacion','historia_ocupacional_id');
    }

    public function ginecobstetricas()
    {
        return $this->hasMany('App\Ginecobstetrica','historia_ocupacional_id');
    }

    public function exploraciones()
    {
        return $this->hasMany('App\Exploracion','historia_ocupacional_id');
    }

    public function visuales()
    {
        return $this->hasMany('App\Visual','historia_ocupacional_id');
    }

    public function examen_laboratorios()
    {
        return $this->hasMany('App\Examen_laboratorio','historia_ocupacional_id');
    }

    public function factor_riesgos()
    {
        return $this->belongsToMany('App\Factor_riesgo','historia_ocupacional_factor_riesgo','historia_ocupacional_id','factor_riesgo_id');
    }

    public function diagnosticos()
    {
        return $this->belongsToMany('App\Factor_riesgo','historia_ocupacional_diagnostico','historia_ocupacional_id','diagnostico_id');
    }
}
