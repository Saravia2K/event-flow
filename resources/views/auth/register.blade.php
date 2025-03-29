@extends('templates.base')

@section('body-class', 'bg-gradient-primary');

@section('styles')
    <style>
        .bg-register-image img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .error {
            color: red;
            font-size: 12px;
            padding-left: 15px
        }

        select.form-control {
            padding: 0 1rem !important;
            height: 3rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <img src="/images/register-bg.jpg" alt="Register form">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crea una cuenta!</h1>
                            </div>
                            <form class="user" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="name"
                                            name="name" placeholder="Nombre(s)" required>
                                        @error('name')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="last_name"
                                            name="last_name" placeholder="Apellido(s)" required>
                                        @error('last_name')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email"
                                        name="email" placeholder="Correo electrónico" required>
                                    @error('email')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <select class="form-control form-control-user form-select" id="role" name="role"
                                        required>
                                        <option disabled selected>----- Selecciona un rol -----</option>
                                        <option value="admin">Organizador</option>
                                        <option value="participant">Participante</option>
                                    </select>
                                    @error('role')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="password"
                                            name="password" placeholder="Contraseña" required>
                                        @error('password')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Repite la contraseña" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrarse
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('login-form') }}">¿Ya tienes una cuenta? ¡Inicia
                                    sesión!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
