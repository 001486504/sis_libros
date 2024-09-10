@extends('backend.layouts.master')

@section('title')
    Admin Edit - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .superuser-text {
            display: none;
            color: red;
            font-weight: bold;
        }
    </style>
@endsection

@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Admin Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.admins.index') }}">All Admins</a></li>
                        <li><span>Edit Admin - {{ $admin->name }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Edit Admin - {{ $admin->name }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="name">Admin Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter Name" value="{{ old('name', $admin->name) }}" required
                                           autofocus>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="email">Admin Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="Enter Email" value="{{ old('email', $admin->email) }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password">Password (Optional)</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Enter New Password">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password_confirmation">Confirm Password (Optional)</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" placeholder="Confirm New Password">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="roles">Assign Roles</label>
                                    <select name="roles[]" id="roles" class="form-control select2" multiple required>
                                        @foreach ($roles as $role)
                                            <option
                                                value="{{ $role->id }}" {{ in_array($role->id, old('roles', $admin->roles->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="username">Admin Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           placeholder="Enter Username" value="{{ old('username', $admin->username) }}"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <!-- Campo oculto que envía 0 si el checkbox no está marcado -->
                                    <input type="hidden" name="is_superuser" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_superuser"
                                           name="is_superuser"
                                           value="1" {{ old('is_superuser', $admin->is_superuser) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_superuser">Superuser</label>
                                </div>
                                <!-- Texto que se mostrará si el rol Superadmin es seleccionado -->
                                <p id="superuser-text" class="superuser-text">This user is a Superuser.</p>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                            <a href="{{ route('admin.admins.index') }}"
                               class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            // Asigna el ID del rol Superadmin (ajusta esto al ID correcto en tu base de datos)
            var superadminRoleId = 1;

            // Escucha los cambios en la selección de roles
            $('#roles').on('change', function () {
                var selectedRoles = $(this).val(); // obtiene los valores seleccionados (IDs)

                if (selectedRoles.includes(String(superadminRoleId))) {
                    // Muestra el texto de Superuser si se selecciona el rol Superadmin
                    $('#superuser-text').show();
                } else {
                    // Oculta el texto de Superuser si no está seleccionado el rol Superadmin
                    $('#superuser-text').hide();
                }
            });

            // Disparar el evento `change` al cargar la página para ajustar el estado inicial
            $('#roles').trigger('change');
        });
    </script>
@endsection
