@extends('cms_login.index_admin')
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

.custom-card .card-body {
    text-align: center;
}

.custom-card .card-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.custom-card .card-text {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.card-img-top {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

/* Gradient backgrounds */
.gradient-1 {
    background: linear-gradient(135deg, #FF5F6D, #FFC371);
}

.gradient-1:hover {
    background: linear-gradient(135deg, #42E695, #3BB2B8);
}

.gradient-2 {
    background: linear-gradient(135deg, #8E2DE2, #4A00E0);
}

.gradient-2:hover {
    background: linear-gradient(135deg, #DA4453, #89216B);
}

.gradient-3 {
    background: linear-gradient(135deg, #FC466B, #3F5EFB);
}

.gradient-3:hover {
    background: linear-gradient(135deg, #3A1C71, #D76D77, #FFAF7B);
}

.gradient-4 {
    background: linear-gradient(135deg, #0F2027, #203A43, #2C5364);
}

.gradient-4:hover {
    background: linear-gradient(135deg, #FFB75E, #ED8F03);
}

.gradient-5 {
    background: linear-gradient(135deg, #C33764, #1D2671);
}

.gradient-5:hover {
    background: linear-gradient(135deg, #667EEA, #764BA2);
}

.gradient-6 {
    background: linear-gradient(135deg, #1d976c, #93f9b9);
}

.gradient-6:hover {
    background: linear-gradient(135deg, #f6d365, #fda085);
}

.gradient-7 {
    background: linear-gradient(135deg, #ff758c, #ff7eb3);
}

.gradient-7:hover {
    background: linear-gradient(135deg, #f54ea2, #ff7676);
}

.gradient-8 {
    background: linear-gradient(135deg, #00F260, #0575E6);
}

.gradient-8:hover {
    background: linear-gradient(135deg, #e1eec3, #f05053);
}

.gradient-9 {
    background: linear-gradient(135deg, #7F00FF, #E100FF);
}

.gradient-9:hover {
    background: linear-gradient(135deg, #ff6a00, #ee0979);
}

.gradient-10 {
    background: linear-gradient(135deg, #4568DC, #B06AB3);
}

.gradient-10:hover {
    background: linear-gradient(135deg, #ff5858, #f09819);
}

.gradient-11 {
    background: linear-gradient(135deg, #34e89e, #0f3443);
}

.gradient-11:hover {
    background: linear-gradient(135deg, #fc00ff, #00dbde);
}

.gradient-12 {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.gradient-12:hover {
    background: linear-gradient(135deg, #dd5e89, #f7bb97);
}

.gradient-13 {
    background: linear-gradient(135deg, #ff758c, #ff7eb3);
}

.gradient-13:hover {
    background: linear-gradient(135deg, #f54ea2, #ff7676);
}

.gradient-14 {
    background: linear-gradient(135deg, #ff5858, #f09819);
}

.gradient-14:hover {
    background: linear-gradient(135deg, #4568dc, #b06ab3);
}

.gradient-15 {
    background: linear-gradient(135deg, #f3e7e9, #e3eeff);
}

.gradient-15:hover {
    background: linear-gradient(135deg, #fffcdc, #d9eade);
}

.gradient-16 {
    background: linear-gradient(135deg, #ee9ca7, #ffdde1);
}

.gradient-16:hover {
    background: linear-gradient(135deg, #da4453, #89216b);
}

.gradient-17 {
    background: linear-gradient(135deg, #fc00ff, #00dbde);
}

.gradient-17:hover {
    background: linear-gradient(135deg, #34e89e, #0f3443);
}

.gradient-18 {
    background: linear-gradient(135deg, #43c6ac, #f8ffae);
}

.gradient-18:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.gradient-19 {
    background: linear-gradient(135deg, #7F00FF, #E100FF);
}

.gradient-19:hover {
    background: linear-gradient(135deg, #ff758c, #ff7eb3);
}

.gradient-20 {
    background: linear-gradient(135deg, #ff6e7f, #bfe9ff);
}

.gradient-20:hover {
    background: linear-gradient(135deg, #f9d423, #ff4e50);
}
</style>

@section('content')

<div class="container-fluid">
        
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Events</h5>

                <div class="card">
                    
                    <div class="card-header">
                        <a href="{{route('admin.events.add')}}" class="btn btn-success">Add Events</a>
                        
                        @if (session()->has('success_saved'))
                            <div id="w6" class="alert-primary alert alert-dismissible mt-3 w-75" role="alert">
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
                    </div>

                    <div class="card-body">
                        <div class="card-body p-0" style="overflow-x: auto;">
                            <div id="w0" class="gridview table-responsive">
                                <table class="table text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Code event_date</td>
                                            <td>Nama Event</td>
                                            <td>Tanggal Event</td>
                                            <td>Location</td>
                                            <td>Nama Pengurus</td>
                                            <td>Bagian Acara</td>
                                            <td>Total Pengurus</td>
                                            <td>Total Peserta</td>
                                            <td>Dibuat</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($events as $ev)
                                            <tr>
                                                @php
                                                    $eventDate = \Carbon\Carbon::parse($ev->event_date);
                                                    $formattedEventDate = $eventDate->isoFormat('ddd, D MMMM YYYY HH:mm');
                                                @endphp 

                                                <td>{{$loop->index += 1}}</td>
                                                <td>{{$ev->code_event}}</td>
                                                <td>{{$ev->nama_event}}</td>
                                                <td>{{ $formattedEventDate }}</td>
                                                <td>{{$ev->location}}</td>
                                                <td>{{$ev->organizer_name}}</td>
                                                <td>{{$ev->event_section}}</td>
                                                <td>{{$ev->total_pengurus}}</td>
                                                <td>{{$ev->total_peserta}}</td>
                                                <td>{{$ev->created_at}}</td>
                                                <td>
                                                    <a class="btn btn-warning btn-sm" href="{{route('admin.events.update', ['code' => $ev->code_event])}}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                    <a class="btn btn-danger btn-sm btn-delete" href="#" title="Delete" aria-label="Delete" data-pjax="0"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <p class="ml-2 mt-3 text-danger">Pengguna belum tersedia</p>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <img src="{{asset('events/example-1.jpg')}}" class="card-img-top" alt="Event Poster">

                        {{-- <h5 class="card-title">Event 1</h5>
                        <p class="card-text"><strong>Date:</strong> June 1, 2024</p>
                        <p class="card-text"><strong>Place:</strong> Central Park</p>
                        <p class="card-text">Join us for an amazing event filled with fun and excitement!</p> --}}
                    
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card custom-card gradient-2">
                    {{-- <img src="https://via.placeholder.com/150" class="card-img-top" alt="Event Poster"> --}}
                    <div class="card-body">
                        <h5 class="card-title">Event 2</h5>
                        <p class="card-text"><strong>Date:</strong> June 5, 2024</p>
                        <p class="card-text"><strong>Place:</strong> Downtown Hall</p>
                        <p class="card-text">Don't miss this special occasion with lots of learning opportunities.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card custom-card gradient-3">
                    {{-- <img src="https://via.placeholder.com/150" class="card-img-top" alt="Event Poster"> --}}
                    <div class="card-body">
                        <h5 class="card-title">Event 3</h5>
                        <p class="card-text"><strong>Date:</strong> June 10, 2024</p>
                        <p class="card-text"><strong>Place:</strong> Beachside</p>
                        <p class="card-text">A great opportunity to learn and grow in a beautiful setting.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card custom-card gradient-4">
                    {{-- <img src="https://via.placeholder.com/150" class="card-img-top" alt="Event Poster"> --}}
                    <div class="card-body">
                        <h5 class="card-title">Event 4</h5>
                        <p class="card-text"><strong>Date:</strong> June 15, 2024</p>
                        <p class="card-text"><strong>Place:</strong> Riverside Park</p>
                        <p class="card-text">Come and enjoy a day of fun and activities with us!</p>
                    </div>
                </div>
            </div>
            <!-- Add more cards as needed with different gradient classes -->
                <!--

        </div>
        <div class="card">
            <div class="card-body">
        
           </div>
        </div> -->
    </div>
</div>

@endsection