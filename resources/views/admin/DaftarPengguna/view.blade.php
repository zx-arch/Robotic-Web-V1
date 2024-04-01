@extends('cms_login.index_admin')
@section('content')
    <div class="container-fluid">

        <div class="row mb-2 ml-2">
            <div class="col-sm-6 p-2">
                <h5 class="m-0 text-dark">{{$user->username}}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <section class="content">
                    <div class="container-fluid">

                        <div class="box">
                            <div class="box-body">
                                <div class="user-view">
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="btn btn-primary" href="{{ route('daftar_pengguna.update', ['user_id' => encrypt($user->id)]) }}" title="Update" aria-label="Update" data-pjax="0">Update</a>
                                        </div>
                                        <div class="card-body p-0">
                                            <table id="w0" class="table table-striped table-bordered detail-view">
                                                <tbody>
                                                    <tr>
                                                        <th>Id</th><td>{{$user->id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Username</th>
                                                        <td>{{$user->username}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>E-mail</th>
                                                        <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{$user->status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created at</th>
                                                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('M d, Y, h:i:s A')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Updated at</th>
                                                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at)->format('M d, Y, h:i:s A')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last login</th>
                                                        <td>{{$user->last_login ?? '(not set)'}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection