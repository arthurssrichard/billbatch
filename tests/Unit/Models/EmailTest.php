<?php

use App\Models\Email;

test('remetente_senha é criptografado automaticamente', function () {
    $email = Email::factory()->create(['remetente_senha' => 'senhanaocriptografada']);
    expect($email->remetente_senha)->not->toBe($email->getRawOriginal('remetente_senha'))
        ->and($email->remetente_senha)->toBe('senhanaocriptografada');
});
