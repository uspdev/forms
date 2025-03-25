<?php

namespace Uspdev\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Graduacao;

class DisciplinaController extends Controller
{
    /**
     * Busca para ajax do select2 de disciplinas
     */
    public function find(Request $request)
    {
        if (!$request->term) {
            return response()->json(['results' => []]);
        }

        $results = [];

        if (hasReplicado()) {
            $coddis = \Illuminate\Support\Str::upper($request->term);
            
            $disciplinas = Graduacao::obterDisciplinas([$coddis]);

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

}
