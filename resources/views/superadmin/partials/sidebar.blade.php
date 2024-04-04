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
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image" onclick="window.location.href=`/admin`">
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
                    <a href="{{route('daftar_pengguna.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'daftar_pengguna' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-users {{isset($currentAdminMenu) && $currentAdminMenu == 'daftar_pengguna' ? 'text-white' : ''}}"></i>&nbsp;<p>Daftar Pengguna</p>
                    </a>
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
            </ul>
            {{-- <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt {{isset($currentAdminMenu) && $currentAdminMenu == 'dashboard' ? 'text-white' : ''}}"></i>&nbsp;<p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('Order.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'order' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-cart-plus {{isset($currentAdminMenu) && $currentAdminMenu == 'order' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'order' ? 'text-white' : ''}}">Order</p>
                        <span class="badge right badge-success">{{(session('totalOrder') ? session('totalOrder') : 0)}}</span>
                    </a>
                </li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'payment' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'payment' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-money-bill-wave {{isset($currentAdminMenu) && $currentAdminMenu == 'payment' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'payment' ? 'text text-white' : ''}}">Payment<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'payment_buyer' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'payment_buyer' ? 'text-dark' : ''}}"></i>&nbsp;<p>Payment Buyer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'payment_seller' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'payment_seller' ? 'text-dark' : ''}}"></i>&nbsp;<p>Payment Seller</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'anomali_pembayaran' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) &&$currentAdminSubMenu == 'anomali_pembayaran' ? 'text-dark' : ''}}"></i>&nbsp;<p>Anomali Pembayaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'bayar_full' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'bayar_full' ? 'text-dark' : ''}}"></i>&nbsp;<p>Bayar Full</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'catalog' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'catalog' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-book {{isset($currentAdminMenu) && $currentAdminMenu == 'catalog' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'catalog' ? 'text text-white' : ''}}">Catalog<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="{{route('daftarProduct.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'product' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'product' ? 'text-dark' : ''}}"></i>&nbsp;<p>Product</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('Categories.index')}}" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category' ? 'text-dark' : ''}}"></i>&nbsp;<p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'review' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSybMenu) && $currentAdminSubMenu == 'review' ? 'text-dark' : ''}}"></i>&nbsp;<p>Review</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'attribute' ? 'menu-open' : ''}}">
                    
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'attribute' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-band-aid" {{isset($currentAdminMenu) && $currentAdminMenu == 'attribute' ? 'text-white' : ''}}></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'attribute' ? 'text text-white' : ''}}">Attribute<i class="fas fa-angle-left right"></i></p>
                    </div>

                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'specs' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'product' ? 'text-dark' : ''}}"></i>&nbsp;<p>Specs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'specs_value' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'specs_value' ? 'text-dark' : ''}}"></i>&nbsp;<p>Specs Value</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'option' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'option' ? 'text-dark' : ''}}"></i>&nbsp;<p>Option</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'option_value' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'option_value' ? 'text-dark' : ''}}"></i>&nbsp;<p>Option Value</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'store' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'store' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-ship {{isset($currentAdminMenu) && $currentAdminMenu == 'store' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'store' ? 'text-white' : ''}}">Store <i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'store' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'store' ? 'text-dark' : ''}}"></i>&nbsp;<p>Store</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'transaksi' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'transaksi' ? 'text-dark' : ''}}"></i>&nbsp;<p>Transaksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'pajak' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'pajak' ? 'text-dark' : ''}}"></i>&nbsp;<p>Pajak</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">Users</li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'komplain' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-comments {{isset($currentAdminMenu) && $currentAdminMenu == 'komplain' ? 'text-white' : ''}}"></i>&nbsp;<p>Komplain</p>
                        <span class="badge right badge-success">{{(session('totalComplain') ? session('totalComplain') : 0)}}</span>
                    </a>
                </li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'mitra_kirim' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'mitra_kirim' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-ship" {{isset($currentAdminMenu) && $currentAdminMenu == 'mitra_kirim' ? 'text-white' : ''}}></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'mitra_kirim' ? 'text-white' : ''}}">Mitra Kirim<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_partner' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_partner' ? 'text-dark' : ''}}"></i>&nbsp;<p>Kurir Partner</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_produk' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_produk' ? 'text-dark' : ''}}"></i>&nbsp;<p>Kurir Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_pegawai' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'kurir_pegawai' ? 'text-dark' : ''}}"></i>&nbsp;<p>Kurir Pegawai</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'sekolah' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'sekolah' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-building {{isset($currentAdminMenu) && $currentAdminMenu == 'sekolah' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'sekolah' ? 'text-white' : ''}}">Sekolah<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sekolah' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sekolah' ? 'text-dark' : ''}}"></i>&nbsp;<p>Sekolah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'akun_sekolah' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'akun_sekolah' ? 'text-dark' : ''}}"></i>&nbsp;<p>Akun Sekolah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sekolah_sudah_transaksi' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sekolah_sudah_transaksi' ? 'text-dark' : ''}}"></i>&nbsp;<p>Sekolah Sudah Transaksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sumber_dana_sekolah' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'sumber_dana_sekolah' ? 'text-dark' : ''}}"></i>&nbsp;<p>Sumber Dana Sekolah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'anggaran_sekolah' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'anggaran_sekolah' ? 'text-dark' : ''}}"></i>&nbsp;<p>Anggaran Sekolah</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'pengawas' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-binoculars {{isset($currentAdminMenu) && $currentAdminMenu == 'pengawas' ? 'text-white' : ''}}"></i>&nbsp;
                        <p>Pengawas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('daftar_pengguna.index')}}" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'users' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-users {{isset($currentAdminMenu) && $currentAdminMenu == 'users' ? 'text-white' : ''}}"></i>&nbsp;<p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'aktifitas_pengguna' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-history {{isset($currentAdminMenu) && $currentAdminMenu == 'aktifitas_pengguna' ? 'text-white' : ''}}"></i>&nbsp;<p>Aktifitas Pengguna</p>
                    </a>
                </li>

                <li class="nav-header">Marketplace Setting</li>

                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'wilayah_jual' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'wilayah_jual' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-globe {{isset($currentAdminMenu) && $currentAdminMenu == 'wilayah_jual' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'wilayah_jual' ? 'text-white' : ''}}">Wilayah Jual<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_mapping' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_mapping' ? 'text-dark' : ''}}"></i>&nbsp;<p>Master Kode Mapping</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_kabupaten' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_kabupaten' ? 'text-dark' : ''}}"></i>&nbsp;<p>Master Kode Kabupaten</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_penjualan' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_kode_penjualan' ? 'text-dark' : ''}}"></i>&nbsp;<p>Master Kode Penjual</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{isset($currentAdminMenu) && $currentAdminMenu == 'bank' ? 'menu-open' : ''}}">
                    <div class="nav-link toggle-label {{isset($currentAdminMenu) && $currentAdminMenu == 'bank' ? 'active' : ''}}" onclick="toggleSubMenu(event)">
                        <i class="nav-icon fas fa-credit-card {{isset($currentAdminMenu) && $currentAdminMenu == 'bank' ? 'text-white' : ''}}"></i>&nbsp;
                        <p class="menu-text {{isset($currentAdminMenu) && $currentAdminMenu == 'bank' ? 'text-white' : ''}}">Bank<i class="fas fa-angle-left right"></i></p>
                    </div>
                    <ul class="nav nav-treeview submenu">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_bank' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'master_bank' ? 'text-dark' : ''}}"></i>&nbsp;<p>Master Bank</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'marketplace_bank' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'marketplace_bank' ? 'text-dark' : ''}}"></i>&nbsp;<p>Marketplace Bank</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'bank_mapping' ? 'active' : ''}}">
                                <i class="nav-icon far fa-circle {{isset($currentAdminSubMenu) && $currentAdminSubMenu == 'bank_mapping' ? 'text-dark' : ''}}"></i>&nbsp;<p>Bank Mapping</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">Content</li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{isset($currentAdminMenu) && $currentAdminMenu == 'static_pages' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-thumbtack {{isset($currentAdminMenu) && $currentAdminMenu == 'static_pages' ? 'text-white' : ''}}"></i>&nbsp;<p>Static pages</p>
                    </a>
                </li>
            </ul> --}}
        </nav>
    </div>
</aside>