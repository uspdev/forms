<?php

namespace Uspdev\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Uspdev\Forms\Models\FormDefinition;
use Uspdev\Forms\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use \Spatie\Activitylog\Models\Activity;

class Form
{

    /** Chave definida pelo usuário para esta instancia do form */
    public $key;

    public $group;
    public $btnLabel;
    public $btnSize;

    /** formDefinition object */
    public $definition;

    /** Metodo do form */
    public $method;

    /** corresponde ao campo action do formulário */
    public $action;

    /** Nome do formulario no BD*/
    public $name;

    /** se true, pode ser editado. nesse caso precisa passar o id da submissão */
    public $editable; // bool
    
    public $user;
    
    public $admin;

    public function __construct($config = [])
    {
        $this->key = isset($config['key']) ? $config['key'] : config('uspdev-forms.defaultKey');
        $this->method = isset($config['method']) ? $config['method'] : config('uspdev-forms.defaultMethod');

        $this->group = config('uspdev-forms.defaultGroup');
        $this->btnLabel = config('uspdev-forms.defaultBtnLabel');
        $this->btnSize = config('uspdev-forms.formSize') == 'small' ? ' btn-sm ' : '';

        // nome do form definition
        $this->name = isset($config['name']) ? $config['name'] : null;

        $this->action = isset($config['action']) ? $config['action'] : null;
        $this->editable = isset($config['editable']) ? $config['editable'] : false;
    }

    /**
     * Processa submissões do form persistindo em banco de dados
     *
     * Dentro do request precisa ter form_definition, form_key
     * user é opcional para o caso do form ser aberto
     *
     * @param $request->form_definition
     * @param $request->form_key
     * @param $request->user
     * @param $request->id (necessário se update for permitido)
     * @return FormSubmission $formSubmission
     */
    public function handleSubmission(Request $request)
    {
        // Retrieve the form definition by id
        if (!($definition = $this->getDefinition($request->form_definition))) {
            return response()->json('Error on finding form definition');
        }

        // Lets store only valid form fields
        $data = $this->validate($request);
        if ($this->editable && $request->id) {
            $form = FormSubmission::where('id', $request->id)->firstOrFail();
            $form->data = $data;
            $form->save();

            return $form;
        } else {
            $formSubmission = FormSubmission::Create([
                'form_definition_id' => $definition->id,
                'user_id' => $request->user() ? $request->user()->id : null,
                'key' => $request->form_key,
                'data' => $data,
            ]);

            activity()->performedOn($formSubmission)->causedBy(Auth::user())->log('Submissão criada');
            return $formSubmission;
        }
    }

    /**
     * Validate form submission and return validated data
     */
    public function validate($request)
    {
        // Retrieve the form definition by id
        if (!($definition = $this->getDefinition($request->form_definition))) {
            return response()->json('Error on finding form definition');
        }

        // Validate the incoming request data based on form fields
        $rules = $this->getValidationRules($definition);
        $validator = Validator::make($request->all(), $rules);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(array_keys($rules));
        return $data;
    }

    /**
     * Retorna as regras de validação para os campos do form
     */
    protected static function getValidationRules(FormDefinition $definition)
    {
        $rules = [];

        foreach ($definition->fields as $field) {

            if (array_is_list($field)) {
                foreach ($field as $f) {
                    $rules[$f['name']] = self::getFieldValidationRule($f);
                }
            } else {
                $rules[$field['name']] = self::getFieldValidationRule($field);
            }
        }
        return $rules;
    }

    /**
     * Return the validation rule for a field based on required or type
     */
    protected static function getFieldValidationRule($field)
    {
        // required or nullable
        if (isset($field['required']) && $field['required']) {
            $rule = 'required';
        } else {
            $rule = 'nullable';
        }

        // Add additional rules based on field type
        // Example: if field type is 'email'
        if ($field['type'] === 'email') {
            $rule .= '|email';
        }
        return $rule;
    }

    /**
     * Generates HTML FORM from Form Definition
     *
     * @param String $formName ID of form definition
     * @return String HTML formatted
     */
    public function generateHtml(?string $formName = null, $formSubmission = null)
    {
        if (
            !($this->definition = $this->getDefinition($formName ?? $this->name)) &&
            !($this->definition = $formSubmission->formDefinition)
        ) {
            return null;
        }

        $fields = '';
        foreach ($this->definition->fields as $field) {

            if (array_is_list($field)) {
                // agrupando campos na mesma linha: igual para bs4 e bs5
                $fields .= '<div class="row">';
                foreach ($field as $f) {
                    $fields .= '<div class="col">' . $this->generateField($f, $formSubmission) . '</div>';
                }
                $fields .= '</div>';
            } else {
                // a linha possui um campo somente
                $fields .= $this->generateField($field, $formSubmission);
            }
        }
        if ($formSubmission) {
            $this->btnLabel = 'Atualizar';
        }

        $form = view('uspdev-forms::partials.form', [
            'form' => $this,
            'fields' => $fields,
        ])->render();

        return $form;
    }

    /**
     * Generates fields for the form generator
     */
    protected function generateField($field, $formSubmission)
    {
        $field['bs'] = config('uspdev-forms.bootstrapVersion');
        $field['required'] = isset($field['required']) ? $field['required'] : false;
        $field['requiredLabel'] = $field['required'] ? ' <span class="text-danger">*</span>' : '';
        $field['formGroupClass'] = $field['bs'] == 5 ? 'mb-3' : 'form-group';
        $field['controlClass'] = 'form-control ' . (config('uspdev-forms.formSize') == 'small' ? ' form-control-sm ' : '');
        $field['id'] = 'uspdev-forms-' . $field['name'];

        $field['old'] = null;
        if (isset($formSubmission->data[$field['name']])) {
            $field['old'] = $formSubmission->data[$field['name']];
        }

        if (in_array($field['type'], ['textarea', 'select', 'checkbox', 'hidden','time','date', 'pessoa-usp', 'disciplina-usp'])) {
            $html = view('uspdev-forms::partials.' . $field['type'], compact('field'))->render();
        } else {
            $html = view('uspdev-forms::partials.default', compact('field'))->render();
        }

        return $html;
    }

    /**
     * List form submissions filtering by key and optionally by formName
     * 
     * If there's no specific key, it lists all submissions
     */
    public function listSubmission($formName = null)
    {
        $cond = [];
        if($this->key != config('uspdev-forms.defaultKey')){
            $cond['key'] = $this->key;
        }

        if ($formName) {
            $cond['form_definition_id'] = $this->getDefinition($formName)->id;
        }

        return FormSubmission::where($cond)->get();
    }

    /**
     * List form submissions filtering by the value of a given field
     */
    public function whereSubmissionContains($field, $string)
    {
        return $this->admin == true 
            ? FormSubmission::all()
            : FormSubmission::whereJsonContains('data->' . $field, (string) $string)->get();
    }

    /**
     * Get a form submission by id
     */
    public function getSubmission($id)
    {
        return FormSubmission::find($id);
    }

    /**
     * Get a form submission activities by id
     */
    public function getSubmissionActivities($id)
    {
        return Activity::orderBy('created_at', 'DESC')->where('subject_id', $id)->take(20)->get();
    }

    /**
     * Updates a form submission and registers the activity
     */
    public function updateSubmission(Request $request, $formSubmissionId, $user){
        if($this->editable){
            $request->id = $formSubmissionId;
            $formSubmission = $this->handleSubmission($request);

            activity()->performedOn($formSubmission)->causedBy($user)->log('Submissão atualizada');

            return $formSubmission;
        }
        return false;
    }

    /**
     * Deletes a form submission and registers the activity
     */
    public function deleteSubmission($id, $user)
    {
        $submission = $this->getSubmission($id);

        $mockSubmission = $submission;
        if($submission->delete()){
            activity()->performedOn($mockSubmission)->causedBy($user)->log('Chave excluída');
            return $mockSubmission;
        } return false;
    }

    /**
     * Returns form definition by form name
     */
    public function getDefinition($formName = null)
    {
        return FormDefinition::where('name', $formName ?? $this->name)->first();
    }

    /**
     * Return form definitions for a group
     */
    public function listDefinition($formGroup = null)
    {
        $where[] = $formGroup ? ['group', $formGroup] : ['group', $this->group];
        return FormDefinition::where($where)->get();
    }
}
