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
            $('.datepicker').datepicker();

            $('.carousel').carousel({
                onCycleTo: (element) => {
                    let page = $(element).index();

                    $('.showcase-controls a.active').removeClass('active').addClass('waves-effect');

                    $('.showcase-controls .item-' + page + ' a').removeClass('waves-effect').addClass('active');
                }
            });

            $('#members').modal({
                onCloseStart: () => {
                    $('.member .modal').modal('close');
                    $('body').css('overflow', 'initial');
                }
            });
        });
    </script>

    <script>
        const BASE_URL = '{{ url('/') }}';
    </script>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection