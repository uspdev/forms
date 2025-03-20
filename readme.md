# Forms

Forms é uma biblioteca **uspdev** que permite gerar formulários dinâmicos a partir de definição armazenado em banco de dados e opcionalmente persiste os resultados.

## Features

- Gera formulários a partide definição em BD;
- Supporta estilos em Bootstrap 4 e 5;
- Processa submissão dos formulários com validação e persistência;
- Integra com aplicações Laravel 11 em diante.

## Installation

1. **Instale a biblioteca por meio do  Composer:**

  ```bash
   composer require uspdev/forms
  ```
    
2. **Publish the configuration and migrations:**

Run the following commands to publish the package's configuration and migrations:

    php artisan vendor:publish --tag=forms-migrations
    php artisan vendor:publish --tag=forms-config


3. **Run migrations:**

After publishing, run the migrations to create the necessary tables:

    php artisan migrate

## Configuration

You can customize the package's settings by modifying the config/forms.php file. Here, you can set the default Bootstrap version.

## Usage

1. **Crie uma entrada na tabela form_definitions**

```php
$form = [
    'name' => 'contact_form', // chave de acesso ao form
    'group' => config('uspdev-forms.defaultGroup'),  // permite agrupar vários forms
    'description' => 'A form for user inquiries.',
    'fields' => [
      [
        'name' => 'codpes',
        'type' => 'pessoa-usp',
        'label' => 'Pessoa (select2)',
        'required' => true
      ],
      [
        [
          'name' => 'name',
          'type' => 'text',
          'label' => 'Nome (text)',
          'required' => true
        ],
        [
          'name' => 'email',
          'type' => 'email',
          'label' => 'Email (email)',
          'required' => false
        ],
      ],
      [
        'name' => 'rating', 
        'type' => 'select', 
        'label' => 'Avaliação (select)', 
        'options' => [
          '1', '2', '3', '4', '5'
        ]
      ],
      [
        'name' => 'message', 
        'type' => 'textarea', 
        'label' => 'Mensagem (textarea)', 
      ],
    ],
];

FormDefinition::create($form);

```           
                 
2. **Generate the form in your view:**

Use the FormGenerator class to render the form in your Blade template:

```php
use Uspdev\Forms\Forms;

$form = new Form($key = null, ['action' => route('sua-rota-do-action')]);
$formHtml = $form->generateHtml('contact_form'); // conforme definido em $form

// ....
```

3. **Handle form submissions:**

In your controller, handle the form submission by saving the data to the database:

```php
public function store(Request $request)
{
  $form = (new Form())->handleSubmission($request);
  
  // ....
}
```
    
## Contributing

Contributions are welcome! Please follow these steps to contribute:

Fork the repository.
Create a new branch (git checkout -b feature/YourFeature).
Make your changes and commit them (git commit -m 'Add some feature').
Push to the branch (git push origin feature/YourFeature).
Create a new Pull Request.

## License

This package is licensed under the MIT License. See the LICENSE file for details.


### Summary of Contents
- **Package Overview**: Describes what the package does.
- **Features**: Highlights key functionalities.
- **Installation Steps**: Provides detailed installation instructions.
- **Configuration Details**: Guides on customizing settings.
- **Usage Examples**: Shows how to create a YAML form and use it in your application.
- **Contribution Guidelines**: Encourages contributions with clear steps.
- **License Information**: Indicates the licensing of the package.






