<?php

namespace Uspdev\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Uspdev\Forms\Form;
use Uspdev\Forms\Models\FormDefinition;
use Uspdev\Forms\Models\FormSubmission;

class SubmissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:' . config('uspdev-forms.adminGate'));
    }

    public function index(FormDefinition $formDefinition)
    {
        $config = [
            'editable' => true,
            'name' => $formDefinition->name,
            'action' => route('form-submissions.store', $formDefinition->id),
        ];
        $form = new Form($config);
        $form->user = Auth::user();
        $form->admin = Gate::allows('manager', $form->user) ? true : false;

        return view('uspdev-forms::submission.index', compact('form', 'formDefinition'));
    }

    public function create(FormDefinition $formDefinition)
    {
        $definition = $formDefinition;
        $submission = null;
        
        $config = [
            $key = null,
            'action' => route('form-submissions.store', $formDefinition),
        ];
        $form = new Form($config);
        $formHtml = $form->generateHtml($formDefinition->name);

        return view('uspdev-forms::submission.edit', compact('definition', 'submission', 'formHtml'));
    }

    public static function edit(FormDefinition $formDefinition, FormSubmission $formSubmission)
    {
        $formHtml = (new Form(['method' => 'PUT']))->generateHtml($formDefinition->name, $formSubmission);

        return view('uspdev-forms::submission.edit')->with([
            'formHtml' => $formHtml,
            'submission' => $formSubmission,
            'definition' => $formDefinition,
        ]);
    }

    public function store(FormDefinition $formDefinition, Request $request)
    {
        $submission = (new Form(['editable' => true]))->handleSubmission($request);

        if ($submission instanceof FormSubmission) {
            return redirect()->route('form-submissions.index', $formDefinition)
                ->with('alert-success', 'Submissão criada com sucesso!');
        }
        return redirect()->back()->withInput()
            ->with('alert-danger', $submission);
    }

    public static function update(Request $request, FormDefinition $formDefinition, FormSubmission $formSubmission)
    {
        $submission = (new Form(['editable' => true]))
            ->updateSubmission($request, $formSubmission->id);

        if ($submission instanceof FormSubmission) {
            return redirect(route('form-submissions.index', $formDefinition))
                ->with('alert-success', 'Submissão atualizada com sucesso!');
        }
        return redirect()->back()->withInput()
            ->with('alert-danger', 'Erro: ' . $submission);
    }

    public static function destroy(FormDefinition $formDefinition, FormSubmission $formSubmission)
    {
        $form = (new Form())->deleteSubmission($formSubmission->id, Auth::user());

        return redirect(route('form-submissions.index', $formDefinition))
            ->with('alert-success', 'Submissão enviada para lixeira com sucesso!');
    }
}
