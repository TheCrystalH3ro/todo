@extends('layout.app')

@section('main')

    <main class="task single">
        
        <div class="task-box container row blue-grey lighten-5">

            <div class="task-header col s12">
                @include('components.category-list')
            </div>
            
            <div class="task-body col s12">

                <div class="task-top row">
                    <div class="col s7">
                        <h4>
                            {{ $task->name }}
                        </h4>
                    </div>
                    <div class="col s5">
                        
                        @if ($task->visibility)
                            
                            <span class="chip">
                                <i class="material-icons">visibility</i> : {{ __('tasks.public') }}
                            </span>

                        @else

                            <span class="chip">
                                <i class="material-icons">visibility_off</i> : {{ __('tasks.private') }}
                            </span>
                            
                        @endif

                    </div>
                </div>

                <div class="task-controls row">
                    <div class="task-controls-main col s8">                  
                        @include('components.task-isdone')
                        @include('components.task-members')
                    </div>
                    <div class="task-controls-side col s4">
                        
                        @if ($isOwner)
                            
                            <form action="" method="POST">
                                @method('DESTROY')
                                @csrf
                                
                                <input type="hidden" name="delete-task" value="{{ $task->id }}">
                                
                                <button class="waves-effect waves-light btn-floating red darken-1" type="submit">
                                    <i class="material-icons">delete_forever</i>
                                </button>
                                
                            </form>
                        
                        @endif

                    </div>
                </div>

                <div class="task-detail row">
                    <div class="col s12">
                        <p>
                            {{ $task->description }}
                        </p>
                    </div>
                </div>

            </div>
            
            <div class="task-footer col s12">
                
                @if ($isMember)
                
                    <a href="{{ url('tasks/'. $task->id . '/edit') }}" type="submit" class="waves-effect waves-light chip text-white btn teal lighten-2"> 
                        <i class="material-icons">edit</i> 
                        {{ __('tasks.editButton') }} 
                    </a>
                
                @endif
                
                <span>
                    {{ __('tasks.owner') }}:
                    <a href="{{ url('/user/' . $task->owner->id) }}">
                        {{ $task->owner->name }}
                    </a>
                </span>

            </div>
                
        </div>

        @include('components.comments')

    </main>

@endsection