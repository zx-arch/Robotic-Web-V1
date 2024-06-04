@extends('cms_login.index_admin')

@section('content')

<div class="container-fluid">
        
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">List Presensi</h5>

                <div class="card">
                    <div class="card-body">
                        @php
                            $eventsDate = \Carbon\Carbon::parse($events->event_date);
                            $formattedEventsDate = $eventsDate->isoFormat('dddd, D MMMM YYYY');
                        @endphp

                        @if($errors->any())
                            <div class="alert alert-danger" style="width: 35%" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session()->has('success_saved'))
                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_saved'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('click_perpanjang') && (session()->has('max_time_presensi') && session('max_time_presensi') > now()))
                            <div class="card" style="width: 35%">
                                <div class="card-header bg-info">
                                    Setup waktu presensi
                                </div>

                                <div class="card-body w-75">
                                    <form action="{{route('admin.events.submitSetPresensi', ['code' => $events->code])}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="setTgl">Tanggal presensi <span class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="setTgl" id="setTgl" class="form-control" required min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                                <label for="waktuMulai">Mulai <span class="text-danger fw-bold">*</span></label>
                                                <input type="time" id="waktuMulai" name="waktuMulai" class="form-control" required min="<?php echo date('H:i'); ?>">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                                <label for="waktuBerakhir">Berakhir <span class="text-danger fw-bold">*</span></label>
                                                <input type="time" id="waktuBerakhir" name="waktuBerakhir" class="form-control" required>
                                            </div>
                                        </div>
                                        <p class="text-success w-100">Note: atur waktu presensi dalam hitungan jam di tanggal yang sama</p>
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            @if ($attendances->closing_date < now() && $attendances->opening_date < $attendances->closing_date)
                                <div class="alert alert-warning" role="alert" style="width: 35%;">
                                    Presensi telah berakhir / ditutup!
                                    <br><br>
                                    <span class="mt-2 "><strong>Topik</strong>: {{$attendances->event_name }}</span>
                                    <br>
                                    <span><strong>Tanggal</strong>: {{$formattedEventsDate}}</span><br>
                                    <span><strong>Tempat</strong>: {{$events->location}}</span><br><br>
                                    <form action="{{route('admin.events.perpanjangPresensi', ['code' => $events->code])}}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-info" type="submit">Perpanjang waktu</button>
                                    </form>
                                </div>

                            @elseif ($attendances->opening_date < $attendances->closing_date)
                                <div class="alert alert-info w-50" id="countdown">
                                    Presensi telah dibuka!
                                    <p style="float: right;"><span id="datetime"></span></p>
                                    <br><br>
                                    <span class="mt-2 "><strong>Topik</strong>: {{$attendances->event_name }}</span>
                                    <br>
                                    <span><strong>Tanggal</strong>: {{$formattedEventsDate}}</span><br>
                                    <span><strong>Tempat</strong>: {{$events->location}}</span><br>
                                    <span><strong>Tanggal presensi</strong>: {{explode(' ',$attendances->opening_date)[0]}}</span><br>
                                    <span><strong>Lama presensi</strong>: {{explode(' ',$attendances->opening_date)[1]}} - {{explode(' ',$attendances->closing_date)[1]}}</span><br><br>

                                    <p><strong>Time remaining:</strong> <span id="hours"></span> jam <span id="minutes"></span> menit <span id="seconds"></span> detik</p>
                                </div>
                            @endif
                        @endif

                        <div class="card-body p-0" style="overflow-x: auto;">
                            <div id="w0" class="gridview table-responsive">
                                <table class="table text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Nama</td>
                                            <td>Email</td>
                                            <td>No Handphone</td>
                                            <td>Status</td>
                                            <td>Waktu Presensi</td>
                                            @if ($attendances->closing_date > now() && $attendances->opening_date < $attendances->closing_date)
                                                <td>Note / Action</td>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($participants as $participant)
                                            <form action="{{route('admin.events.presentUser', ['code' => $events->code, 'id' => encrypt($participant->id)])}}" method="post">
                                                @csrf
                                                <tr>
                                                    <td>{{$loop->index += 1}}</td>
                                                    <td>{{$participant->name}}</td>
                                                    <td>{{$participant->email}}</td>
                                                    <td>{{$participant->phone_number}}</td>
                                                    <td>{{$participant->status_presensi}}</td>
                                                    <td>{{$participant->waktu_presensi}}</td>

                                                    @if ($attendances->closing_date > now() && $attendances->opening_date < $attendances->closing_date && is_null($participant->waktu_presensi))
                                                        <td>
                                                            <button type="submit" name="present" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>&nbsp;
                                                            <button type="submit" name="block" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></button>
                                                        </td>
                                                    @else
                                                        @if ($participant->waktu_presensi && $participant->status_presensi == 'Tidak Hadir')
                                                            <td class="text-danger" style="font-style: italic;">
                                                                Dipresensikan tidak hadir oleh {{auth()->user()->role}}
                                                            </td>
                                                        @else
                                                            <td>Dipresensikan hadir oleh {{auth()->user()->role}}</td>
                                                        @endif
                                                    @endif

                                                </tr>
                                            </form>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger">Data Presensi tidak ditemukan!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
    
                        @if ($participants->lastPage() > 1)
                            <nav aria-label="Page navigation example">
                                <ul class="pagination mt-3">
                                    {{-- Previous Page Link --}}
                                    @if ($participants->currentPage() > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $participants->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @for ($i = 1; $i <= $participants->lastPage(); $i++)
                                        @if ($i == $participants->currentPage())
                                            {{-- Current Page --}}
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            {{-- Pages Link --}}
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $participants->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Next Page Link --}}
                                    @if ($participants->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $participants->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif

                    </div>

                    @if ($participants->count() >= 15)
                        <div>
                            Showing <b>{{ $participants->firstItem() }}</b>
                            to <b>{{ $participants->lastItem() }}</b>
                            of <b>{{ $participants->total() }}</b> items.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
     // Ambil waktu berakhir dari server
        var waktuBerakhir = new Date("{{$attendances->closing_date}}");

        // Perbarui waktu setiap detik
        var x = setInterval(function() {
            // Dapatkan waktu sekarang
            var waktuSekarang = new Date();

            // Hitung selisih waktu antara waktu sekarang dan waktu berakhir
            var selisihWaktu = waktuBerakhir - waktuSekarang;

            // Hitung jam, menit, dan detik dari selisih waktu
            var jam = Math.floor(selisihWaktu / (1000 * 60 * 60));
            var menit = Math.floor((selisihWaktu % (1000 * 60 * 60)) / (1000 * 60));
            var detik = Math.floor((selisihWaktu % (1000 * 60)) / 1000);

            // Tampilkan hasil perhitungan di dalam elemen dengan id yang sesuai
            document.getElementById("hours").innerHTML = jam;
            document.getElementById("minutes").innerHTML = menit;
            document.getElementById("seconds").innerHTML = detik;
            
            var table = document.querySelector('.table');

            // Jika waktu berakhir sudah lewat, hentikan perhitungan
            if (selisihWaktu < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "Waktu presensi telah berakhir.";
                // Hapus tag <td> di kolom "Note / Action" dari bagian <thead>
                var theadActionColumn = table.querySelector('thead tr td:nth-child(7)');
                if (theadActionColumn) {
                    theadActionColumn.remove();
                }

                // Hapus tag <td> di kolom "Note / Action" dari bagian <tbody>
                var tbodyRows = table.querySelectorAll('tbody tr');
                tbodyRows.forEach(function(row) {
                    var tbodyActionColumn = row.querySelector('td:nth-child(7)');
                    if (tbodyActionColumn) {
                        tbodyActionColumn.remove();
                    }
                });
            }
        }, 1000); // Perbarui setiap detik
</script>

<script>

    window.onload = function() {
        setInterval(function() {
            var date = new Date();
            var displayTime = date.toLocaleTimeString();

            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var day = days[date.getDay()];

            // Mendapatkan tanggal, bulan, dan tahun
            var dayOfMonth = date.getDate();
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            var month = months[date.getMonth()];
            var year = date.getFullYear();

            document.getElementById('datetime').innerHTML = day + ', ' + dayOfMonth + ' ' + month + ' ' + year + ' ' + displayTime;
        }, 1000); // 1000 milliseconds = 1 second
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('waktuMulai').addEventListener('input', function() {
            document.getElementById('waktuBerakhir').min = this.value;
        });

        document.getElementById('waktuBerakhir').addEventListener('change', function() {
            var waktuMulai = document.getElementById('waktuMulai').value;
            var waktuBerakhir = this.value;

            // Membandingkan waktu berakhir dengan waktu mulai
            if (waktuBerakhir <= waktuMulai) {
                this.setCustomValidity('Jam berakhir harus lebih besar dari jam mulai.');
            } else {
                this.setCustomValidity('');
            }
        });

    });

</script>