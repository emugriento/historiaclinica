<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    
    protected $table = 'empresas';

    protected $fillable = [
        'descripcion',
    ];

    protected $dates = ['deleted_at'];

    public function pacientes()
    {
        return $this->hasMany('App\Paciente');
    }

    public function scopeOfType($query, $type){
		
		return $query->where('descripcion', 'like' , '%'.$type.'%');
	}
}
