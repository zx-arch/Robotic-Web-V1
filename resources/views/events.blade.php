<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Code</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
        }

        .enter-code-form {
            position: relative;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
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
            margin-bottom: 20px;
        }

        .form-header h1 {
            font-size: 24px;
            color: #333;
            animation: slideDown 1s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
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

        .input-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-container input:focus + .floating-label,
        .input-container input:not(:placeholder-shown) + .floating-label {
            top: -1.5rem;
            left: 0.75rem;
            font-size: 12px;
            color: #007bff;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 1rem;
            top: 0.5rem;
            transition: 0.3s ease all;
            color: #aaa;
        }

        .floating-label.active {
            top: -1.5rem;
            left: 0.75rem;
            font-size: 12px;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="enter-code-form">
    <form action="{{route('dashboard.events.submit')}}" method="post" id="searchForm">
        @csrf
        <div class="input-container">
            <input type="text" id="code" class="form-control @error('code') is-invalid @enderror" name="code" onkeypress="handleKeyPress(event)" autofocus value="{{ old('code') }}" required>
            <label for="code" class="floating-label">Enter Code</label>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-custom btn-block">Submit</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JS -->
<script>
    // Optional: JavaScript to enhance the form
    document.getElementById('code').addEventListener('focus', function() {
        document.querySelector('.floating-label').classList.add('active');
    });

    document.getElementById('code').addEventListener('blur', function() {
        if (this.value === '') {
            document.querySelector('.floating-label').classList.remove('active');
        }
    });
    function handleKeyPress(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchForm').submit();
        }
    }
</script>

</body>
</html>
