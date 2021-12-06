@extends('task.task-master')

@section('form-url')
    {{ url('/tasks') }}
@endsection

@section('form-method')
    @method('POST')
@endsection

@section('task-heading')
    
    @include('components.task-categories')

@endsection

@section('task-top-options')
    
    @include('components.task-visibility')

@endsection

@section('task-footer')
    
    <button type="submit" class="waves-effect waves-light chip text-white btn teal lighten-2"> {{ __('tasks.createButton') }} </button>

@endsection