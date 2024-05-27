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
                            <div class="card-header">
                                <h5 class="card-title">Pengurus</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{route('admin.events.saveParticipant', ['code' => $eventCode, 'id' => encrypt($myEventParticipant->id)])}}" method="post">
                                    @csrf

                                    <div class="form-group highlight-addon has-success">
                                        <label for="name">Nama <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" value="{{$myEventParticipant->name}}" required class="form-control w-50">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group highlight-addon has-success">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" value="{{$myEventParticipant->email}}" required class="form-control w-50">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group highlight-addon has-success">
                                        <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone_number" id="phone_number" value="{{$myEventParticipant->phone_number}}" required class="form-control w-50">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="button">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="/admin/events/{{$eventCode}}" class="btn btn-success">Kembali</a>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body" style="margin-top: -35px;">

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

                                @if ($eventParticipant->count() > 0)
                                    <div id="w0" class="gridview table-responsive mx-auto">
                                        <table class="table text-nowrap table-striped table-bordered mb-0 mt-3">
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
                                                        <td>
                                                            <a class="btn btn-warning btn-sm" href="{{route('admin.events.updateParticipant', ['code' => $eventCode, 'role' => 'participant', 'id' => encrypt($participant->id)])}}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                            <a class="btn btn-danger btn-sm btn-delete" href="{{route('admin.events.deleteParticipant', ['id' => encrypt($participant->id)])}}" title="Delete" aria-label="Delete" data-role="participant"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>

    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data ${this.getAttribute('data-role')} yang dihapus tidak dapat dipulihkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna menekan tombol "Ya, hapus", arahkan ke URL penghapusan
                        window.location.href = url;
                    }
                });
            });
        });

    });

</script>