@extends('layout.app')

@section('main')

    <main class="task">
        
        <div class="task-box container row blue-grey lighten-5">
            
            <form action="@yield('form-url')">
                @yield('form-method')
                @csrf

                <div class="task-header col s12">
                    @yield('task-heading')
                </div>
                
                <div class="task-body col s12">

                    <div class="row">
                        <div class="input-field col s7">
                            <input id="task_name" type="text" name="task_name" class="validate">
                            <label for="task_name">Name</label>
                        </div>
                        <div class="input-field col s5">
                            @yield('task-top-options')
                        </div>
                    </div>

                    <div class="row">
                        <div class="task-controls-main col s8">
                            @yield('task-controls-main')
                        </div>
                        <div class="task-controls-side col s4">
                            @yield('task-controls-side')
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="task_description" name="task_description" class="materialize-textarea"></textarea>
                            <label for="task_description">Description</label>
                        </div>
                    </div>

                </div>
                
                <div class="task-footer col s12">
                    @yield('task-footer')
                </div>
                
            </form>
        </div>

        @yield('comments')

    </main>

@endsection