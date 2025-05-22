<?php

namespace Uspdev\Forms\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Uspdev\UspTheme\Events\UspThemeParseKey;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if (config('uspdev-forms.prefix')) {
            Event::listen(function (UspThemeParseKey $event) {
                if (isset($event->item['key']) && $event->item['key'] == 'uspdev-forms') {
                    $event->item = [
                        'text' => '<span class="text-danger">Formulários</span>',
                        'url' => config('uspdev-forms.prefix') . '/form-definitions',
                        'title' => 'Formulários',
                        'can' => 'admin',
                    ];
                }
                return $event->item;
            });
        }
    }
}
