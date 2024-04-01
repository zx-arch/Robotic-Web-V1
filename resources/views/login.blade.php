@extends('cms.Index')

<style>
    .clients .row {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .clients img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        height: 70px;
    }
</style>

@section('content')

    <section id="hero" class="d-flex align-items-center" style="background-color: #37517e">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-2 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">

                    <div class="card">
                        <div class="card-body" id="login-form"> <!-- Add an id to identify the form -->

                            <form action="{{route('submit_form.login')}}" method="POST" id="formLogin">
                                @csrf

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Username atau Email</label>
                                    <input id="username_or_email" type="text" class="form-control" name="username_or_email" value="{{ old('username_or_email') }}" required autofocus>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    @error('password')
                                        <span role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" name="login" id="buttonLogin" class="btn btn-primary">Login</button>
                                <button type="button" id="buttonRegister" class="btn btn-success">Register</button>
                            </form>

                            <div id="formRegister" style="display: none;">
                                <form action="{{route('register.submit')}}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Username</label>
                                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="" disabled {{!old('role') ? 'selected' : ''}}>Select Role ..</option>
                                            <option value="pengurus" {{ old('role') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                        <div class="invalid-feedback"></div>
                                        @error('password')
                                            <span role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" name="register" id="buttonSubmitRegister" class="btn btn-primary">Register</button>
                                
                                </form>
                            </div>

                            @if (session()->has('success_register'))
                                <div class="alert alert-success">{{ session('success_register') }}</div>
                            @endif

                            @if (session()->has('error_register'))
                                <div class="alert alert-warning">{{ session('error_register') }}</div>
                            @endif

                            @if ($errors->has('email'))
                                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                            @endif

                            @error('message')
                                <div class="alert alert-warning">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    
                </div>

                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in">
                    <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('buttonRegister').addEventListener('click', function () {
            document.getElementById('formRegister').style.display = 'block';
            document.getElementById('formLogin').style.display = 'none';
        });

        const formLogin = document.getElementById('formLogin');
        const formRegister = document.getElementById('formRegister');
        const buttonLogin = document.getElementById('buttonLogin');
        const buttonSubmitRegister = document.getElementById('buttonSubmitRegister');

        formRegister.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default formLogin submission

            // Reset styles and error messages
            const inputsRegister = formRegister.querySelectorAll('input, textarea');
            inputsRegister.forEach(input => {
                input.classList.remove('is-invalid');
                input.classList.remove('is-valid');
                input.nextElementSibling.textContent = '';
            });

            // Validate each input
            let isValid = true;
            inputsRegister.forEach(input => {
                if (!input.checkValidity()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = input.validationMessage;
                } else {
                    input.classList.add('is-valid');
                }
            });

            if (isValid) {
                buttonSubmitRegister.disabled = false;
                buttonSubmitRegister.style.pointerEvents = 'auto';
                buttonSubmitRegister.style.opacity = '1';
                // If form is valid, you can submit it here
                formRegister.submit();
            }
        });

        // Add event listener to validate on input change
        const inputFieldsRegister = formRegister.querySelectorAll('input, textarea');

        inputFieldsRegister.forEach(input => {
            input.addEventListener('keyup', function() {
                if (!this.checkValidity()) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    this.nextElementSibling.textContent = this.validationMessage;
                
                } else if (this.id === 'username' && !/^[a-zA-Z]+$/.test(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    this.nextElementSibling.textContent = 'Username should only contain letters';
                
                } else if (this.id === 'email' && !isValidEmail(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    this.nextElementSibling.textContent = 'Please enter a valid email address';
                
                } else if (this.id === 'role' && this.value.trim() != '') {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    this.nextElementSibling.textContent = 'Choose Role..';
                
                } else {
                    if (this.id === 'username' && !/^[a-zA-Z]{3,}$/.test(input.value)) {
                        isValid = false;
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                        input.nextElementSibling.textContent = 'Username should have at least 3 letters';
                    } else {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                        this.nextElementSibling.textContent = '';
                    }
                }

                // Disable submit button if form is not valid
                if (!formRegister.checkValidity()) {
                    buttonSubmitRegister.disabled = true;
                    buttonSubmitRegister.style.pointerEvents = 'none';
                    buttonSubmitRegister.style.opacity = '0.5';
                } else {
                    buttonSubmitRegister.disabled = false;
                    buttonSubmitRegister.style.pointerEvents = 'auto';
                    buttonSubmitRegister.style.opacity = '1';
                }
            });
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>