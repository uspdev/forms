<?php

namespace Uspdev\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Uspdev\Forms\Models\FormSubmission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class FormDefinition extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast. (Laravel 11 style)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
        ];
    }

    /**
     * Retorna fields mas achatado - sem subarrays
     */
    public function flattenFields()
    {
        $ret = [];
        foreach ($this->fields as $field) {
            if (array_is_list($field)) {
                foreach ($field as $f) {
                    $ret[] = $f;
                }
            } else {
                $ret[] = $field;
            }
        }
        return $ret;
    }

    /**
     * Filtro para buscar por campos específicos dentro do JSON de fields.
     */
    public function scopeFilter($query, string $key, mixed $value)
    {
        return $query->whereJsonContains("fields->{$key}", $value);
    }


    /**
     * Get the the submissions for the form definition
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $rules = [
                'name'        => 'required|string|max:255',
                'group'       => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'fields'      => 'required|array',
                'all_fields.*.name' => 'required|string|distinct',
            ];

            $messages = [
                'all_fields.*.name.distinct' => 'Os nomes dos campos devem ser únicos.',
                'all_fields.*.name.required' => 'O nome de cada campo é obrigatório.',
            ];    

            $flatFields = [];
            $stack = $model->fields;

            while (!empty($stack)) {
                $field = array_shift($stack);
                if (is_array($field)) {
                    if (array_key_exists('name', $field)) {
                        $flatFields[] = $field;
                    } else {
                        $stack = array_merge($field, $stack);
                    }
                }
            }

            $data = $model->attributesToArray();
            $data['all_fields'] = $flatFields;

            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });
    }
}