<?php

namespace Uspdev\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Graduacao;
use Uspdev\Replicado\Pessoa;
use Uspdev\Forms\Models\FormDefinition;

class FormController extends Controller
{
    public function create()
    {
        return view('uspdev-forms::form.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'required|string',
            'fields' => 'required|string',
        ]);

        FormDefinition::create($request->all());

        return redirect()->route('form-definitions.index')->with('success', 'Form Definition created successfully.');
    }

    public function edit($id)
    {
        $formDefinition = FormDefinition::findOrFail($id);
        return view('uspdev-forms::form.create', compact('formDefinition'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'fields' => 'required|string'
        ]);
        

        $formDefinition = FormDefinition::findOrFail($id);

        $formDefinition->fields = json_decode($request->input('fields'), true);
        $formDefinition->save();

        $formDefinition->update($request->only(['name', 'group', 'description']));

        return redirect()->route('form-definitions.index');
    }

    public function destroy($id)
    {
        $formDefinition = FormDefinition::findOrFail($id);
        $formDefinition->delete();

        return redirect()->route('form-definitions.index');
    }

    public function index()
    {
        $formDefinitions = FormDefinition::all();
        return view('uspdev-forms::form.index', compact('formDefinitions'));
    }

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
