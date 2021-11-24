@extends('layout.app')

@section('main')

    <main class="task">
        
        <form action="@yield('form-url')">
            @yield('form-method')
            @csrf
            
            <div class="row">
                <div class="input-field col s-12">
                    
                </div>
            </div>
            
        </form>

    </main>

@endsection