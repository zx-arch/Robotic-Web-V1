@extends('cms_login.index_user')
<style>
    .card {
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

</style>
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
                
                <div class="col-md-4 mt-3 mb-3">

                    <div class="card">
                        <div class="card-body bg bg-cyan">
                            <h5 class="card-title">{{ $notification->title }}</h5>
                            <p class="card-text">{{ $notification->content }}</p>
                            @if ($myev->status_presensi != 'Hadir')
                                <form action="{{route('user.dashboard.presentUser', ['code' => $notification->event_code, 'id' => encrypt($myev->id)])}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Hadir</button>
                                </form>
                            @else
                                <p class="card-text text-primary">presensi {{$myev->waktu_presensi}}</p>
                            @endif
                            
                        </div>
                        <div class="card-footer text-muted">
                            notif: {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection