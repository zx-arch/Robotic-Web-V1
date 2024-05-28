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
            width: 120%; /* Ensure it stretches the full width up to max-width */
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

        .custom-alert {
            padding: 5px 20px;
            margin-top: 10px;
        }

        .custom-alert p {
            font-size: 15px;
            margin-top:12px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-header">
            <h1>Presensi Peserta</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $eventDate = \Carbon\Carbon::parse($event->event_date);
            $formattedEventDate = $eventDate->isoFormat('ddd, D MMMM YYYY HH:mm');
        @endphp

        <div class="alert alert-primary">
            <p><strong>Topik:</strong> {{$event->event_name}}</p>
            <p><strong>Lokasi:</strong> {{$event->location}}</p>
            <p><strong>Pukul:</strong> {{$formattedEventDate}}</p>
        </div>

        <div class="alert alert-warning">
            <p><strong>Nama:</strong> {{$event->name}}</p>
            <p><strong>Email:</strong> {{$event->email}}</p>
            <p><strong>Phone Number:</strong> {{$event->phone_number}}</p>
        </div>

        @if($event->closing_date > now())
            <div id="countdown" class="alert alert-info mt-5 custom-alert">
                <p><strong>Sisa Waktu Presensi:</strong> <span id="hours"></span> jam <span id="minutes"></span> menit <span id="seconds"></span> detik</p>
            </div>
            <form action="#" method="post">
                @csrf
                <button type="submit" class="btn btn-custom btn-block w-25 mt-4" id="buttonSubmit">Hadir</button>
            </form>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Simpan waktu closing awal dalam variabel global
        let originalClosingDate = new Date("{{$event->closing_date}}").getTime();

        function updateCountdown() {
            // Tanggal dan waktu sekarang
            const now = new Date().getTime();

            // Selisih waktu antara waktu sekarang dan waktu closing awal
            let difference = originalClosingDate - now;

            // Periksa apakah waktu closing telah berubah (diperpanjang)
            const newClosingDate = new Date("{{$event->closing_date}}").getTime();
            if (newClosingDate !== originalClosingDate) {
                // Hitung ulang selisih waktu dengan waktu closing baru
                difference = newClosingDate - now;
                // Perbarui waktu closing awal dengan waktu baru
                originalClosingDate = newClosingDate;
            }

            // Jika waktu telah berakhir, tampilkan alert yang baru
            if (difference <= 0) {
                const closedAlert = document.getElementById("closedAlert");
                // Periksa apakah alert sudah ada sebelumnya
                if (!closedAlert) {
                    const container = document.querySelector(".container");
                    // Buat elemen alert baru
                    const newAlert = document.createElement("div");
                    newAlert.id = "closedAlert";
                    newAlert.className = "alert alert-danger mt-5 custom-alert";
                    newAlert.innerHTML = "<p><strong>Waktu presensi telah berakhir / ditutup</strong></p>";
                    // Tambahkan alert baru ke dalam dokumen HTML
                    container.appendChild(newAlert);

                    const forms = document.getElementsByTagName("form");
                    if (forms.length > 0) {
                        forms[0].parentNode.removeChild(forms[0]);
                    }
                }
                // Sembunyikan countdown timer
                document.getElementById("countdown").style.display = "none";
                return;
            }

            // Hitung jumlah jam, menit, dan detik dari selisih waktu
            const hours = Math.floor(difference / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            // Perbarui tampilan countdown timer
            document.getElementById("hours").innerText = hours;
            document.getElementById("minutes").innerText = minutes;
            document.getElementById("seconds").innerText = seconds;
        }

        // Panggil fungsi updateCountdown setiap detik
        setInterval(updateCountdown, 1000);

        // Panggil updateCountdown untuk memastikan bahwa countdown timer terupdate saat halaman dimuat
        updateCountdown();

    </script>

</body>
</html>
