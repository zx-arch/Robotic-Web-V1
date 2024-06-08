<nav class="navbar navbar-expand px-4 py-3" style="height: 60px;background-color: #e3def9;">
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                    <img src="{{ asset('assets/img/account.png') }}" class="avatar img-fluid" alt="">
                </a>

                <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/account.png') }}" class="avatar img-fluid me-3" alt="">
                        <div>
                            <h6 class="mb-0">{{Auth::user()->username}}</h6>
                            <small>{{Auth::user()->email}}</small>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>

                    <a href="" class="dropdown-item"><i class="fas fa-cog" aria-hidden="true"></i> Settings</a>
                    <form action="{{route('logout')}}" method="post" style="margin: 0; padding: 0;">
                        @csrf
                        <button class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>

            </li>
        </ul>
    </div>
</nav>

<style>
.nav-icon::after {
    display: none !important; /* Menghilangkan panah dropdown */
}
</style>
