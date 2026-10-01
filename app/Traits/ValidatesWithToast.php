<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ValidatesWithToast
{
    protected function validateWithToast(array $campos, string $entidadeAtualizada): bool
    {
        try {
            $this->validate();

            $mensagem = str_split($entidadeAtualizada)[strlen($entidadeAtualizada) - 1] == 'a' ? "$entidadeAtualizada atualizada com sucesso" : "$entidadeAtualizada atualizado com sucesso";

            $this->dispatch(
                'toast',
                tipo: 'sucesso',
                mensagem: $mensagem
            );

            return true;
        } catch (ValidationException $e) {
            // dd($e);
            $campoComErro = $e->validator->errors()->keys()[0];

            $valor = data_get($this, $campoComErro);

            $nomeLegivel = $this->campoLegivel(
                $campoComErro,
                $campos
            );

            $this->dispatch(
                'toast',
                tipo: 'erro',
                mensagem: "O valor '{$valor}' não é um {$nomeLegivel} válido."
            );

            throw $e;
        }
    }

    private function campoLegivel(string $campo, array $campos): string
    {
        foreach ($campos as $padrao => $nome) {
            if ($campo === $padrao) {
                return $nome;
            }

            if (@preg_match("/^{$padrao}$/", $campo)) {
                return $nome;
            }
        }

        return 'campo';
    }
}
