@extends('task.task-master')

@section('form-url')
    {{ url('/tasks/' . $task->id) }}
@endsection

@section('form-method')
    @method('PATCH')
    <input type="hidden" name="task" value="{{ $task->id }}">
@endsection

@section('task-heading')
    
    @include('components.task-categories')

@endsection

@section('task-top-options')
    
    @include('components.task-visibility')

@endsection

@section('task-controls-main')
    @include('components.task-isdone')
@endsection

@section('task-footer')
    
    <button type="submit" class="waves-effect waves-light chip text-white btn teal lighten-2"> {{ __('tasks.saveButton') }} </button>

@endsection