@extends('layouts.auth')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">{{ __('Forgot Your Password?') }}</h1>
                    <p class="mb-4">We get it, stuff happens. Just enter your email address below
                        and we'll send you a link to reset your password!</p>
                </div>
                <form class="user" method="POST" action="{{ route('password.process') }}">
                    @csrf
                    <!-- username Input -->
                    <div class="form-group">
                        <input type="text" id="username" name="username"
                            class="form-control form-control-user @error('username') is-invalid @enderror"
                            placeholder="Enter Email Address..." value="{{ old('username') }}" required
                            autocomplete="username" autofocus>

                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Reset Password Button -->
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Reset Password') }}
                    </button>
                </form>
                <!-- Additional Links -->
                <hr>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
