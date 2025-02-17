<?php

namespace Uspdev\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Uspdev\Forms\Models\FormDefinition;
use Uspdev\Forms\Models\FormSubmission;

class Form
{

    public $key;
    public $method;
    public $group;
    public $btnLabel;

    public $formId;

    public $action; // sem uso por enquanto

    public function __construct($key = null, $name = null)
    {
        $this->key = $key ?: config('uspdev-forms.defaultKey');
        $this->method = config('uspdev-forms.defaultMethod');
        $this->group = config('uspdev-forms.defaultGroup');
        $this->btnLabel = config('uspdev-forms.defaultBtnLabel');

        $this->action = '';

    }

    /**
     * Processa submissões do form persistindo em banco de dados
     *
     * Dentro do request precisa ter form_definition, form_key
     * user é opcional para o caso do form ser aberto
     *
     * @param $request->formDefinition
     * @param $request->form_key
     * @param $request->user
     * @return FormSubmission $formSubmission
     */
    public function handleSubmission(Request $request)
    {
        // Lets store only valid form fields
        $data = $this->validate($request);

        $formSubmission = FormSubmission::Create([
            'form_definition_id' => $this->formId,
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
        if (!($formDefinition = $this->getDefinition($request->form_definition))) {
            return response()->json('Error on finding form definition');
        }

        $this->formId = $formDefinition->id;

        // Validate the incoming request data based on form fields
        $rules = $this->getFormValidationRules($formDefinition);
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
    protected function getFormValidationRules($form)
    {
        $rules = [];

        foreach ($form->fields as $field) {

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
     * @param String $form_key Application key associated to this form/submissions
     * @return String HTML formatted
     */
    public function generateHtml(String $formName)
    {
        if (!($formDefinition = $this->getDefinition($formName))) {
            return null;
        }

        $btnSize = config('uspdev-forms.formSize') == 'small' ? ' btn-sm ' : '';

        $form = '<form action="' . $this->action . '" method="' . $this->method . '" name="' . $formDefinition->name . '">';
        $form .= '<input type="hidden" name="form_definition" value="' . $formDefinition->name . '">' . PHP_EOL;
        $form .= '<input type="hidden" name="form_key" value="' . $this->key . '">' . PHP_EOL;
        $form .= csrf_field() . PHP_EOL;

        foreach ($formDefinition->fields as $field) {

            if (array_is_list($field)) {
                // agrupando campos na mesma linha: igual para bs4 e bs5
                $form .= '<div class="row">';
                foreach ($field as $f) {
                    $form .= ' <div class="col">';
                    $form .= $this->generateField($f);
                    $form .= '</div>';
                }
                $form .= '</div>';
            } else {
                // a linha possui um campo somente
                $form .= $this->generateField($field);
            }
        }

        $form .= '<button type="submit" class="btn btn-primary ' . $btnSize . '">' . $this->btnLabel . '</button>' . PHP_EOL;
        $form .= '</form>' . PHP_EOL;

        return $form;
    }

    /**
     * Generates fields for the form generator
     */
    protected function generateField($field)
    {
        $bs = config('uspdev-forms.bootstrapVersion');
       
        $required = isset($field['required']) && $field['required'] ? 'required' : '';
        $requiredLabel = $required ? ' <span class="text-danger">*</span>' : '';

        $formGroupClass = $bs == 5 ? 'mb-3' : 'form-group';

        $formControlClass = $bs == 5 ? 'form-control' : 'form-control';
        $formControlClass .= config('uspdev-forms.formSize') == 'small' ? ' form-control-sm ' : '';

        $fieldId = 'uspdev-laravel-form-' . $field['name'];

        if ($field['type'] === 'textarea') {

            $html = '<div class="' . $formGroupClass . '">';
            $html .= '<label for="' . $fieldId . '">' . $field['label'] . $requiredLabel . '</label>';
            $html .= '<textarea id="' . $fieldId . '" name="' . $field['name'] . '" class="' . $formControlClass . '" ' . $required . '></textarea>';
            $html .= '</div>' . PHP_EOL;

        } elseif ($field['type'] === 'select') {

            $html = '<div class="' . $formGroupClass . '">';
            $html .= '<label for="' . $fieldId . '">' . $field['label'] . $requiredLabel . '</label>';
            $html .= '<select id="' . $fieldId . '" name="' . $field['name'] . '" class="' . $formControlClass . '" ' . $required . '>';
            $html .= '<option selected disabled hidden value="">Selecione um ..</option>';
            foreach ($field['options'] as $o) {
                $html .= '<option>' . $o . '</option>';
            }
            $html .= '</select>';
            $html .= '</div>' . PHP_EOL;

        } else {
            $value = array_key_exists('value', $field) ? $field['value'] : "";
            $label = isset($field['label']) ? $field['label'] : "";

            $html = '<div class="' . $formGroupClass . '">';
            $html .= '<label for="' . $fieldId . '">' . $label  . $requiredLabel . '</label>';
            $html .= '<input id="' . $fieldId . '" type="' . $field['type'] . '" name="' . $field['name'] . '" class="' . $formControlClass . '" ' . $required .'
            value="'. $value .'" />';
            $html .= '</div>' . PHP_EOL;
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
