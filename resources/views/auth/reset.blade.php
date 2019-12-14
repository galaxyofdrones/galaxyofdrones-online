@extends('layouts.main')

@php
    $subtitle = __('messages.auth.reset');
@endphp

@section('subtitle', $subtitle)

@section('body')
    <section id="app" class="container-app container-app-standalone">
        <a class="logo" href="{{ route('home') }}">
            <img class="img-fluid"
                 src="{{ mix('images/logo.png') }}"
                 alt="{{ setting('title') }}">
        </a>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-key"></i>
                    {{ $subtitle }}
                </h5>
            </div>
            <form action="{{ route('password.update') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="card-body">
                    <div class="form-group">
                        <label class="required" for="email">
                            {{ __('validation.attributes.email') }}
                        </label>
                        <input id="email"
                               class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="{{ __('validation.attributes.email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="password">
                            {{ __('validation.attributes.password') }}
                        </label>
                        <input id="password"
                               class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               type="password"
                               name="password"
                               placeholder="{{ __('validation.attributes.password') }}" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group mb-0">
                        <label class="required" for="password_confirmation">
                            {{ __('validation.attributes.password_confirmation') }}
                        </label>
                        <input id="password_confirmation"
                               class="form-control form-control-lg {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                               type="password"
                               name="password_confirmation"
                               placeholder="{{ __('validation.attributes.password_confirmation') }}" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">
                        {{ $subtitle }}
                    </button>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-1">
                        {{ __('messages.auth.already_have') }}
                        <a href="{{ route('login') }}">
                            {{ __('messages.auth.login') }}
                        </a>
                    </p>
                    <p class="mb-0">
                        {{ __('messages.auth.dont_have') }}
                        <a href="{{ route('register') }}">
                            {{ __('messages.auth.register') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('javascripts')
    @include('partials.flash')
@endpush
