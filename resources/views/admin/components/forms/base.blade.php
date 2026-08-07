@extends('admin.layouts.contentLayout')

@section('title', $form->getTitle() ?? 'Form Base')

@section('content')
    <div class="content-wrapper">
        <div class="row g-6">
            <div class="form-has-data">
                @include($form->getView(), [$form, $id, $class])
            </div>
        </div>
    </div>
@endsection
