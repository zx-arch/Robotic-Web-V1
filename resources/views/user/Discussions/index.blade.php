@extends('cms_login.index_user')

@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box mb-4">
            <div class="box-body d-flex justify-content-between align-items-center">
                <h4 class="m-0 highlight-title">Forum Diskusi</h4>
                <a href="{{route('user.discussions.add')}}" class="btn btn-primary">Add</a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="box mb-4">
            <div class="box-body">
                <form method="GET" action="{{route('user.discussions.filter')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <input type="text" name="filter[title]" class="form-control" placeholder="Filter by Title" value="{{ request()->input('filter.title') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="filter[filter_option]" id="filter_option" class="form-control">
                                <option value="" selected disabled>Filter ..</option>
                                <option value="likes" {{ request()->input('filter.filter_option') == 'likes' ? 'selected' : '' }}>Likes</option>
                                <option value="views" {{ request()->input('filter.filter_option') == 'views' ? 'selected' : '' }}>Views</option>
                                <option value="responses" {{ request()->input('filter.filter_option') == 'responses' ? 'selected' : '' }}>Answer</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="filter[sorting]" id="sorting" class="form-control">
                                <option value="" selected disabled>Sorting ..</option>
                                <option value="asc" {{ request()->input('filter.sorting') == 'asc' ? 'selected' : '' }}>Terkecil</option>
                                <option value="desc" {{ request()->input('filter.sorting') == 'desc' ? 'selected' : '' }}>Terbesar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('user.discussions') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        @if (session()->has('success_saved'))
            <div class="alert alert-success w-25">
                {{session('success_saved')}}
            </div>
        @endif

        @if (session()->has('error_saved'))
            <div class="alert alert-danger w-50">
                {{session('error_saved')}}
            </div>
        @endif

        @if (isset($discussions))

            @forelse ($discussions as $discussion)

                <div class="box mb-3 rounded shadow-lg">
                    <div class="box-body p-3">
                        <h5>
                            <a href="{{ route('user.discussions.getByID', ['id' => $discussion->id, 'title' => Str::kebab(preg_replace('/[^\w\s]/', '', $discussion->title))]) }}">
                                {{ $discussion->title }}
                            </a>
                        </h5>
                        <p class="subs-title mt-3">{!! Str::limit($discussion->message, 165) !!}</p>
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex justify-content-left flex-wrap align-items-center">
                                <small class="text-muted me-3">{{$discussion->responses}} Answer</small>
                                <small class="text-muted me-3">{{$discussion->views}} Views</small>
                                <small class="text-muted">{{$discussion->likes}} Likes</small>
                            </div>
                            <small class="text-muted username-info mt-2 mt-md-0">{{ $discussion->username }} - {{ $discussion->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

            @empty
                <p class="text-warning"><strong>Data discussions belum tersedia !</strong></p>
            @endforelse

            @if ($discussions->lastPage() > 1)
                <nav aria-label="Page navigation example">
                    <ul class="pagination mt-3">
                        {{-- Previous Page Link --}}
                        @if ($discussions->currentPage() > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $discussions->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $discussions->lastPage(); $i++)
                            @if ($i == $discussions->currentPage())
                                {{-- Current Page --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                {{-- Pages Link --}}
                                <li class="page-item">
                                    <a class="page-link" href="{{ $discussions->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($discussions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $discussions->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif

        @else
            <p class="text-warning"><strong>Data discussions belum tersedia !</strong></p>
        @endif

        @if ($discussions->count() > 0)
            <div class="show-text-paging">
                Showing <b>{{ $discussions->firstItem() }}</b>
                to <b>{{ $discussions->lastItem() }}</b>
                of <b>{{ $discussions->total() }}</b> items.
            </div>
        @endif

    </div>
</main>
@endsection
