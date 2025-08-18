<?php

namespace Uspdev\Forms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Demo extends Command
{
    protected $signature = 'forms:demo';
    protected $description = 'Adiciona dados de exemplo no BD do forms';

    public function handle()
    {
        
        $form = [
            [
                "name"     => "codpes",
                "type"     => "pessoa-usp",
                "label"    => "Pessoa",
                "required" => true
            ],
            [
                "name"     => "local",
                "type"     => "local-usp",
                "label"    => "Local",
                "required" => true
            ],
            [
                [
                    "name"     => "number",
                    "type"     => "number",
                    "label"    => "Campo de número",
                    "required" => false
                ],
                [
                    "name"     => "texto",
                    "type"     => "text",
                    "label"    => "Campo de texto",
                    "required" => false
                ]
            ],
            [
                [
                    "name"     => "textarea",
                    "type"     => "textarea",
                    "label"    => "Textarea",
                    "required" => false
                ]
            ],
            [
                [
                    "name"     => "data",
                    "type"     => "date",
                    "label"    => "Data",
                    "required" => true
                ],
                [
                    "name"     => "arquivo",
                    "type"     => "file",
                    "label"    => "Arquivo",
                    "accept"   => ".pdf, image/*",
                    "required" => false
                ]
            ],
            [
                [
                    "name"     => "rating",
                    "type"     => "select",
                    "label"    => "Avaliação",
                    "options"  => ["1", "2", "3", "4", "5"],
                    "required" => false
                ]
            ],
            [
                [
                    "name"     => "email",
                    "type"     => "email",
                    "label"    => "Email",
                    "required" => false
                ]
            ],
            [
                [
                    "name"     => "coddis",
                    "type"     => "disciplina-usp",
                    "label"    => "Disciplina",
                    "required" => true
                ]
            ],
            [
                [
                    "name"     => "patrimonio",
                    "type"     => "patrimonio-usp",
                    "label"    => "Patrimonio",
                    "required" => true
                ]
            ],
        ];


        DB::table('form_definitions')->insert([
            'name' => 'Demo Form',
            'group' => 'demo',
            'description' => 'Esse é um formulário de demonstração.',
            'fields' => json_encode($form),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);



        $this->info('Dados de exemplo adicionados ao banco de dados.');
    }
}
