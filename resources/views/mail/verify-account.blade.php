@extends('mail.mail-master')

@section('content')
    <p style="font-size: 1.2rem;">
        {{ __('auth.verifyLinkText') }}
    </p>
    <div style="text-align: center">
        <a href="{{ url($link) }}" 
            style="
                background-color: #4db6ac;    
                display: inline-block;
                height: 32px;
                font-size: 13px;
                font-weight: 500;
                line-height: 32px;
                padding: 0 12px;
                border-radius: 16px;
                margin-bottom: 5px;
                margin-right: 5px;
                color: white;
                font-size: 1rem;
                text-decoration: none;
                text-transform: uppercase;
        "> 
            <b>{{ __('auth.verifySubjectText') }}</b>
        </a>
    </div>
@endsection