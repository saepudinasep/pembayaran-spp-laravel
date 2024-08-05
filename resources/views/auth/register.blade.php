@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">{{ __('Register') }}</h1>
                </div>
                <form class="user" method="POST" action="{{ route('register.process') }}">
                    @csrf
                    <!-- Email Input -->
                    <div class="form-group">
                        <input type="email" id="email" name="email"
                            class="form-control form-control-user @error('email') is-invalid @enderror"
                            placeholder="Enter Email Address..." value="{{ old('email') }}" autocomplete="off" required
                            autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Name Input -->
                    <div class="form-group">
                        <input type="text" id="name" name="name"
                            class="form-control form-control-user @error('name') is-invalid @enderror"
                            placeholder="Enter Full Name..." value="{{ old('name') }}" autocomplete="off" required>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Username Input -->
                    <div class="form-group">
                        <input type="text" id="username" name="username"
                            class="form-control form-control-user @error('username') is-invalid @enderror"
                            placeholder="Enter Username..." value="{{ old('username') }}" autocomplete="off" required>

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
                            placeholder="Password" autocomplete="off" required>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Confirm Password Input -->
                    <div class="form-group">
                        <input type="password" id="password-confirm" name="password_confirmation"
                            class="form-control form-control-user" placeholder="Confirm Password" autocomplete="off"
                            required>
                    </div>
                    <!-- Register Button -->
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Register') }}
                    </button>
                </form>
                <!-- Forgot Password Link -->
                <hr>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <script>
            toastr.success('{{ session('status') }}');
        </script>
    @endif

    @if ($errors->any())
        <script>
            toastr.error('{{ $errors->first() }}');
        </script>
    @endif
@endsection
