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
                        <input type="text" class="form-control" disabled id="username" style="pointer-events: none; opacity: 0.6" name="username" value="{{ $user->username }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group highlight-addon has-success">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" disabled style="pointer-events: none; opacity: 0.6" id="email" name="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group highlight-addon has-success">
                        <label for="password">Enter New Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group highlight-addon has-success">
                        <label for="confirm_password">Enter Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="buttonSubmit">Simpan</button>
                    <a class="btn btn-warning" href="{{ route('daftar_pengguna.update', ['user_id' => encrypt($user->id)]) }}" title="Update" aria-label="Update" data-pjax="0">Kembali</a>

                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    
document.addEventListener("DOMContentLoaded", function() {
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const buttonSubmit = document.getElementById('buttonSubmit');
    buttonSubmit.disabled = true;
    buttonSubmit.style.pointerEvents = 'none';
    buttonSubmit.style.opacity = '0.5';
    password.innerHTML = '';

    password.addEventListener('input', function() {
        if (password.value !== confirm_password.value) {
                password.classList.remove('is-valid');
                password.classList.add('is-invalid');
                password.nextElementSibling.textContent = 'Password and confirm password do not match!';
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
                password.nextElementSibling.textContent = '';
            }
            checkFormValidity();
    });

    confirm_password.addEventListener('keyup', function() {
    if (password.value !== confirm_password.value) {
            confirm_password.classList.remove('is-valid');
            confirm_password.classList.add('is-invalid');
            confirm_password.nextElementSibling.textContent = 'Password and confirm password do not match!';
        } else {
            confirm_password.classList.remove('is-invalid');
            confirm_password.classList.add('is-valid');
            confirm_password.nextElementSibling.textContent = '';
        }
        checkFormValidity();

    });

    function validateInput(input) {
        
        password.addEventListener('input', function() {
            validateInput(password);
        });
        if (password.value !== confirm_password.value) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            input.nextElementSibling.textContent = 'Password and confirm password do not match!';
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            input.nextElementSibling.textContent = '';
        }

        checkFormValidity();
    }

    function checkFormValidity(e) {
        const inputs = [password, confirm_password];
        const isValid = inputs.every(input => input.classList.contains('is-valid'));
        
        if (isValid) {
            buttonSubmit.disabled = false;
            buttonSubmit.style.pointerEvents = 'auto';
            buttonSubmit.style.opacity = '1';
        } else {
            buttonSubmit.disabled = true; // Set disabled to true if form is not valid
            buttonSubmit.style.pointerEvents = 'none';
            buttonSubmit.style.opacity = '0.5';
        }
    }
});

</script>
