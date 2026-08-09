@extends('shared::layouts.content')

@section('title', $form->getTitle() ?: 'Form')

@section('content')
    <div class="content-wrapper w-100">
        <div class="row g-6"><div class="form-has-data">@include($form->getView(), compact('form', 'id', 'class'))</div></div>
    </div>
@endsection
