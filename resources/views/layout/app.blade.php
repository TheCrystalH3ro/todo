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
            $('.chips').chips({
                placeholder: '{{ __('tasks.sharedWith') }}',
                @if (isset($shared_with))
                data: [
                    @foreach (array_filter($shared_with) as $name)
                    {tag: '{{ $name }}'},
                    @endforeach
                ],
                @endif
                onChipAdd: (element) => {
                    var data = M.Chips.getInstance($(element)).chipsData;

                    let inputs = $('.shared-inputs');

                    inputs.append('<input type="hidden" name="shared_with[]" value="'+ encodeURI(data.at(-1).tag) +'">');
                },
                onChipDelete: (element) => {
                    var data = M.Chips.getInstance($(element)).chipsData;

                    let inputs = $('.shared-inputs');
                    inputs.empty();

                    data.forEach((item, key) => {
                        inputs.append('<input type="hidden" name="shared_with[]" value="'+ encodeURI(item.tag) +'">');
                    });

                },
            });

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