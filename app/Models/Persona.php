<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     * (Siguiendo tu esquema: gestion_personas.personas)
     */
    protected $table = 'personas';

    /**
     * Los atributos que son asignables (Mass Assignment).
     */
    protected $fillable = [
        'cedula',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'sexo',
        'estado',
        'municipio',
        'parroquia',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación Uno a Uno con el Usuario.
     * Una persona tiene un único registro de usuario en el sistema.
     */
    public function user(): HasOne
    {
        // 'persona_id' es la FK en la tabla 'usuarios'
        return $this->hasOne(User::class, 'persona_id');
    }

    /**
     * Accesor para obtener el nombre completo de forma sencilla.
     * Ejemplo: $persona->nombre_completo
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->primer_nombre} {$this->primer_apellido}";
    }
}
