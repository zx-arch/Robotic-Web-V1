@extends('cms_login.index_admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Pengguna</h2>
                <form action="{{ route('daftar_pengguna.save_update', ['user_id' => $user->id]) }}" method="POST" id="saveDataPengguna">
                    @csrf
                    @method('PUT')

                    <div class="form-group highlight-addon has-success">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control is-valid" id="username" name="username" value="{{ $user->username }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group highlight-addon has-success">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control is-valid" id="email" name="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role" required style="border: 1px solid green;">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="pengurus" {{ $user->role == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required style="border: 1px solid green;">
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" id="buttonSubmit">Simpan</button>
                    <a class="btn btn-warning" href="{{ route('daftar_pengguna.update_password', ['user_id' => encrypt($user->id)]) }}" title="Update" aria-label="Update" data-pjax="0">Update Password</a>

                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('saveDataPengguna');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const status = document.getElementById('status');
        const role = document.getElementById('role');
        const buttonSubmit = document.getElementById('buttonSubmit');


        username.addEventListener('input', function() {
            validateInput(username);
        });

        email.addEventListener('input', function() {
            validateInput(email);
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
            const regex = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;
            return regex.test(password);
        }

        function checkFormValidity() {
            const inputs = [username, email, status, role];
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
