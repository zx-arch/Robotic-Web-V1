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
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
        }

        .container {
            background: white;
            padding: 50px;
            max-height: 920px; /* Smaller max-height for larger screens */
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            margin-top: 25px;
            max-width: 75%; /* Increase the maximum width */
            width: 120%; /* Ensure it stretches the full width up to max-width */
        }

        /* Styling for mobile devices */
        @media (max-width: 768px) {
            .container {
                max-height: 1000px; /* Higher max-height for mobile devices */
                padding: 30px; /* Adjust padding for mobile */
                width: 100%; /* Ensure it stretches the full width for mobile */
                max-width: 90%; /* Slightly smaller max-width for mobile */
            }
            .show-text-paging {
                margin-top: 20px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
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
            <h1>Daftar Peserta</h1>
            <h2>{{$attendances->event_name}}</h2>
        </div>

        <form action="{{route('logout')}}" method="post">
            @csrf
            <button class="btn btn-primary mb-4">Logout</button>
        </form>

        <div id="w0" class="gridview table-responsive">
            <table class="table text-nowrap table-striped table-bordered">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Nama</td>
                        <td>Email</td>
                        <td>Phone Number</td>
                    </tr>
                    <form action="{{route('guest.dashboard.search')}}" method="get" id="searchForm">
                        @csrf
                        <tr>
                            <td></td>
                            <td>
                                <input type="text" id="name" class="form-control" name="search[name]" onkeypress="handleKeyPress(event)" value="{{ $searchData['name'] ?? '' }}">
                            </td>
                            <td>
                                <input type="text" id="email" class="form-control" name="search[email]" onkeypress="handleKeyPress(event)" value="{{ $searchData['email'] ?? '' }}">
                            </td>
                            <td>
                                <input type="tel" id="phone_number" class="form-control" name="search[phone_number]" onkeypress="handleKeyPress(event)" value="{{ $searchData['phone_number'] ?? '' }}">
                            </td>
                        </tr>
                    </form>
                </thead>
                <tbody>
                    @forelse ($dataParticipant as $participant)
                        <tr>
                            <td>{{$loop->index += 1}}</td>
                            <td>{{$participant->name}}</td>
                            <td>{{$participant->email}}</td>
                            <td>{{$participant->phone_number}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-danger fw-bold text-center">Data peserta belum tersedia!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($dataParticipant->lastPage() > 1)
                <nav aria-label="Page navigation example">
                    <ul class="pagination mt-3">
                        {{-- Previous Page Link --}}
                        @if ($dataParticipant->currentPage() > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $dataParticipant->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $dataParticipant->lastPage(); $i++)
                            @if ($i == $dataParticipant->currentPage())
                                {{-- Current Page --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                {{-- Pages Link --}}
                                <li class="page-item">
                                    <a class="page-link" href="{{ $dataParticipant->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($dataParticipant->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $dataParticipant->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    
        @if ($dataParticipant->count() >= 10)
            <div class="show-text-paging">
                Showing <b>{{ $dataParticipant->firstItem() }}</b>
                to <b>{{ $dataParticipant->lastItem() }}</b>
                of <b>{{ $dataParticipant->total() }}</b> items.
            </div>
        @endif
    </div>

    <script>
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