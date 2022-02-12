@extends('layout.app')

@section('title')
    {{$title}} | ToDo
@endsection

@section('main')

    <main class="message">
        <div class="container">

            <div class="box blue-grey lighten-5">
                <h3>{{ $title }}</h3>
                <p>{{ $message }}</p>
            </div>

        </div>
    </main>

@endsection