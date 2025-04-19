@extends('layouts.app')

@section('content')
<style>
  /* Centrar verticalmente en toda la pantalla */
  .vh-center {
    height: 100vh;
  }
  .card-login {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
  }
  .card-login .card-header {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 500;
    font-size: 1.25rem;
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    text-align: center;
  }
  .btn-login {
    background-color: #0d6efd;
    color: #fff;
  }
  .btn-login:hover {
    background-color: #0b5ed7;
    color: #fff;
  }
</style>

<div class="container vh-center">
  <div class="row justify-content-center align-items-center h-100">
    <div class="col-md-5 col-lg-4">
      <div class="card card-login">
        <div class="card-header">
          Iniciar Sesión
        </div>
        <div class="card-body p-4">
          @if ($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
              >
              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                required
              >
              @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-login btn-lg">
                Ingresar
              </button>
            </div>

            {{-- Opcional: enlace para recuperación si lo agregas más adelante --}}
            {{-- <div class="text-center">
              <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div> --}}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
