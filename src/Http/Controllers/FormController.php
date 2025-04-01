<?php

namespace Uspdev\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Forms\Graduacao;
use Uspdev\Replicado\Pessoa;

class FormController extends Controller
{

    /**
     * Busca para ajax do select2 de disciplinas
     */
    public function findDisciplina(Request $request)
    {
        $this->authorize(config('uspdev-forms.findDisciplinasGate'));

        if (!$request->term) {
            return response()->json(['results' => []]);
        }

        $results = [];

        if (hasReplicado()) {
            $coddis = \Illuminate\Support\Str::upper($request->term);
            
            $disciplinas = Graduacao::procurarDisciplinas([$coddis]);

            foreach ($disciplinas as $disciplina) {
                $results[] = [
                    'text' => $disciplina['coddis'] . ' - ' . $disciplina['nomdis'],
                    'id' => $disciplina['coddis'],
                ];
            }
            $results = array_slice($results, 0, 50);
  
        }

        return response()->json(['results' => $results]);
    }

    /**
     * Busca para ajax do select2 de adicionar pessoas
     */
    public function findUser(Request $request)
    {
        $this->authorize(config('uspdev-forms.findUsersGate'));

        if (!$request->term) {
            return response([]);
        }

        $results = [];

        if (hasReplicado()) {

            if (is_numeric($request->term)) {
                // procura por codpes
                $pessoa = Pessoa::dump($request->term);
                $results[] = [
                    'text' => $pessoa['codpes'] . ' ' . $pessoa['nompesttd'],
                    'id' => $pessoa['codpes'],
                ];
            } else {
                // procura por nome, usando fonético e somente ativos
                $pessoas = Pessoa::procurarPorNome($request->term);

                // limitando a resposta em 50 elementos
                $pessoas = array_slice($pessoas, 0, 50);

                $pessoas = collect($pessoas)->unique()->sortBy('nompesttd');

                // formatando para select2
                foreach ($pessoas as $pessoa) {
                    $results[] = [
                        'text' => $pessoa['codpes'] . ' ' . $pessoa['nompesttd'],
                        'id' => $pessoa['codpes'],
                    ];
                }
            }
        }

        return response(compact('results'));
    }
}
