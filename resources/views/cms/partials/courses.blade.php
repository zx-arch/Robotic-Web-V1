<section id="courses" class="courses section-bg">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2>Courses</h2>
                    {{-- @if (session()->has('valid_book'))
                        <img src="{{asset('assets/content_pembelajaran/struktur_pengajaran.png')}}" class="img-fluid mb-4" style="width: 400px;" alt="">
                        <p>
                            Struktur kurikulum pada tiap materi dibagi menjadi 3 kategori yaitu basic, hands-on dan advanced. 
                            Kategori ini didasarkan pada tingkat kesulitan materi dari dasar hingga mahir. Pada tingkat jenjang SD s.d SMP
                            lebih berfokus pada materi 'Block Programming' dan jenjang SMA/SMK berfokus pada materi 'Python dan AI Programming'.
                        </p>
                    @endif --}}
        </div>

        @if (session()->has('error_access_book'))
            <div class="card">
                <form id="courseForm" action="{{ route('courses.submit') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row w-75">
                            <div class="col-md-4 mb-2">
                                <select id="translationSelect" name="terjemahan" class="form-control" required>
                                    <option value="" disabled selected>Select Version ..</option>
                                    @foreach (session('getLanguages') as $language)
                                        <option value="{{$language->language_id}}" {{session()->has('request_input_book') && session('request_input_book')['terjemahan'] == $language->language_id ? 'selected' : ''}}>{{$language->language_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="text-danger">{{session('error_access_book')}}</p>
                    </div>
                </form>

            </div>
        @endif
        {{-- @php
            var_dump(session('jenis_materi'));
        @endphp --}}
        @if (session()->has('valid_book'))
            <div class="row content">

                <div class="card mt-3">
                    <form id="courseForm" action="{{ route('courses.submit') }}" method="post">
                        @csrf
                        <div class="card-header">

                            @if (!session()->has('getChapter'))
                                <div class="row w-75">
                                    <div class="col-md-4 mb-2">
                                        <select id="translationSelect" name="terjemahan" class="form-control" required>
                                            <option value="" disabled selected>Select Version ..</option>
                                            @foreach (session('getLanguages') as $language)
                                                <option value="{{$language->language_id}}">{{$language->language_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            
                            @else
                                {{-- @php
                                    var_dump(session('getChapter'));
                                @endphp --}}
                                <div class="row w-75">
                                    <div class="col-md-4 mb-2">
                                        <select id="translationSelect" name="terjemahan" class="form-control" required>
                                            <option value="" disabled selected>Select Version ..</option>
                                            @foreach (session('getLanguages') as $language)
                                                <option value="{{$language->language_id}}">{{$language->language_name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="jenis_materi" value="{{session('jenis_materi')}}">
                                    </div>
                                </div>

                            @endif
                        </div>
                    </form>

                    <div id="w0" class="gridview table-responsive" style="overflow-x: auto;">

                        @if (session()->has('getBook'))
                            <table class="table text-nowrap table-striped table-bordered mb-0">
                                
                                <tr class="for_pc_tr w-100">
                                    <td class="text-center align-middle th-number">No</td>
                                    <td class="text-center align-middle font-weight-bold td-name">Nama Materi</td>
                                    <td class="text-center align-middle font-weight-bold td-name">Halaman</td>
                                    <td class="text-center align-middle font-weight-bold td-link">Download</td>
                                    <td class="text-center align-middle font-weight-bold td-link">Tutorial Lainnya</td>
                                </tr>

                                @if ((session()->has('request_input_book')))
                                    <p>Version: {{ \App\Models\Translations::where('id', session('getBook')[0]->language_id)->first()->language_name }}</p>
                                    
                                    @php
                                        $level = session('request_input_book')['level'] ?? null;
                                        $chapter = session('request_input_book')['chapter'] ?? null;
                                    @endphp

                                    @if ($level != null)
                                        <p>Level: {{ \App\Models\HierarchyCategoryBook::where('id', $level)->first()->name }}</p>
                                    @elseif ($chapter != null)
                                        <p>Chapter: {{ \App\Models\HierarchyCategoryBook::where('id', $chapter)->first()->name }}</p>
                                    @endif
                                    
                                @endif
                                @php
                                    $booksCount = count(session('getBook'));
                                    $randomVideos = \App\Models\Tutorials::randomVideo($booksCount);
                                @endphp

                                @foreach (session('getBook') as $key => $book)
                                    <tr class="for_pc_tr w-100">
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{$book->book_title}}</td>
                                        <td>{{$book->pages}}</td>
                                        <td><a href="{{ asset('book/'.\App\Models\Translations::where('id',$book->language_id)->first()->language_name.'/'.$book->file) }}" download>{{$book->file}}</a></td>
                                        <td>
                                            @if ($loop->index < count($randomVideos))
                                                <img src="{{$randomVideos[$loop->index]->thumbnail}}" width="65" height="45" class="img-icon" alt="">
                                                <span><a href="{{$randomVideos[$loop->index]->url ?? $randomVideos[$loop->index]->path_video }}" class="glightbox ms-2">{{$randomVideos[$loop->index]->video_name}}</a></span>
                                            @else
                                            <span style="text-align: center;">-</span>
                                                <!-- Handle the case where the number of videos is less than the number of books -->
                                                <!-- You can display a default image or message -->
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        @if (session()->has('not_available_book'))
                            <p class="text-warning">{{session('not_available_book')}}</p>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <div class="row">
                <div class="col-xl-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100" onclick="window.location.href=`{{route('courses', ['jenis_materi' => 'block_programming'])}}`">

                    <div class="icon-box">
                        <div class="icon"><img src="{{url('assets/content_pembelajaran/icon_block_programming.png')}}" class="img-fluid" style="height: 350px;" alt=""></div>
                        <h4><a href="">Block Programming</a></h4>
                        <p>Mempelajari teknik pemrograman dasar berbasis block menggunakan panduan dari buku Artec Robo Education</p>
                    </div>

                </div>

                <div class="col-xl-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200" onclick="window.location.href=`{{route('courses', ['jenis_materi' => 'ai_programming'])}}`">

                    <div class="icon-box">
                        <div class="icon"><img src="{{url('assets/content_pembelajaran/icon_ai_programming.png')}}" class="img-fluid" style="height: 350px;" alt=""></div>
                        <h4><a href="">AI Programming</a></h4>
                        <p>Merancang sebuah program untuk memberi kecerdasan buatan yang diterapkan pada robot supaya mampu melakukan pengambilan keputusan</p>
                    </div>
                            
                </div>

                <div class="col-xl-4 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300" onclick="window.location.href=`{{route('courses', ['jenis_materi' => 'python_programming'])}}`">

                    <div class="icon-box">
                        <div class="icon"><img src="{{url('assets/content_pembelajaran/icon_python_programming.png')}}" class="img-fluid" style="height: 350px;" alt=""></div>
                        <h4><a href="">Python Edition</a></h4>
                        <p>Mampu merancang sebuah program yang menggunakan python untuk mengatur berbagai pergerakan mobil robot</p>
                    </div>

                </div>

            </div>
        @endif                

    </div>
</section>


