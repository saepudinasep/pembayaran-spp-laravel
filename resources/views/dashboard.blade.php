@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to SB Admin 2</h1>
    <p>This is a sample page using SB Admin 2 layout.</p>
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
