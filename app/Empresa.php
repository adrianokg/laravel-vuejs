<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'id_segmento', 'nome', 'descricao', 'email', 'telefone', 'celular', 'whatsapp', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep', 'lat', 'lng', 'banner', 'logo'
    ];

    public function segmento()
    {
        return $this->belongsTo(Segmento::class, 'id_segmento');
    }
}
