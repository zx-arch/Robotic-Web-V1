@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Memuat CSS untuk jQuery UI (dibutuhkan untuk styling datepicker) -->

@section('content')
<div class="container-fluid">

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3 card-default">
                    <div class="card-header card-header-border-bottom">
                        <h5>Create Language</h5>
                    </div>

                    <div class="card-body p-3">
                        <form action="{{route('language.saveLanguage')}}" method="post">
                            @csrf
                            <div class="form-group highlight-addon has-success">
                                <label for="language_code">Language Code: </label>
                                <input type="text" name="language_code" id="language_code" required class="form-control w-25">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group highlight-addon has-success">
                                <label for="name">Language Name: </label>
                                <input type="text" name="language_name" id="language_name" required class="form-control w-50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <button type="submit" id="buttonSubmit" class="btn btn-primary">Submit</button>
                        </form>
                        
                        @if (session()->has('error_add_language'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_add_language')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection