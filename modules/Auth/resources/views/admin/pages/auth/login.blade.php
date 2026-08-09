@extends('admin.layouts.blankLayout')

@section('title', 'Login Basic - Pages')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <div class="card px-sm-6 px-0">
                    <div class="card-body">

                        <div class="app-brand justify-content-center">
                            <a href="#" class="app-brand-link gap-2">
                                <img src="{{ asset('assets/img/favicon/logo.png') }}" class="rounded-5" alt="logo"
                                    style="width: 100%; max-width: 128px">
                            </a>
                        </div>

                        @if (config('variables.templateName'))
                            <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! </h4>
                        @endif
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>

                        <form action="{{ route('login.authenticate') ?? '' }}" method="post" class="login-form"
                            data-bb-toggle="login-form-toggle" novalidate>
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email or Username</label>

                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-email" class="input-group-text"><i
                                            class="icon-base bx bx-user"></i></span>
                                    <input value="admin@admin.vn" type="text" class="form-control" id="email"
                                        name="email" {{ old('email') ? 'value=' . old('email') : '' }}
                                        placeholder="Enter your email or username" autofocus required>
                                </div>
                                <div class="invalid-feedback">Email is required.</div>
                                @if ($errors->any() && key_exists('email', $errors->messages()))
                                    @foreach ($errors->messages()['email'] as $message)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-password" class="input-group-text">
                                        <span class="icon-base bx bx-lock"></span>
                                    </span>
                                    <input value="admin@admin.vn" type="password" id="password" class="form-control"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>

                                <div class="invalid-feedback">Password is required.</div>
                                @if ($errors->any() && key_exists('password', $errors->messages()))
                                    @foreach ($errors->messages()['password'] as $message)
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between mt-8">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" name="is_remember_me" type="checkbox"
                                            id="remember-me">
                                        <label class="form-check-label" for="remember-me">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                            @if ($errors->any() && key_exists('error', $errors->messages()))
                                @foreach ($errors->messages()['error'] as $message)
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @endforeach
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
