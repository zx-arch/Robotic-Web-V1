<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
        }

        .container {
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            max-width: 500px; /* Increase the maximum width */
            width: 100%; /* Ensure it stretches the full width up to max-width */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .form-control {
            border: 2px solid #007bff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 32px;
            color: #333;
            animation: slideDown 1s ease;
        }

        .form-header h2 {
            font-size: 20px;
            color: #666;
            animation: slideUp 1s ease;
        }

        .form-header h4 {
            font-size: 18px;
            color: #8e8a8a;
            animation: slideUp 1s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-custom {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            transition: background 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
        }

        .form-group {
            margin-bottom: 25px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-header">
            <h1>Registrasi Peserta</h1>
            <h2>{{$event->event_name ?? ''}}</h2>
            <h4>{{$event->location}}</h4>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{route('events.registerParticipant')}}" method="post" id="addParticipant">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback"></div>
            </div>

            <input type="hidden" name="event[]" value="{{$event}}">
    
            <div class="form-group highlight-addon has-success">
                <label for="name">Email: </label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-custom btn-block" id="buttonSubmit">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('addParticipant');
            const name = document.getElementById('name');
            const phone_number = document.getElementById('phone_number');
            const email = document.getElementById('email');

            name.addEventListener('keyup', function() {
                validateInput(name);
            });

            email.addEventListener('keyup', function() {
                validateInput(email);
            });

            phone_number.addEventListener('keyup', function() {
                validateInput(phone_number);
            });

            function validateInput(input) {
                if (input.value.trim() === '') {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = 'Please fill out this field.';
                } else if (input === name && !/^[a-zA-Z\s]{3,}$/.test(input.value)) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = 'Name should have at least 3 letters.';
                } else if (input === email && !isValidEmail(input.value)) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = 'Please enter a valid email address.';
                } else if (input === phone_number && !/^\d{10,15}$/.test(input.value)) { // Adjust regex as needed
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = 'Please enter a valid phone number.';
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

            function checkFormValidity() {
                const inputs = [name, email, phone_number];
                const isValid = inputs.every(input => input.classList.contains('is-valid'));
                return isValid;
            }

            form.addEventListener('submit', function(e) {
                if (checkFormValidity()) {
                    form.submit();
                }
                e.preventDefault();
            });

        });

    </script>
</body>
</html>
