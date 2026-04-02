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
        $activeTab = 'index';
        return view('uspdev-forms::definition.index', compact('formDefinitions','activeTab'));
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

    public function backup_def(FormDefinition $formDefinition)
    {
        
        $file_dir = config("uspdev-forms.forms_storage_dir");
        if(!is_dir($file_dir))
        {
            mkdir($file_dir,0777,true);
        }
        
        $file_path = $file_dir . "/" . $formDefinition['name'] . '@' . now()->format('d-m-Y_H:i:s') . ".json";
        $json_file = fopen($file_path, "w");

        fwrite($json_file, json_encode($formDefinition,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fclose($json_file);

        return redirect()->back()->with('alert-success','Backup de: '. $formDefinition['name'] .' gerado com sucesso em: ' . now() . '!');

    }

    public function backup_all()
        {
            $form_definitions = FormDefinition::all();
            
            foreach($form_definitions as $form_definition)
            {
                $this->backup_def($form_definition);
            }
    
            return redirect()->back()->with('alert-success','Backups gerados em: ' . now() . ' com sucesso!');
        }
    
    public function backups_index()
    {
        \UspTheme::activeUrl(route('form-definitions.backups'));

        $activeTab = 'backup';
        $formDefinitions = FormDefinition::all();
        return view('uspdev-forms::definition.backup', compact('activeTab','formDefinitions'));
    }

    public function list_backups(FormDefinition $formDefinition)
    {
        $bckp_files = scandir(config('uspdev-forms.forms_storage_dir'));
        $bckp_files = array_filter($bckp_files, function($filename) use ($formDefinition) { return str_contains($filename,$formDefinition->name); });
            
        $backup_data = [];

        foreach($bckp_files as $filename)
        {
            [$name, $created_time] = explode('@',$filename);
            $created_time = explode('.',$created_time);
            $created_time = $created_time[0];

            $created_time = str_replace('-','/',$created_time);
            $created_time = str_replace('_',' - ',$created_time);
            

            $last_mod_time = date('d/m/Y - H:i:s',filemtime(config('uspdev-forms.forms_storage_dir') .'/'.$filename));

            $backup_data[$created_time] = $last_mod_time;
        }

        return view('uspdev-forms::definition.backup-list', ['formDefinition' => $formDefinition, 'backup_data' => $backup_data]);
    }
}
