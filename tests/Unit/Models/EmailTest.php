<?php

use App\Models\Usuario;
use Illuminate\Support\Str;

test('remetente_senha é criptografado automaticamente', function () {
    $email = geraEmail();
    expect($email->remetente_senha)->not->toBe($email->getRawOriginal('remetente_senha'));
    expect($email->remetente_senha)->toBe('senhanaocriptografada');
});

function geraEmail()
{
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $empresa = $usuario->empresas()->create([
        'nome' => 'Empresa Teste',
    ]);

    $email = $empresa->emails()->create([
        'limite_emails_hora' => '200',
        'remetente_nome' => 'EmpresaTeste',
        'remetente_endereco' => 'empresateste@gmail.com',
        'remetente_senha' => 'senhanaocriptografada',
        'smtp_servidor' => 'smtp.teste.com',
        'smtp_secure' => '587',
        'smtp_porta' => 'tls',
    ]);

    return $email;
}
