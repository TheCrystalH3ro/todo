@extends('layout.master')

@section('main_attr')

    class="app grey darken-3"

@endsection

@section('content')

    @include('components.navbar')

    @yield('main')

@endsection

@section('scripts')
    
    <script>
        $(document).ready(() => {
            $(".dropdown-trigger").dropdown({
                alignment: 'right',
                coverTrigger: false,
                closeOnClick: false,
                constrainWidth: false,
            });

            $('.modal').modal();
            $('select').formSelect();
        });
    </script>

    <script>
        const BASE_URL = '{{ url('/') }}';
    </script>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection