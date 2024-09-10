@extends('backend.layouts.master')

@section('title')
    Create Admin - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .superuser-text {
            display: none;
            color: red;
            font-weight: bold;
        }

        .disabled-option {
            color: #ccc;
        }
    </style>
@endsection

@section('admin-content')
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Create Admin</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.admins.index') }}">All Admins</a></li>
                    <li><span>Create Admin</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>

<div class="main-content-inner" x-data="roleSelection()">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Create Admin</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Admin Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Admin Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="roles">Assign Roles</label>
                                <select name="roles[]" id="roles" class="form-control select2" multiple x-model="selectedRoles" @change="handleRoleChange">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            :disabled="isSuperuserSelected && {{ $role->id }} != 1 || !canEnableRole({{ $role->id }})"
                                            x-bind:class="{ 'disabled-option': isSuperuserSelected && {{ $role->id }} != 1 }">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="username">Admin Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <p x-show="isSuperuserSelected" class="superuser-text">This user is a Superuser.</p>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('roleSelection', () => ({
                selectedRoles: @json(old('roles', [])),

                get isSuperuserSelected() {
                    return this.selectedRoles.includes('1');
                },

                init() {
                    // Inicializar Select2
                    $('.select2').select2();

                    // Monitorear cambios en el select
                    $('#roles').on('change', () => {
                        this.selectedRoles = $('#roles').val();
                        this.handleRoleChange();
                    });

                    this.handleRoleChange();
                },

                handleRoleChange() {
                    const selectElement = $('#roles');

                    // Deshabilitar o habilitar opciones según la selección de Superuser
                    selectElement.find('option').each((_, option) => {
                        const value = $(option).val();
                        if (this.isSuperuserSelected && value !== '1') {
                            $(option).prop('disabled', true);
                        } else {
                            $(option).prop('disabled', false);
                        }
                    });

                    // Deshabilitar Superuser si hay otros roles seleccionados
                    const hasOtherRoles = this.selectedRoles.length > 0 && !this.isSuperuserSelected;
                    selectElement.find('option[value="1"]').prop('disabled', hasOtherRoles);

                    // Actualizar visualmente el estado deshabilitado de las opciones
                    selectElement.trigger('change.select2');
                },

                canEnableRole(roleId) {
                    return !this.isSuperuserSelected || roleId === 1;
                }
            }));
        });
    </script>
@endsection
