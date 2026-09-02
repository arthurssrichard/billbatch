<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    protected $fillable = 
    [
        'limite_emails_hora',
        'remetente_nome',
        'remetente_endereco',
        'remetente_senha',
        'smtp_servidor',
        'smtp_secure',
        'smtp_porta',
    ];

    protected function casts(): array
    {
        return [
            'remetente_senha' => 'encrypted',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
