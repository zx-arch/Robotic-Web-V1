<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    overflow: hidden; /* Menghilangkan scroll */
}

.error-container {
    text-align: center;
    padding: 40px;
    border-radius: 10px;
    background-color: #ffffff;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    animation: fadeInDown 1s;
}

@keyframes fadeInDown {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-heading {
    font-size: 2.5rem;
    color: #ff0000;
    margin-bottom: 20px;
}

.error-message {
    font-size: 1.5rem;
    color: #333333;
    margin-bottom: 30px;
}

.error-image {
    width: 150px;
    margin-bottom: 30px;
}

.error-back {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.error-back:hover {
    background-color: #0056b3;
}

/* Animasi background */
@keyframes moveBackground {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.parallax-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    animation: moveBackground 10s infinite linear;
    background-image: linear-gradient(90deg, #4b6cb7, #182848);
}

    </style>
    {{-- <link rel="stylesheet" href="{{ asset('css/error.css') }}"> <!-- Menggunakan file CSS yang telah didefinisikan --> --}}
</head>
<body>
    <div class="parallax-bg"></div> <!-- Latar belakang animasi -->
    <div class="error-container">
        <h2 class="error-heading">Oops! Something went wrong</h2>
        <p class="error-message">{{ $error->getMessage() }}</p>
        <button class="error-back" onclick="goBack()">Go Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
