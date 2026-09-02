<?php

use App\Enums\LogStatus;
use App\Models\Usuario;
use Illuminate\Support\Str;

test('status é do tipo enum', function () {
    $log = geraLog();
    expect($log->status)->toBe(LogStatus::SUCCESS);
});

function geraLog()
{
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $empresa = $usuario->empresas()->create([
        'nome' => 'Empresa Teste',
    ]);

    $log = $empresa->logs()->create([
        'status' => LogStatus::SUCCESS,
        'nome' => 'Boleto enviado com sucesso',
        'arquivo_origem' => 'app\Services\MailSender.php',
        'mensagem' => 'O boleto referente a empresa MICROSOFT foi enviado com sucesso para os emails microsoft@mail.com;test@microsoft.com',
    ]);

    return $log;
}
