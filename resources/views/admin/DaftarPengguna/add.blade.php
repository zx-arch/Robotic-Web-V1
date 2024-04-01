@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Memuat CSS untuk jQuery UI (dibutuhkan untuk styling datepicker) -->
    
@section('content')
<div class="container-fluid">

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default">
                    <div class="card-header card-header-border-bottom">
                            <h5>Create User</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('daftar_pengguna.save_add_user')}}" id="addDaftarPenggunaForm" method="post">
                            @csrf
                            <div class="form-group highlight-addon has-success">
                                <label for="username">Username: </label>
                                <input type="text" name="username" id="username" required class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group highlight-addon has-success">
                                <label for="name">Email: </label>
                                <input type="email" name="email" id="email" required class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group highlight-addon has-success">
                                <label for="name">Password: </label>
                                <input type="password" name="password" id="password" required class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group highlight-addon has-success">
                                <label for="status">Status: </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" disabled selected>Select Status ..</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group highlight-addon has-success">
                                <label for="role">Role: </label>

                                <select name="role" id="role" class="form-control">
                                    <option value="" disabled selected>Select Role ..</option>
                                    <option value="pengawas">Pengawas</option>
                                    <option value="user">User</option> 
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <button type="submit" id="buttonSubmit" class="btn btn-primary">Submit</button>

                        </form>
                        
                        @if (session()->has('success_submit_save'))
                            <p class="text-success">{{session('success_submit_save')}}</p>
                        @endif

                        @if (session()->has('error_submit_save'))
                            <p class="text-danger">{{session('error_submit_save')}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('addDaftarPenggunaForm');
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const email = document.getElementById('email');
        const status = document.getElementById('status');
        const role = document.getElementById('role');
        const buttonSubmit = document.getElementById('buttonSubmit');

        buttonSubmit.disabled = true;
        buttonSubmit.style.pointerEvents = 'none';
        buttonSubmit.style.opacity = '0.5';

        username.addEventListener('keyup', function() {
            validateInput(username);
        });

        email.addEventListener('keyup', function() {
            validateInput(email);
        });

        password.addEventListener('keyup', function() {
            validateInput(password);
        });
        
        status.addEventListener('change', function() {
            validateInput(status);
        });

        role.addEventListener('change', function() {
            validateInput(role);
        });

        function validateInput(input) {
            if (input.value.trim() === '') {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                input.nextElementSibling.textContent = 'Please fill out this field.';

            } else if (input === username && !/^[a-zA-Z]{3,}$/.test(input.value)) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                input.nextElementSibling.textContent = 'Username should have at least 3 letters';
            
            } else if (input === password && !validatePassword(input.value)) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                input.nextElementSibling.textContent = 'Password should have at least 8 characters with number combination';
            
            } else if (input === email && !isValidEmail(input.value)) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                input.nextElementSibling.textContent = 'Please enter a valid email address.';

            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                input.nextElementSibling.textContent = '';
            }

            checkFormValidity();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePassword(password) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
            return regex.test(password);
        }



        function checkFormValidity() {
            const inputs = [username, email, password, status, role];
            const isValid = inputs.every(input => input.classList.contains('is-valid'));
            
            if (isValid) {
                buttonSubmit.disabled = false;
                buttonSubmit.style.pointerEvents = 'auto';
                buttonSubmit.style.opacity = '1';
            } else {
                e.preventDefault();
            }
        }

        form.addEventListener('submit', function (e) {
            checkFormValidity();
        });
    });

</script>