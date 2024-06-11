<style>
    .custom-card {
        border: none;
        border-radius: 10px;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .custom-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .custom-card .card-title {
        font-size: 1.2rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
    }

    .custom-card .card-text {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .carousel-item {
        transition-duration: 15s; /* Ganti nilai ini sesuai kebutuhan Anda */
    }

    .carousel-item > .row {
        display: flex;
        flex-wrap: nowrap;
    }

    .carousel-item > .row > .col-lg-3 {
        flex: 1;
        margin: 5px;
    }

    .custom-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .card.custom-card {
        height: 100%;
    }

    .custom-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-footer {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .carousel-control-prev, .carousel-control-next {
        display: none; /* Hide the navigation controls */
    }

</style>

@extends('cms_login.index_user')

@section('content')

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class='bx bxs-user'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Semua Peserta</span>
                                <span class="info-box-number">{{$totalPeserta}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="info-box bg-primary">
                            <span class="info-box-icon"><i class='bx bxs-devices'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Peserta Hadir</span>
                                <span class="info-box-number">{{$participantCounts->hadir_count}} dari {{($participantCounts->tidak_hadir_count == 0 ? $totalPeserta : $participantCounts->tidak_hadir_count)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class='bx bxs-bell'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kota Event Terbanyak</span>
                                <span class="info-box-number">{{$kotaTerbanyak->city}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class='bx bxs-check-circle'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Event Terbanyak</span>
                                <span class="info-box-number">{{$kotaTerbanyak->total}} event</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-3">

                    <div class="highlight-title">Events Terbaru</div>
                    
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="35000">
                        <div class="carousel-inner">

                            <div class="carousel-item active">
                                <div class="d-flex flex-wrap">

                                    @forelse ($allEvents as $event)
                                        <div class="col-lg-3 col-md-6 p-2">
                                            <div class="card custom-card my-bg-{{$loop->index + 1}}">
                                                <div class="card-header">
                                                    <h5 class="card-title">{{$event->nama_event}}</h5>
                                                </div>

                                                <div class="card-body">
                                                    <p class="card-text"><strong>Date:</strong> {{$event->event_date}}</p>
                                                    
                                                    @if ($event->event_type === 'online')
                                                        <p class="card-text"><strong>Pembicara:</strong> {{$event->speakers}}</p>
                                                        @if (!is_null($event->link_online) && $event->register)
                                                            <p class="card-text">
                                                                <strong>URL:</strong>
                                                                <a href="{{ $event->link_online}}" target="_blank">
                                                                    {{ $event->link_online }}
                                                                </a>
                                                            </p>

                                                            <p class="card-text"><strong>Usercode: </strong>{{$event->user_access}}</p>
                                                            <p class="card-text"><strong>Passcode: </strong>{{$event->passcode}}</p>
                                                            
                                                        @else
                                                            <p class="card-text"><strong>URL: </strong>(belum tersedia)</p>
                                                        @endif
                                                        
                                                    @else
                                                        <p class="card-text"><strong>Place:</strong> {{$event->location}}</p>
                                                        <p class="card-text"><strong>Access Code:</strong> {{$event->access_code ?? 'belum tersedia'}}</p>
                                                    @endif

                                                    @php
                                                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                                                        $openingDate = \Carbon\Carbon::parse($event->opening_date);
                                                        $closingDate = \Carbon\Carbon::parse($event->closing_date);
                                                    @endphp

                                                    @if ($event->register)
                                                        @if ($event->opening_date <= now() && $event->closing_date >= now())
                                                            <p class="card-text mb-3">Presensi dibuka {{$eventDate->isoFormat('ddd, D MMMM YYYY')}}
                                                                <br>pukul {{$openingDate->isoFormat('HH:mm')}} - {{$closingDate->isoFormat('HH:mm')}}
                                                            </p>
                                                        @endif
                                                    @endif

                                                    @if ($event->event_date < now())
                                                        <span><i class="fas fa-circle" style="color: red"></i>&nbsp; Finished </span>
                                                    @elseif ($event->event_date == now())
                                                        <span><i class="fas fa-circle" style="color: orange"></i>&nbsp; Ongoing</span>
                                                    @else
                                                        <span><i class="fas fa-circle" style="color: green"></i>&nbsp; Upcoming</span>
                                                    @endif

                                                </div>
                                                
                                                <div class="card-footer">
                                                    @if ($event->opening_date <= now() && $event->closing_date >= now())
                                                        <button class="btn btn-primary my-bg-{{$loop->index + 1}}-btn w-50" id="btnPresensi" type="submit">Presensi</button>
                                                    @else
                                                        @if ($eventDate->isoFormat('HH:mm') > now()->isoFormat('HH:mm') && $eventDate->isoFormat('D MMMM YYYY') >= now()->isoFormat('D MMMM YYYY'))
                                                            @if (!$event->register)
                                                                <form action="{{route('user.dashboard.eventRegister', ['code' => $event->code])}}" method="post" id="formRegister">
                                                                    @csrf
                                                                    <button class="btn btn-primary my-bg-{{$loop->index + 1}}-btn" id="btnRegister">Register</button>
                                                                </form>
                                                            @else
                                                                <p>Have Registered</p>
                                                            @endif
                                                        @else
                                                            <p style="visibility: hidden;">Empty</p>
                                                        @endif
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-warning">
                                            Event terbaru belum tersedia
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="highlight-title mt-3 p-2">My Events</div>

                    <div class="card">
                        <div class="card-body p-0" style="overflow-x: auto;">

                            @if (session()->has('success_saved'))
                                <div class="alert alert-success w-25">
                                    {{session('success_saved')}}
                                </div>
                            @endif

                            @if (session()->has('error_saved'))
                                <div class="alert alert-danger w-25">
                                    {{session('error_saved')}}
                                </div>
                            @endif

                            <div id="w0" class="gridview table-responsive">
                                <table class="table text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td></td>
                                            <td>Nama Event</td>
                                            <td>Tanggal Event</td>
                                            <td>Location</td>
                                            <td>Access Code</td>
                                            <td>Status Event</td>
                                            <td>Status Absensi</td>
                                            <td>Note</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($participants as $myev)
                                            <tr>
                                                <td>{{$loop->index += 1}}</td>
                                                <td><button class="btn btn-sm btn-warning" type="submit"><i class="fas fa-list-ul"></i></button></td>
                                                <td>{{$myev->nama_event}}</td>
                                                <td>{{$myev->event_date}}</td>
                                                <td>{{$myev->location}}</td>
                                                <td>{{$myev->access_code}}</td>

                                                @if ($myev->event_date < now())
                                                    <td><i class="fas fa-circle" style="color: red"></i>&nbsp; Finished </td>
                                                @elseif ($myev->event_date == now())
                                                    <td><i class="fas fa-circle" style="color: orange"></i>&nbsp; Ongoing</td>
                                                @else
                                                    <td><i class="fas fa-circle" style="color: green"></i>&nbsp; Upcoming</td>
                                                @endif

                                                @if ($myev->status_presensi == '' || is_null($myev->status_presensi))
                                                    <td></td>

                                                @elseif (!is_null($myev->waktu_presensi))
                                                    @if (strtolower($myev->status_presensi) == 'hadir')
                                                        <td>{{$myev->waktu_presensi}} <span class="text-success">({{$myev->status_presensi}})</span></td>
                                                    @else
                                                        <td>{{$myev->waktu_presensi}} <span class="text-danger">({{$myev->status_presensi}})</span></td>
                                                    @endif    

                                                @else
                                                    @if (strtolower($myev->status_presensi) == 'hadir')
                                                        <td class="text-success">{{$myev->status_presensi}}</td>
                                                    @else
                                                        <td class="text-danger">{{$myev->status_presensi}}</td>
                                                    @endif
                                                @endif

                                                <td>
                                                    @if (!is_null($myev->waktu_presensi) && strtolower($myev->status_presensi) == 'tidak hadir')
                                                        Dipresensi-kan oleh pengurus event
                                                    @endif

                                                    @if ($myev->opening_date > now())
                                                        <span class="text-primary">Absensi belum dibuka</span>
                                                    @endif
                                                </td>

                                                @if ($myev->event_date < now() && $myev->opening_date <= now() && $myev->closing_date >= now())
                                                    @if ($myev->status_presensi != 'Hadir')
                                                        <form action="{{route('user.dashboard.presentUser', ['code' => $myev->code, 'id' => encrypt($myev->id_user)])}}" method="post">
                                                            @csrf
                                                            <td>
                                                                <button type="submit" class="btn btn-sm btn-primary">Hadir</button>
                                                            </td>
                                                        </form>
                                                    @endif
                                                @endif

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-danger">Data event belum tersedia!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</main>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btnRegister').addEventListener('click', function () {
            document.getElementById('formRegister').submit();
        });
        @if (session()->has('success_saved') || session()->has('error_saved'))
            const tableElement = document.getElementById('w0');
            tableElement.scrollIntoView({ behavior: 'smooth' });
        @endif
    });
</script>