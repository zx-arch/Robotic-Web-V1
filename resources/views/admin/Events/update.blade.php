@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')

    <div class="container-fluid">

        <div class="box">
            <div class="box-body">

                <div class="px-15px px-lg-25px">
                    <div class="col-md-12 mx-auto">

                        <div class="card">

                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="event" role="tabpanel" aria-labelledby="event-tab">
                                        <h5 class="card-title">Event</h5>
                                    </div>
                                    <div class="tab-pane fade" id="pengurus" role="tabpanel" aria-labelledby="pengurus-tab">
                                        <h5 class="card-title">Pengurus</h5>
                                        <div id="w0" class="gridview table-responsive p-3 mx-auto">
                                            <table class="table text-nowrap table-striped table-bordered mb-0 mt-3 w-75">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>Nama</td>
                                                        <td>No Handphone</td>
                                                        <td>Email</td>
                                                        <td>Bagian</td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($eventManager as $manager)
                                                        <tr>
                                                            <td>{{$loop->index += 1}}</td>
                                                            <td>{{$manager->name}}</td>
                                                            <td>{{$manager->email}}</td>
                                                            <td>{{$manager->phone_number}}</td>
                                                            <td>{{$manager->section}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @empty
                                                        <p class="ml-2 mt-3 text-danger">Pengguna belum tersedia</p>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="peserta" role="tabpanel" aria-labelledby="peserta-tab">
                                        <h5 class="card-title">Peserta</h5>
                                        @if ($eventParticipant->count() > 0)

                                        <div id="w0" class="gridview table-responsive p-3 mx-auto">

                                            <table class="table text-nowrap table-striped table-bordered mb-0 mt-3 w-75">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>Nama</td>
                                                        <td>No Handphone</td>
                                                        <td>Email</td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($eventParticipant as $participant)
                                                        <tr>
                                                            <td>{{$loop->index += 1}}</td>
                                                            <td>{{$participant->name}}</td>
                                                            <td>{{$participant->email}}</td>
                                                            <td>{{$participant->phone_number}}</td>
                                                            <td>{{$participant->section}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        @else
                                            <br><p class="mt-3 ms-2 text-danger">Pengguna belum tersedia</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="home" aria-selected="true">Event</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pengurus-tab" data-toggle="tab" href="#pengurus" role="tab" aria-controls="profile" aria-selected="false">Pengurus</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="peserta-tab" data-toggle="tab" href="#peserta" role="tab" aria-controls="contact" aria-selected="false">Peserta</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection