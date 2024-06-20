<nav class="navbar navbar-expand px-4 py-3" style="height: 60px;background-color: #e3def9;">
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown me-2 me-lg-1">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-3 notification-bell show" aria-expanded="true">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger" style="margin-bottom: -15px;">{{session()->has('info_notif.total_notif') ? session('info_notif.total_notif') : 0}}</span> <!-- Notifications badge -->
                </a>

                <div class="dropdown-menu dropdown-menu-end p-3 active" style="min-width: 200px;" data-bs-popper="static">
                    <h6 class="dropdown-header">Anda mempunyai {{session()->has('info_notif.total_notif') ? session('info_notif.total_notif') : 0}} pemberitahuan</h6>
                    <div class="dropdown-divider"></div>

                    <div id="notification-container">
                        @if (session()->has('info_notif.total_notif') && session('info_notif.total_notif') > 0)
                            @foreach (session('info_notif.notifications') as $notif)
                                <!-- Example notification item -->
                                <a href="{{ $notif->redirect }}" class="dropdown-item notification-item mb-1" style="{{$notif->read ? 'background-color: #e2e7ec' : ''}}">
                                    <div class="d-flex flex-column">
                                        <div class="notification-title">{{ $notif->title }}</div>
                                        <div class="notification-content">{{ $notif->content }}</div>
                                    </div>
                                </a>

                            @endforeach
                        @endif
                    </div>

                    @if (session()->has('info_notif.total_notif') > 5)
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center" id="view-all-notifications" data-more="true">View all notifications</a>
                    @endif
                </div>
            </li>

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

                    <a href="{{route('user.settings')}}" class="dropdown-item"><i class="fas fa-cog" aria-hidden="true"></i> Settings</a>
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
.notification-bell i {
    font-size: 1.5rem; /* Larger bell icon */
    margin-top: 5px;
}
.dropdown-menu {
    max-height: 560px; /* Set a maximum height */
    overflow-y: auto; /* Enable vertical scrolling */
}
.notification-item .notification-title, .notification-item .notification-content {
    width: 355px;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notification-item .notification-title {
    font-weight: bold;
}
.notification-item .notification-content {
    color: #5d6369; /* Bootstrap text-muted color */
}
</style>

<script>

    document.addEventListener("DOMContentLoaded", function() {

        const viewAllLink = document.getElementById('view-all-notifications');
        const notificationContainer = document.getElementById('notification-container');
        const notifications = {!! session('info_notif.notifications') ? json_encode(session('info_notif.notifications')) : null !!}; // Ambil data notifikasi dari PHP

        // Fungsi untuk menampilkan notifikasi
        function displayNotifications() {
            // Hapus notifikasi yang ada di dalam container
            notificationContainer.innerHTML = '';

            // Loop melalui data notifikasi dan tambahkan ke dalam container
            notifications.forEach((notif, index) => {
                // Hanya tampilkan lima notifikasi pertama
                if (index < 6) {
                    const notificationItem = document.createElement('a');
                    notificationItem.href = notif.redirect;
                    if (notif.read == '1' && notif.date_read) {
                        notificationItem.style.backgroundColor = '#e2e7ec';
                    } else {
                        notificationItem.style.backgroundColor = 'white';
                    }
                    notificationItem.classList.add('dropdown-item', 'notification-item', 'mb-1');

                    // Cek apakah event_date sudah kadaluarsa
                    let eventExpired = '';
                    if (notif.event_date_online) {
                        const eventDateOnline = new Date(notif.event_date_online);
                        const eventDateOffline = new Date(notif.event_date_offline);
                        const currentDate = new Date();
                        const daysDifferenceOnline = (currentDate - eventDateOnline) / (1000 * 60 * 60);
                        const daysDifferenceOffline = (currentDate - eventDateOffline) / (1000 * 60 * 60);
                        if (daysDifferenceOnline > 1 || daysDifferenceOnline > 1) {
                            eventExpired = '<span class="text-danger fw-normal" style="float: right;font-size: 16px;">Expired</span>';
                        }
                    }

                    notificationItem.innerHTML = `
                        <div class="d-flex flex-column">
                            <div class="notification-title">
                                ${notif.title}
                                ${eventExpired}
                            </div>
                            <div class="notification-content">${notif.content}</div>
                        </div>
                    `;
                    notificationContainer.appendChild(notificationItem);
                }
            });

            // Tampilkan tombol "View all notifications" jika ada lebih dari lima notifikasi
            if (notifications.length > 6) {
                viewAllLink.style.display = 'block';
            } else {
                viewAllLink.style.display = 'none';
            }
        }


        // Panggil fungsi untuk menampilkan notifikasi saat halaman dimuat
        displayNotifications();

        // Event listener untuk menangani klik pada tombol "View all notifications"
        viewAllLink.addEventListener('click', function(event) {
            event.preventDefault();

            // Tambahkan sisa notifikasi ke dalam container
            notifications.slice(6).forEach(notif => {
                const notificationItem = document.createElement('a');
                notificationItem.href = `${notif.redirect}`;

                if (`${notif.read}` == '1' && `${notif.date_read}`) {
                    notificationItem.style.backgroundColor = '#e2e7ec';
                } else {
                    notificationItem.style.backgroundColor = 'white';
                }
                
                notificationItem.classList.add('dropdown-item', 'notification-item', 'mb-1');
                // Cek apakah event_date sudah kadaluarsa
                    let eventExpired = '';
                    if (notif.event_date_online) {
                        const eventDateOnline = new Date(notif.event_date_online);
                        const eventDateOffline = new Date(notif.event_date_offline);
                        const currentDate = new Date();

                        const daysDifferenceOnline = (currentDate - eventDateOnline) / (1000 * 60 * 60);
                        const daysDifferenceOffline = (currentDate - eventDateOffline) / (1000 * 60 * 60);

                        if (daysDifferenceOnline > 1 || daysDifferenceOnline > 1) {
                            eventExpired = '<span class="text-danger fw-normal" style="float: right;font-size: 16px;">Expired</span>';
                        }
                    }

                    notificationItem.innerHTML = `
                        <div class="d-flex flex-column">
                            <div class="notification-title">
                                ${notif.title}
                                ${eventExpired}
                            </div>
                            <div class="notification-content">${notif.content}</div>
                        </div>
                    `;
                notificationContainer.appendChild(notificationItem);
            });

            // Sembunyikan tombol "View all notifications" setelah semua notifikasi ditampilkan
            viewAllLink.style.display = 'none';
        });

        // Menghentikan dropdown dari menutup saat klik di dalamnya
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

    });

</script>