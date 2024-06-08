<style>
    .avatar-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
        margin-bottom: 10px;
    }
    img.avatars {
        width: 150px;
        height: 150px;
        border-radius: 50%;
    }
    .avatar-container:hover .overlay {
        opacity: 1;
    }
    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .5s ease;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2em;
    }
    .file-input {
        display: none;
    }
    .content-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .content-column {
        flex: 1;
        margin: 0 30px;
        margin-top: 10px;
    }
</style>


@extends('cms_login.index_user')

@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box">
            <div class="box-body">
                <div class="highlight-title">Settings</div>
                
                <form action="{{route('user.settings.save')}}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="text-center">
                        <div class="avatar-container">
                            <img src="{{ isset($myData->foto_profil) && !is_null($myData->foto_profil) ? asset('events/user/foto_profil/' . $myData->foto_profil) : asset('assets/img/account.png') }}" class="avatars img-fluid" alt="" id="preview">
                            <div class="overlay">Upload</div>
                            <input type="file" id="file-input" class="file-input" name="foto_profil" accept="image/*">
                        </div>
                        <p class="text-danger">Click to Change Picture</p>
                    </div>

                    @if (session()->has('success_saved'))
                        <div class="alert alert-success w-25" role="alert">
                            {{session('success_saved')}}
                        </div>
                    @endif
                    @if (session()->has('error_saved'))
                        <div class="alert alert-warning w-25" role="alert">
                            {{session('error_saved')}}
                        </div>
                    @endif

                    <div class="content-row">
                        
                        <div class="content-column">
                            <h5>Account Information</h5>
                            <div class="form-group highlight-addon mt-3" style="width: 100%;">
                                <label for="name">Nama</label>
                                <input type="text" name="name" id="name" class="form-control w-100 mt-1 border border-info" value="{{ isset($myData->name) ? $myData->name : '' }}">
                            </div>
                            <div class="form-group highlight-addon mt-3" style="width: 100%;">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control w-100 mt-1 border border-info" value="{{ isset($myData->email) ? $myData->email : '' }}">
                            </div>
                            <div class="form-group highlight-addon mt-3" style="width: 100%;">
                                <label for="phone_number">Phone Number</label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control w-100 mt-1 border border-info" value="{{ isset($myData->phone_number) ? $myData->phone_number : '' }}">
                            </div>
                        </div>
                        <div class="content-column">
                            <h5>Update Password</h5>
                            <div class="form-group highlight-addon mt-3" style="width: 100%;">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control w-100 mt-1 border border-info" autocomplete="new-password">
                            </div>
                            <div class="form-group highlight-addon mt-3" style="width: 100%;">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control w-100 mt-1 border border-info" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning mt-4 ms-4">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelector('.avatar-container').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });

        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.avatars').src = e.target.result;
                    document.getElementById('preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
