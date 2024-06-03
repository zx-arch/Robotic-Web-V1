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

                                    <div class="form-group highlight-addon has-success">
                                        <label for="status">Status Kehadiran <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control" style="width: 150px;" disabled>
                                            <option value="" {{$myEventParticipant->status_presensi ?? 'selected disabled'}}></option>
                                            <option value="hadir" {{$myEventParticipant->status_presensi == 'Hadir' ? 'selected' : ''}}>Hadir</option>
                                            <option value="tidak_hadir" {{$myEventParticipant->status_presensi == 'Tidak Hadir' ? 'selected' : ''}}>Tidak Hadir</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="button">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="/admin/events/{{$eventCode}}" class="btn btn-success">Kembali</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection