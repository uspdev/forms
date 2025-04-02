@extends('layouts.app')

@section('content')
  <h2>Submissões para {{ $form->name }}</h2>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur porttitor eu enim lacinia commodo. Aenean ut
    nisl aliquam, dignissim lorem ut, convallis justo. Praesent sit amet semper orci.</p>
  @include('uspdev-forms::partials.submissions-table')
@endsection
