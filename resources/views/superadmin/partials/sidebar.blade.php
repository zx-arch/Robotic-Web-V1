<style>
    .submenu {
        display: none;
    }

    .submenu-toggle:checked + .submenu {
        display: block;
    }

    .menu-text,
    .menu-text:hover {
        color: #c2c7d0; /* putih */
    }

    .nav-icon,
    .nav-icon:hover {
        color: #c2c7d0; /* putih */
    }

</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/img/logo-user-image.png') }}" class="img-circle elevation-2" alt="User Image" onclick="window.location.href=`/admin`">
            </div>
            <div class="info">
                <a href="{{route('admin.dashboard')}}" class="d-block" style="font-size: 15px; color: rgb(252, 252, 252); text-align: center;">
                    {{ Auth::user()->username }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt {{isset($currentAdminMenu) && $currentAdminMenu == 'dashboard' ? 'text-white' : ''}}"></i>&nbsp;<p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{route('admin.courses.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'courses' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-th-large {{isset($currentAdminMenu) && $currentAdminMenu == 'courses' ? 'text-white' : ''}}"></i>&nbsp;<p>Courses</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{route('tutorials.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-play {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'text-white' : ''}}"></i>&nbsp;<p>Tutorials</p>
                    </a>
                </li> --}}
                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fa fa-play {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'text text-white' : ''}}">Tutorials<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="{{route('tutorials.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'preview' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'preview' ? 'text-dark' : ''}}"></i>&nbsp;<p>Preview</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('category_tutorial.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category_tutorial' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category_tutorial' ? 'text-dark' : ''}}"></i>&nbsp;<p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'anomali_tutorial' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) &&$currentAdminSubMenu == 'anomali_tutorial' ? 'text-dark' : ''}}"></i>&nbsp;<p>Anomali Tutorial</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.chat_dashboard.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'chat_dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-comments {{isset($currentAdminMenu) && $currentAdminMenu == 'chat_dashboard' ? 'text-white' : ''}}"></i>&nbsp;<p>Chat <span class="badge right badge-success" id="countMessage">{{session()->has('countChat') ? session('countChat') : 0}}</span></p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'authentication' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'authentication' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-users {{isset($currentAdminMenu) && $currentAdminMenu == 'authentication' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'authentication' ? 'text text-white' : ''}}">Authentication<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="{{route('daftar_pengguna.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'account' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'account' ? 'text-dark' : ''}}"></i>&nbsp;<p>Account</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ip_global.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'ip_global' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'ip_global' ? 'text-dark' : ''}}"></i>&nbsp;<p>IP Global</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ip_locked.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'ip_locked' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'ip_locked' ? 'text-dark' : ''}}"></i>&nbsp;<p>IP Locked</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('language.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'language' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-globe {{isset($currentAdminMenu) && $currentAdminMenu == 'language' ? 'text-white' : ''}}"></i>&nbsp;<p>Language</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('aktivitas_pengguna.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'aktivitas_pengguna' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-history {{isset($currentAdminMenu) && $currentAdminMenu == 'aktivitas_pengguna' ? 'text-white' : ''}}"></i>&nbsp;<p>Aktivitas Pengguna</p>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="{{route('admin.settings.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'settings' ? 'active' : ''}}">
                        <i class="nav-icon fa fa-cog {{isset($currentAdminMenu) && $currentAdminMenu == 'settings' ? 'text-white' : ''}}"></i>&nbsp;<p>Settings</p>
                    </a>
                </li>
            </ul>
            
        </nav>
    </div>
</aside>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
    // Simpan referensi ke fungsi log asli dari console
    var originalConsoleLog = console.log;

    // Override fungsi log dari console untuk mencegah pesan log dari Pusher
    console.log = function() {
        // Periksa apakah pesan log berasal dari Pusher
        if (arguments.length > 0 && typeof arguments[0] === 'string' && arguments[0].includes('Pusher :')) {
            // Jika ya, jangan cetak pesan log
            return;
        }
        // Jika bukan dari Pusher, cetak pesan log seperti biasa
        originalConsoleLog.apply(console, arguments);
    };
    // Pesan log dari Pusher yang mencetak ke konsol akan dihentikan, tetapi pesan log lainnya akan tetap dicetak ke konsol.

    fetch('/api/pusher-key')
        .then(response => response.json())

        .then(data => {
            var pusher = new Pusher(data.pusher_app_key, {
                cluster: data.pusher_app_cluster
            });

            var channel = pusher.subscribe('notify-channel');
            channel.bind('form-submit', function(data) {
                document.getElementById('countMessage').innerHTML = data.message.count_message;
            });
        })
    
    .catch(error => console.error('Error:', error));

</script>