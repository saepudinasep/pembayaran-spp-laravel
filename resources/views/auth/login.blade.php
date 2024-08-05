@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">{{ __('Login') }}</h1>
                </div>
                <form class="user" method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Username Input -->
                    <div class="form-group">
                        <input type="username" id="username" name="username"
                            class="form-control form-control-user @error('username') is-invalid @enderror"
                            placeholder="Enter Username..." value="{{ old('username') }}" required autofocus
                            autocomplete="off">

                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Password Input -->
                    <div class="form-group">
                        <input type="password" id="password" name="password"
                            class="form-control form-control-user @error('password') is-invalid @enderror"
                            placeholder="Password" required autocomplete="off">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Remember Me Checkbox -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                    </div>
                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Login') }}
                    </button>
                </form>
                <!-- Forgot Password Link -->
                <hr>
                <div class="text-center">
                    @if (!$userExists)
                        <a class="small" href="{{ route('register.index') }}">Create an Account!</a>
                    @endif
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('password.reset') }}">Forgot Password?</a>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    @if (session('status'))
        <script>
            toastr.success('{{ session('status') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif

    @if ($errors->any())
        <script>
            toastr.error('{{ $errors->first() }}');
        </script>
    @endif
@endsection
