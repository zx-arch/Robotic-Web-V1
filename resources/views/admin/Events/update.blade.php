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

                                    <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="event-tab">
                                        <h5 class="card-title">Events</h5><br>
                                        <ul class="list-group list-group-horizontal mt-3 h-50">
                                            <li class="list-group-item list-group-item-secondary">Nama Event</li>
                                            <li class="list-group-item">{{$event->nama_event}}</li>
                                        </ul>
                                        <ul class="list-group list-group-horizontal">
                                            <li class="list-group-item list-group-item-secondary">Lokasi</li>
                                            <li class="list-group-item">{{$event->location}}</li>
                                        </ul>
                                        @php
                                            $eventDate = \Carbon\Carbon::parse($event->event_date);
                                            $formattedEventDate = $eventDate->isoFormat('ddd, D MMMM YYYY HH:mm');
                                        @endphp
                                        <ul class="list-group list-group-horizontal">
                                            <li class="list-group-item list-group-item-secondary">Tanggal Event</li>
                                            <li class="list-group-item">{{$formattedEventDate}}</li>
                                        </ul>
                                        <ul class="list-group list-group-horizontal">
                                            <li class="list-group-item list-group-item-secondary">Penanggung Jawab</li>
                                            <li class="list-group-item">{{$event->organizer_name}}</li>
                                        </ul>
                                        <ul class="list-group list-group-horizontal">
                                            <li class="list-group-item list-group-item-secondary">Bagian</li>
                                            <li class="list-group-item">{{$event->event_section}}</li>
                                        </ul>
                                    </div>

                                    <div class="tab-pane fade" id="pengurus" role="tabpanel" aria-labelledby="pengurus-tab">
                                        <h5 class="card-title">Pengurus</h5>
                                        <br>
                                        <a href="#" class="btn btn-info p-2 mb-2 mt-3" id="addPengurusBtn">Add Pengurus</a>
                                        <div id="formPengurusContainer" class="mt-3" style="display: none;">
                                            <form action="{{route('admin.events.submitPengurus', ['code' => $eventCode])}}" method="post" id="formPengurus">
                                                @csrf
                                            </form>
                                        </div>

                                        @if (session()->has('success_saved'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('success_saved')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('error_saved'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('error_saved')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (isset($eventManager) && $eventManager->count() > 0 || isset($searchDataManager))
                                            <div id="w0" class="gridview table-responsive mx-auto">
                                                <table class="table text-nowrap table-striped table-bordered mb-0 mt-2 w-75">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td>Nama</td>
                                                            <td>Email</td>
                                                            <td>No Handphone</td>
                                                            <td>Bagian</td>
                                                            <td></td>
                                                        </tr>

                                                        <form action="{{ route('admin.events.searchUpdate', ['code' => $eventCode, 'role' => 'manager']) }}" method="get" id="searchFormManager">
                                                            @csrf
                                                                <tr>
                                                                    <td></td>
                                                                    <td>
                                                                        <input type="text" id="name_manager" class="form-control" name="search[name]" onkeypress="handleKeyPressManager(event)" value="{{ $searchDataManager['name'] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="email" id="email_manager" class="form-control" name="search[email]" onkeypress="handleKeyPressManager(event)" value="{{ $searchDataManager['email'] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="tel" id="phone_number_manager" class="form-control" name="search[phone_number]" onkeypress="handleKeyPressManager(event)" value="{{ $searchDataManager['phone_number'] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="section_manager" class="form-control" name="search[section]" onkeypress="handleKeyPressManager(event)" value="{{ $searchDataManager['section'] ?? '' }}">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                        </form>

                                                    </thead>

                                                    <tbody>
                                                        @forelse ($eventManager as $manager)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $manager->name }}</td>
                                                                <td>{{ $manager->email }}</td>
                                                                <td>{{ $manager->phone_number }}</td>
                                                                <td>{{ $manager->section }}</td>
                                                                <td>
                                                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.events.updateManager', ['code' => $eventCode, 'id' => encrypt($manager->id)]) }}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                                    <a class="btn btn-danger btn-sm btn-delete" href="{{ route('admin.events.deleteManager', ['id' => encrypt($manager->id)]) }}" title="Delete" aria-label="Delete" data-role="manager"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center text-danger">Data panitia tidak ditemukan!</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>

                                                </table>

                                                @if (isset($searchDataManager))
                                                    <a href="{{ route('admin.events.update', ['code' => $eventCode]) }}" class="btn btn-success mt-3">Kembali</a>
                                                @endif

                                                @if ($eventManager->lastPage() > 1)
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination mt-3">
                                                            {{-- Previous Page Link --}}
                                                            @if ($eventManager->currentPage() > 1)
                                                                <li class="page-item">
                                                                    <a class="page-link" href="{{ $eventManager->previousPageUrl() }}" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                            @endif

                                                            {{-- Pagination Elements --}}
                                                            @for ($i = 1; $i <= $eventManager->lastPage(); $i++)
                                                                @if ($i == $eventManager->currentPage())
                                                                    {{-- Current Page --}}
                                                                    <li class="page-item active">
                                                                        <span class="page-link">{{ $i }}</span>
                                                                    </li>
                                                                @else
                                                                    {{-- Pages Link --}}
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="{{ $eventManager->url($i) }}">{{ $i }}</a>
                                                                    </li>
                                                                @endif
                                                            @endfor

                                                            {{-- Next Page Link --}}
                                                            @if ($eventManager->hasMorePages())
                                                                <li class="page-item">
                                                                    <a class="page-link" href="{{ $eventManager->nextPageUrl() }}" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </nav>
                                                @endif

                                            </div>

                                            @if ($eventManager->count() >= 15)
                                                <div>
                                                    Showing <b>{{ $eventManager->firstItem() }}</b>
                                                    to <b>{{ $eventManager->lastItem() }}</b>
                                                    of <b>{{ $eventManager->total() }}</b> items.
                                                </div>
                                            @endif

                                        @else
                                            <p class="mt-3 ms-2 text-danger">Pengurus belum tersedia</p>
                                        @endif

                                    </div>

                                    <div class="tab-pane fade" id="peserta" role="tabpanel" aria-labelledby="peserta-tab">
                                        <h5 class="card-title">Peserta</h5>
                                        <br>
                                        <a href="#" class="btn btn-info p-2 mb-2 mt-3" id="addPesertaBtn">Add Peserta</a>

                                        <div id="formPesertaContainer" class="mt-3" style="display: none;">
                                            <form action="{{route('admin.events.submitPeserta', ['code' => $eventCode])}}" method="post" id="formPeserta">
                                                @csrf
                                            </form>
                                        </div>

                                        @if (session()->has('success_saved'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('success_saved')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('error_saved'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('error_saved')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('delete_successfull_manager'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('delete_successfull_manager')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('error_delete_manager'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('error_delete_manager')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('delete_successfull_participant'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('delete_successfull_participant')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (session()->has('error_delete_participant'))
                                            <div id="w6" class="alert-success alert alert-dismissible mt-3 w-75" role="alert">
                                                {{session('error_delete_participant')}}
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                            </div>
                                        @endif

                                        @if (isset($eventParticipant) && $eventParticipant->count() > 0 || isset($searchDataParticipant))
                                            <div id="w0" class="gridview table-responsive mx-auto">
                                                <table class="table text-nowrap table-striped table-bordered mb-0 mt-3 w-75">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td>Nama</td>
                                                            <td>Email</td>
                                                            <td>No Handphone</td>
                                                            <td></td>
                                                        </tr>

                                                        <form action="{{ route('admin.events.searchUpdate', ['code' => $eventCode, 'role' => 'participant']) }}" method="get" id="searchFormParticipant">
                                                            @csrf
                                                                <tr>
                                                                    <td></td>
                                                                    <td>
                                                                        <input type="text" id="name_participant" class="form-control" name="search[name]" onkeypress="handleKeyPressParticipant(event)" value="{{ $searchDataParticipant['name'] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="email" id="email_participant" class="form-control" name="search[email]" onkeypress="handleKeyPressParticipant(event)" value="{{ $searchDataParticipant['email'] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="tel" id="phone_number_participant" class="form-control" name="search[phone_number]" onkeypress="handleKeyPressParticipant(event)" value="{{ $searchDataParticipant['phone_number'] ?? '' }}">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                        </form>

                                                    </thead>

                                                    <tbody>

                                                        @forelse ($eventParticipant as $participant)
                                                            <tr>
                                                                <td>{{$loop->index += 1}}</td>
                                                                <td>{{$participant->name}}</td>
                                                                <td>{{$participant->email}}</td>
                                                                <td>{{$participant->phone_number}}</td>
                                                                <td>
                                                                    <a class="btn btn-warning btn-sm" href="{{route('admin.events.updateParticipant', ['code' => $eventCode, 'id' => encrypt($participant->id)])}}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                                    <a class="btn btn-danger btn-sm btn-delete" href="{{route('admin.events.deleteParticipant', ['id' => encrypt($participant->id)])}}" title="Delete" aria-label="Delete" data-role="participant"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center text-danger">Data peserta tidak ditemukan!</td>
                                                            </tr>
                                                        @endforelse

                                                    </tbody>
                                                </table>

                                                @if (isset($searchDataParticipant))
                                                    <a href="{{route('admin.events.update', ['code' => $eventCode])}}" class="btn btn-success mt-3">Kembali</a>
                                                @endif

                                                @if ($eventParticipant && $eventParticipant->lastPage() > 1)
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination mt-3">
                                                            {{-- Previous Page Link --}}
                                                            @if ($eventParticipant->currentPage() > 1)
                                                                <li class="page-item">
                                                                    <a class="page-link" href="{{ $eventParticipant->previousPageUrl() }}" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                            @endif

                                                            {{-- Pagination Elements --}}
                                                            @for ($i = 1; $i <= $eventParticipant->lastPage(); $i++)
                                                                @if ($i == $eventParticipant->currentPage())
                                                                    {{-- Current Page --}}
                                                                    <li class="page-item active">
                                                                        <span class="page-link">{{ $i }}</span>
                                                                    </li>
                                                                @else
                                                                    {{-- Pages Link --}}
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="{{ $eventParticipant->url($i) }}">{{ $i }}</a>
                                                                    </li>
                                                                @endif
                                                            @endfor

                                                            {{-- Next Page Link --}}
                                                            @if ($eventParticipant->hasMorePages())
                                                                <li class="page-item">
                                                                    <a class="page-link" href="{{ $eventParticipant->nextPageUrl() }}" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </nav>
                                                @endif

                                            </div>
                                            
                                        @else
                                            <p class="mt-3 ms-2 text-danger">Pengguna belum tersedia</p>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="card-body" style="margin-top: -15px;">
                                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="home" aria-selected="true">Event</a>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Default to the Events tab if localStorage is empty
        document.getElementById('event-tab').classList.add('active');
        document.getElementById('event').classList.add('show', 'active');

        // Add event listeners to nav tabs
        document.querySelectorAll('.nav-link').forEach(function(navLink) {
            navLink.addEventListener('click', function(event) {
                // Save the selected tab to localStorage
                localStorage.setItem('selectedTab', event.target.getAttribute('href'));
            });
        });

        // Check if there's a saved tab in localStorage
        var savedTab = localStorage.getItem('selectedTab');
        if (savedTab) {
            // Show the saved tab
            document.querySelector(savedTab).classList.add('show', 'active');
            document.querySelector(`[href="${savedTab}"]`).classList.add('active');
            document.getElementById('event-tab').classList.remove('active');
            document.getElementById('event').classList.remove('show', 'active');
        }

        document.getElementById("addPesertaBtn").addEventListener("click", function(event) {
            event.preventDefault();
            this.style.display = "none";

            const form = document.getElementById('formPeserta');

            // Create input fields
            var namaInput = createInput("text", "nama", "Nama", "^[a-zA-Z\\s]+$", "Nama hanya boleh berisi huruf dan spasi!");
            var emailInput = createInput("email", "email", "Email", "^[^\s@]+@[^\s@]+\.[^\s@]+$", "Email tidak valid!");
            var phoneInput = createInput("text", "phone_number", "Phone Number", "^\\+?\\d{10,15}$", "Nomor telepon harus berupa angka dan boleh diawali dengan +, dengan panjang 10-15 digit!");

            // Create submit button
            var submitBtn = document.createElement("button");
            submitBtn.type = "submit";
            submitBtn.className = "btn btn-primary";
            submitBtn.textContent = "Submit";

            // Append inputs and button to form
            form.appendChild(namaInput);
            form.appendChild(emailInput);
            form.appendChild(phoneInput);
            form.appendChild(submitBtn);

            // Append form to container
            document.getElementById("formPesertaContainer").appendChild(form);
            document.getElementById("formPesertaContainer").style.display = 'block';
        });

        document.getElementById("addPengurusBtn").addEventListener("click", function(event) {
            event.preventDefault();
            this.style.display = "none";

            const form = document.getElementById('formPengurus');

            // Create input fields
            var namaInput = createInput("text", "nama", "Nama", "^[a-zA-Z\\s]+$", "Nama hanya boleh berisi huruf dan spasi!");
            var emailInput = createInput("email", "email", "Email", "^[^\s@]+@[^\s@]+\.[^\s@]+$", "Email tidak valid!");
            var bagianInput = createInput("text", "section", "Bagian");
            var phoneInput = createInput("text", "phone_number", "Phone Number", "^\\+?\\d{10,15}$", "Nomor telepon harus berupa angka dan boleh diawali dengan +, dengan panjang 10-15 digit!");

            // Create submit button
            var submitBtn = document.createElement("button");
            submitBtn.type = "submit";
            submitBtn.className = "btn btn-primary";
            submitBtn.textContent = "Submit";

            // Append inputs and button to form
            form.appendChild(namaInput);
            form.appendChild(emailInput);
            form.appendChild(bagianInput);
            form.appendChild(phoneInput);
            form.appendChild(submitBtn);

            // Append form to container
            document.getElementById("formPengurusContainer").appendChild(form);
            document.getElementById("formPengurusContainer").style.display = 'block';
        });

        function createInput(type, name, placeholder) {
            var input = document.createElement("input");
            input.type = type;
            input.name = name;
            input.placeholder = placeholder;
            input.className = "form-control d-inline-block mr-2 mb-2";
            input.style.width = "200px";
            return input;
        }

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

<script>
    function handleKeyPressManager(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchFormManager').submit();
        }
    }

    function handleKeyPressParticipant(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchFormParticipant').submit();
        }
    }
</script>