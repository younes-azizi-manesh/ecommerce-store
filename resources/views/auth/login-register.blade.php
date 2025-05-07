@extends('auth.layouts.master-auth')

@section('head-tag')
    <title>ورود / ثبت نام</title>
    <style>
        .login-form {
            max-width: 400px;
            width: 90%;
            padding: 20px;
            margin: auto;
        }
        .login-form .form-group {
            margin-bottom: 1rem;
        }
        .login-form .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }
        .login-form .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-form .btn-primary {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-form .text-center {
            text-align: center;
        }
        .login-form .alert_required {
            display: block;
            margin-top: 0.5rem;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .login-form {
                padding: 15px;
            }
            .login-form h1 {
                font-size: 18px;
            }
            .login-form .form-group input {
                padding: 8px;
            }
            .login-form .btn-primary {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                width: 100%;
                padding: 10px;
            }
            .login-form h1 {
                font-size: 16px;
            }
            .login-form .form-group label {
                font-size: 12px;
            }
            .login-form .form-group input {
                font-size: 12px;
            }
            .login-form .btn-primary {
                font-size: 14px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="flex min-h-screen items-center justify-center bg-background">
        <form action="{{ route('auth.login-register') }}" method="post" class="login-form">
            @csrf
            <div class="container">
                <div class="mx-auto rounded-xl bg-muted p-5 shadow-base">
                    <!-- Logo -->
                    <a href="{{ route('home') }}">
                        <img src="" class="mx-auto mb-5 w-40" alt="" />
                    </a>
                    <div class="form-group">
                        <label for="username">شماره موبایل یا ایمیل</label>
                        <input type="text" id="username" value="{{ old('id') }}" name="id" class="form-control" placeholder="شماره موبایل یا ایمیل" />
                        @error('id')
                        <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn-primary">ورود</button>
                    </div>
                    <p class="text-center text-sm text-text/90">
                        با ورود به فروشگاه,
                            کلیه قوانین
                        را می‌پذیرم
                    </p>
                </div>
            </div>
        </form>
    </div>
@endsection