<?php

namespace Uspdev\Forms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Uspdev\Forms\Models\FormDefinition;

class DefinitionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:' . config('uspdev-forms.adminGate'));
    }

    public function index()
    {
        \UspTheme::activeUrl(route('form-definitions.index'));
        
        $formDefinitions = FormDefinition::all();
        return view('uspdev-forms::definition.index', compact('formDefinitions'));
    }

    public function show(FormDefinition $formDefinition)
    {
        return $formDefinition;
    }

    public function create()
    {
        \UspTheme::activeUrl(route('form-definitions.index'));
        return view('uspdev-forms::definition.create', ['formDefinition' => null]);
    }

    public function store(Request $request)
    {
        $fields = json_decode($request->input('fields'), true);

        FormDefinition::create([
            'name'        => $request->input('name'),
            'group'       => $request->input('group'),
            'description' => $request->input('description'),
            'fields'      => $fields,
        ]);

        return redirect()->route('form-definitions.index')
            ->with('alert-success', 'Definição criada com sucesso!');
    }

    public function edit(FormDefinition $formDefinition)
    {
        \UspTheme::activeUrl(route('form-definitions.index'));
        return view('uspdev-forms::definition.create', compact('formDefinition'));
    }

    public function update(Request $request, FormDefinition $formDefinition)
    {
        $formDefinition->fields = json_decode($request->input('fields'), true);
        
        $formDefinition->save();

        $formDefinition->update($request->only(['name', 'group', 'description']));

        return redirect()->route('form-definitions.index')
            ->with('alert-success', 'Definição atualizada com sucesso!');
    }

    /**
     * Remove o registro do banco de dados
     *
     * Também remove registros excluidos (softDeletes) limpando o BD
     */
    public function destroy(FormDefinition $formDefinition, Request $request)
    {
        if ($request->destroy_trashed) {
            $formDefinition->formSubmissions()->onlyTrashed()->forceDelete();
            return redirect()->route('form-definitions.index')
                ->with('alert-success', 'Registros excluídos limpado com sucesso!');
        }

        try {
            $formDefinition->delete();
            return redirect()->route('form-definitions.index')
                ->with('alert-success', 'Definição excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('form-definitions.index')
                ->with('alert-danger', 'Não foi possível excluir: ' . $e->getMessage());
        }
    }

    public function export_all()
    {
        $file_dir = base_path(config("uspdev-forms.forms_storage_dir"));
        if(!is_dir($file_dir))
        {
            mkdir($file_dir,0777,true);
        }
        $file_path = $file_dir . "/forms.json";

        $json_file = fopen($file_path,"w");
        
        $form_definitions = FormDefinition::all();
        $count = 1;
        
        fwrite($json_file,"[\n");
        foreach($form_definitions as $form_definition)
        {
            $encoded_json = json_encode($form_definition,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $linhas = explode("\n",$encoded_json);
            foreach($linhas as $linha)
            {
                fwrite($json_file,"\t" . $linha . "\n");
            }
            if($count < $form_definitions->count())
            {
                fwrite($json_file,",\n\n");
            }
            $count++;
        }
        
        fwrite($json_file,"]");

        fclose($json_file);

        return redirect()->route('form-definitions.index')->with('alert-success','Formulários exportados com sucesso!');
    }

    public function export_definition(FormDefinition $formDefinition)
    {
        
        $file_dir = base_path(config("uspdev-forms.forms_storage_dir"));
        if(!is_dir($file_dir))
        {
            mkdir($file_dir,0777,true);
        }
        
        $file_path = $file_dir . "/" . $formDefinition['name'] . ".json";
        $json_file = fopen($file_path, "w");

        fwrite($json_file, json_encode($formDefinition,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fclose($json_file);

        return redirect()->route('form-definitions.index')->with('alert-success','Definição de '. $formDefinition['name'] .' exportada com sucesso!');

    }
}
