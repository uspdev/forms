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
     * Get the the submissions for the form definition
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

}
