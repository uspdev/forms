<?php

namespace Uspdev\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Uspdev\Forms\Models\FormDefinition;
use Uspdev\Forms\Models\FormSubmission;

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

    public $updateMode;

    public function __construct($key = null, $config = [],)
    {
        $this->key = $key ?: config('uspdev-forms.defaultKey');
        $this->method = config('uspdev-forms.defaultMethod');
        $this->group = config('uspdev-forms.defaultGroup');
        $this->btnLabel = config('uspdev-forms.defaultBtnLabel');
        $this->btnSize = config('uspdev-forms.formSize') == 'small' ? ' btn-sm ' : '';

        $this->action = isset($config['action']) ? $config['action'] : null;
        $this->updateMode = isset($config['updateMode']) ? $config['updateMode'] : null;
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
     * @return FormSubmission $formSubmission
     */
    public function handleSubmission(Request $request, $id = null)
    {
        // Lets store only valid form fields
        $data = $this->validate($request);
        if ($this->updateMode) {
            $form = FormSubmission::where('id', $id)->firstOrFail();
            $form->data = $data;
            $form->save();

            return $form;
        }
        $formSubmission = FormSubmission::Create([
            'form_definition_id' => $this->definition->id,
            'user_id' => $request->user() ? $request->user()->id : null,
            'key' => $request->form_key,
            'data' => $data,
        ]);

        return $formSubmission;
    }

    /**
     * Validate form submission and return validated data
     */
    public function validate($request)
    {
        // Retrieve the form definition by id
        if (!($this->definition = $this->getDefinition($request->form_definition))) {
            return response()->json('Error on finding form definition');
        }

        // Validate the incoming request data based on form fields
        $rules = $this->getValidationRules();
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
    protected function getValidationRules()
    {
        $rules = [];

        foreach ($this->definition->fields as $field) {

            if (array_is_list($field)) {
                foreach ($field as $f) {
                    $rules[$f['name']] = $this->getFieldValidationRule($f);
                }
            } else {
                $rules[$field['name']] = $this->getFieldValidationRule($field);
            }
        }
        return $rules;
    }

    protected function getFieldValidationRule($field)
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
    public function generateHtml(String $formName, $formSubmission = null)
    {
        if (!($this->definition = $this->getDefinition($formName))) {
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
        $bs = config('uspdev-forms.bootstrapVersion');
        $required = isset($field['required']) && $field['required'] ? 'required' : '';
        $requiredLabel = $required ? ' <span class="text-danger">*</span>' : '';
        $formGroupClass = $bs == 5 ? 'mb-3' : 'form-group';
        $controlClass = 'form-control ' . (config('uspdev-forms.formSize') == 'small' ? ' form-control-sm ' : '');
        $id = 'uspdev-forms-' . $field['name'];
        $f = compact('bs', 'required', 'requiredLabel', 'formGroupClass', 'controlClass', 'id', 'field');

        if ($field['type'] === 'textarea') {
            $html = view('uspdev-forms::partials.textarea', compact('f','formSubmission'))->render();
        } elseif ($field['type'] === 'select') {
            $html = view('uspdev-forms::partials.select', compact('f','formSubmission'))->render();
        } elseif ($field['type'] === 'checkbox') {
            $html = view('uspdev-forms::partials.checkbox', compact('f','formSubmission'))->render();
        } elseif ($field['type'] === 'pessoa-usp') {
            $html = view('uspdev-forms::partials.pessoa-usp', compact('f','formSubmission'))->render();
        } elseif ($field['type'] === 'disciplina-usp') {
            $disciplinas = \Uspdev\Replicado\Graduacao::listarDisciplinas();
            $html = view('uspdev-forms::partials.disciplina-usp', compact('f','formSubmission', 'disciplinas'))->render();
        } else {
            $html = view('uspdev-forms::partials.default', compact('f','formSubmission'))->render();
        }

        return $html;
    }

    /**
     * List form submissions filtering by key and optionally by formName
     */
    public function listSubmission($formName = null)
    {
        $cond['key'] = $this->key;
        if ($formName) {
            $cond['form_definition_id'] = $this->getDefinition($formName)->id;
        }

        return FormSubmission::where($cond)->get();
    }

    /**
     * Get a form submission by id
     */
    public function getSubmission($id)
    {
        return FormSubmission::find($id);
    }

    /**
     * Returns form definition by form_id
     */
    public function getDefinition($formName)
    {
        return FormDefinition::where('name', $formName)->first();
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
