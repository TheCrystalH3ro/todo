@extends('layout.master')

@section('main_attr')

    class="app grey darken-3"

@endsection

@section('content')

    @include('components.navbar')

    @yield('main')

@endsection