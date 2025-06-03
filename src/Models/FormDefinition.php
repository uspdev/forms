<?php

namespace Uspdev\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Uspdev\Forms\Models\FormSubmission;

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
}
