<?php

namespace Uspdev\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Forms\Models\FormDefinition;

class FormDefinitionController extends Controller
{
    public function index()
    {
        $formDefinitions = FormDefinition::all();
        return view('uspdev-forms::form.index', compact('formDefinitions'));
    }
    
    public function show(FormDefinition $formDefinition)
    {
        return $formDefinition;
    }

    public function create()
    {
        return view('uspdev-forms::form.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'required|string',
            'fields' => 'required|json',
        ]);

        $fields = json_decode($request->input('fields'), true);

        FormDefinition::create([
            'name' => $request->input('name'),
            'group' => $request->input('group'),
            'description' => $request->input('description'),
            'fields' => $fields,
        ]);
        
        return redirect()->route('form-definitions.index')->with('alert-success', 'Form Definition created successfully.');
    }

    public function edit(FormDefinition $formDefinition)
    {
        return view('uspdev-forms::form.create', compact('formDefinition'));
    }

    public function update(Request $request, FormDefinition $formDefinition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'fields' => 'required|string'
        ]);

        $formDefinition->fields = json_decode($request->input('fields'), true);
        $formDefinition->save();

        $formDefinition->update($request->only(['name', 'group', 'description']));

        return redirect()->route('form-definitions.index');
    }

    public function destroy(FormDefinition $formDefinition)
    {
        $formDefinition->delete();

        return redirect()->route('form-definitions.index');
    }

}
