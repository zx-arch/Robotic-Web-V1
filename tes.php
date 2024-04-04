<?php
// CONNECT SERVER
session_start();
// Mengambil nilai sesi
$username_login = $_SESSION['username'];
$role_login = $_SESSION['role'];
$app_url = $_SESSION['app_url'];

$currentAdminMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentAdminMenu']) ? $_SESSION['currentMenu']['currentAdminMenu'] : '');
$currentAdminSubMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentAdminSubMenu']) ? $_SESSION['currentMenu']['currentAdminSubMenu'] : '');

$currentPengurusMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentPengurusMenu']) ? $_SESSION['currentMenu']['currentPengurusMenu'] : '');
$currentPengurusSubMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentPengurusSubMenu']) ? $_SESSION['currentMenu']['currentPengurusSubMenu'] : '');

$currentUserMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentUserMenu']) ? $_SESSION['currentMenu']['currentUserMenu'] : '');
$currentUserSubMenu = (isset($_SESSION['currentMenu']) && isset($_SESSION['currentMenu']['currentUserSubMenu']) ? $_SESSION['currentMenu']['currentUserSubMenu'] : '');

if ($username_login == null) {
    echo '<script>window.location.href = "https://artec-indonesia.com";</script>';
    exit;
}
// Informasi koneksi database
$host = 'localhost';
$dbname = 'skesolut_robot';
$username = 'skesolut_robot';
$password = 'BotObot@8899#1';

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Menyiapkan statement SQL
    $statement = $pdo->prepare("SELECT * FROM category_tutorial");

    // Mengeksekusi statement
    $statement->execute();

    // Mengambil hasil query
    $kategori = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Menampilkan hasil
    // foreach ($kategori as $kategori_data) {
    //     echo $kategori_data['category'] . "<br>";
    // }
} catch (PDOException $e) {
    // Menampilkan pesan kesalahan jika koneksi gagal
    echo "Koneksi database gagal: " . $e->getMessage();
}

//Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    try {
        //Query input menginput data kedalam tabel anggota
        $sql = "INSERT INTO category_tutorial (category, status_id) VALUES (:category, :status_id)";
        $statement = $pdo->prepare($sql);

        // Data yang akan disisipkan
        $category = input($_POST["category"]);
        $status_id = input($_POST["status_id"]);

        // Bind parameter ke statement
        $statement->bindParam(':category', $category, PDO::PARAM_STR);
        $statement->bindParam(':status_id', $status_id, PDO::PARAM_INT);

        // Menjalankan statement
        $statement->execute();

        echo "<div class='alert alert-danger'>Data berhasil disimpan.</div>";
    } catch (PDOException $e) {
        // Menampilkan pesan kesalahan jika koneksi gagal
        echo "<div class='alert alert-danger'>Data Gagal disimpan: " . $e->getMessage() . "</div>";
    }
}
?>


<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');

        dropzone.addEventListener('dragover', function (event) {
            event.preventDefault();
            this.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function () {
            this.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function (event) {
            event.preventDefault();
            this.classList.remove('dragover');
            const file = event.dataTransfer.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function () {
                    const img = new Image();
                    img.src = reader.result;
                    dropzone.innerHTML = '';
                    dropzone.appendChild(img);

                    // Menampilkan nama dan ukuran file
                    const fileInfo = document.createElement('p');
                    fileInfo.textContent = `Name: ${file.name}, Size: ${formatBytes(file.size)}`;
                    fileInfo.classList.add('file-info'); // Tambahkan kelas untuk styling
                    dropzone.appendChild(fileInfo);
                    document.getElementById('imageInput').value = reader.result;

                };

                reader.readAsDataURL(file);
            } else {
                alert('Please drop an image file.');
            }
        });

        // Fungsi untuk mengubah ukuran file menjadi format yang lebih mudah dibaca
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

    });
</script>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* Style for invalid inputs */
        .is-invalid {
            border-color: #dc3545 !important;
            /* Border color red */
        }

        /* Style for valid inputs */
        .is-valid {
            border-color: #28a745 !important;
            /* Border color green */
        }

        /* Style for invalid feedback */
        .invalid-feedback {
            color: #dc3545;
            /* Text color red */
            font-size: 80%;
            /* Font size smaller */
        }

        /* Style for valid feedback */
        .valid-feedback {
            color: #28a745;
            /* Text color green */
            font-size: 80%;
            /* Font size smaller */
        }
    </style>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="content-header" style="padding: 4px .5rem;">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light"
            style="max-height: 50px;margin-top:-5px;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="<?= $app_url; ?>logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="<?= $app_url; ?>logout " method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="3aXOHwIchXvJ2OY71P7Ziivj8TFrclcH6PNo72iN">
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <script>
        document.getElementById('logout-form').addEventListener('submit', function (event) {
            this.submit();
        });
    </script>


    <div class="wrapper">

        <style>
            .submenu {
                display: none;
            }

            .submenu-toggle:checked+.submenu {
                display: block;
            }

            .menu-text,
            .menu-text:hover {
                color: #c2c7d0;
                /* putih */
            }


            .nav-icon,
            .nav-icon:hover {
                color: #c2c7d0;
                /* putih */
            }
        </style>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-1 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= $app_url; ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                            alt="User Image" onclick="window.location.href=`<?= $app_url; ?><?= $role_login; ?>`">
                    </div>
                    <div class="info">
                        <a href="<?= $app_url; ?><?= $role_login; ?>" class="d-block"
                            style="font-size: 15px; color: rgb(252, 252, 252); text-align: center;margin-bottom:15px;">
                            <?= $username_login; ?>
                        </a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <?php if ($role_login == 'pengurus'): ?>
                            <li class="nav-item">
                                <a href="<?= $app_url; ?><?= $role_login; ?>"
                                    class="nav-link <?= isset($currentPengurusMenu) && $currentPengurusMenu == 'preview' ? 'active' : ''; ?>">
                                    <i
                                        class="nav-icon fas fa-tachometer-alt <?= isset($currentPengurusMenu) && $currentPengurusMenu == 'preview' ? 'text-dark' : ''; ?>"></i>&nbsp;
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $app_url; ?><?= $role_login; ?>/tutorials"
                                    class="nav-link <?= isset($currentPengurusMenu) && $currentPengurusMenu == 'tutorials' ? 'active' : ''; ?>">
                                    <i
                                        class="nav-icon fa fa-play <?= isset($currentPengurusMenu) && $currentPengurusMenu == 'tutorials' ? 'text-dark' : ''; ?>"></i>&nbsp;
                                    <p>Tutorials</p>
                                </a>
                            </li>

                        <?php elseif ($role_login == 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?= $app_url; ?><?= $role_login; ?>" class="nav-link ">
                                    <i class="nav-icon fas fa-tachometer-alt "></i>&nbsp;<p>Dashboard</p>
                                </a>
                            </li>
                            <li
                                class="nav-item has-treeview <?= isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'menu-open' : ''; ?>">
                                <div class="nav-link toggle-label <?= isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'active' : ''; ?>"
                                    onclick="toggleSubMenu(event)">
                                    <i
                                        class="nav-icon fa fa-play <?= isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'text-white' : ''; ?>"></i>&nbsp;
                                    <p
                                        class="menu-text <?= isset($currentAdminMenu) && $currentAdminMenu == 'tutorials' ? 'text text-white' : ''; ?>">
                                        Tutorials<i class="fas fa-angle-left right"></i></p>
                                </div>
                                <ul class="nav nav-treeview submenu">
                                    <li class="nav-item">
                                        <a href="<?= $app_url; ?><?= $role_login; ?>/tutorials"
                                            class="nav-link <?= isset($currentAdminSubMenu) && $currentAdminSubMenu == 'preview' ? 'active' : ''; ?>">
                                            <i
                                                class="nav-icon far fa-circle <?= isset($currentAdminSubMenu) && $currentAdminSubMenu == 'preview' ? 'text-dark' : ''; ?>"></i>&nbsp;
                                            <p>Preview</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= $app_url; ?><?= $role_login; ?>/category_tutorial"
                                            class="nav-link <?= isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category_tutorial' ? 'active' : ''; ?>">
                                            <i
                                                class="nav-icon far fa-circle <?= isset($currentAdminSubMenu) && $currentAdminSubMenu == 'category_tutorial' ? 'text-dark' : ''; ?>"></i>&nbsp;
                                            <p>Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>&nbsp;
                                            <p>Anomali Tutorial</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $app_url; ?><?= $role_login; ?>/aktivitas_pengguna" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>&nbsp;
                                    <p>Daftar Pengguna</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $app_url; ?><?= $role_login; ?>/language_translate" class="nav-link">
                                    <i class="nav-icon fa fa-globe"></i>&nbsp;
                                    <p>Language</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('aktivitas_pengguna.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-history"></i>&nbsp;
                                    <p>Aktivitas Pengguna</p>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>

                </nav>
            </div>
        </aside>
        <div class="content-wrapper" style="padding-top: 5px;">
            <div class="container-fluid">
                <style>
                    .dropzone {
                        border: 2px dashed #ccc;
                        padding: 20px;
                        text-align: center;
                        margin-top: auto;
                        width: 450px;
                        height: 300px;
                    }

                    .dropzone img {
                        max-width: 70%;
                        max-height: 70%;
                    }

                    .file-info {
                        margin-top: 10px;
                    }
                </style>
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="p-2">Add Tutorials</h5>
                            <p>
                                <?= $currentAdminMenu; ?>
                            </p>
                            <div class="card card-default">
                                <div class="card-body p-0">
                                    <div class="container mb-3 mt-3">
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
                                            enctype="multipart/form-data">
                                            <input type="hidden" name="_token"
                                                value="3aXOHwIchXvJ2OY71P7Ziivj8TFrclcH6PNo72iN">
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group highlight-addon has-success">
                                                                <label for="video_name">Video Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="video_name" id="video_name"
                                                                    required class="form-control">
                                                                <div class="invalid-feedback"></div>
                                                            </div>

                                                            <div class="form-group highlight-addon has-success">
                                                                <label for="category">Category <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="category" id="category"
                                                                    class="form-control w-50" required>
                                                                    <option value="" disabled selected>Choose Category
                                                                        ..</option>
                                                                    <option
                                                                        value="{&quot;id&quot;:1,&quot;category&quot;:&quot;Product Video&quot;,&quot;created_at&quot;:&quot;2024-03-28T07:20:09.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-02T05:55:47.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:1,&quot;category&quot;:&quot;Product
                                                                        Video&quot;,&quot;created_at&quot;:&quot;2024-03-28T07:20:09.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-02T05:55:47.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:2,&quot;category&quot;:&quot;Software Requirement&quot;,&quot;created_at&quot;:&quot;2024-03-28T07:20:09.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-01T02:45:28.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:2,&quot;category&quot;:&quot;Software
                                                                        Requirement&quot;,&quot;created_at&quot;:&quot;2024-03-28T07:20:09.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-01T02:45:28.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:16,&quot;category&quot;:&quot;STEM Indonesia&quot;,&quot;created_at&quot;:&quot;2024-04-02T03:56:44.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-02T03:56:44.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:1,&quot;delete_html_code&quot;:&quot;&lt;a class=\&quot;btn btn-danger btn-sm btn-delete\&quot; href=\&quot;https:\/\/artec-indonesia.com\/admin\/category_tutorial\/delete\/eyJpdiI6IklOZnZoUVM5NVNYOFFDQUhLZEFlcGc9PSIsInZhbHVlIjoidmhZeWttekE1Z2krRUpzUGltelhFZz09IiwibWFjIjoiOGNjNzVlY2I5NDUyYmM4ZTQ5NDBkMjQxNGIwZTA0NjM5NDljNmU1Yjk0OGY4ZTljZWI5Njg1NTEzNTMzZTA2ZSIsInRhZyI6IiJ9\&quot;&gt;&lt;i class=\&quot;fa-fw fas fa-trash\&quot; aria-hidden&gt;&lt;\/i&gt;&lt;\/a&gt;&quot;}">
                                                                        {&quot;id&quot;:16,&quot;category&quot;:&quot;STEM
                                                                        Indonesia&quot;,&quot;created_at&quot;:&quot;2024-04-02T03:56:44.000000Z&quot;,&quot;updated_at&quot;:&quot;2024-04-02T03:56:44.000000Z&quot;,&quot;status_id&quot;:11,&quot;valid_deleted&quot;:1,&quot;delete_html_code&quot;:&quot;&lt;a
                                                                        class=\&quot;btn btn-danger btn-sm
                                                                        btn-delete\&quot;
                                                                        href=\&quot;https:\/\/artec-indonesia.com\/admin\/category_tutorial\/delete\/eyJpdiI6IklOZnZoUVM5NVNYOFFDQUhLZEFlcGc9PSIsInZhbHVlIjoidmhZeWttekE1Z2krRUpzUGltelhFZz09IiwibWFjIjoiOGNjNzVlY2I5NDUyYmM4ZTQ5NDBkMjQxNGIwZTA0NjM5NDljNmU1Yjk0OGY4ZTljZWI5Njg1NTEzNTMzZTA2ZSIsInRhZyI6IiJ9\&quot;&gt;&lt;i
                                                                        class=\&quot;fa-fw fas fa-trash\&quot;
                                                                        aria-hidden&gt;&lt;\/i&gt;&lt;\/a&gt;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:17,&quot;category&quot;:&quot;dukun&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:20,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:17,&quot;category&quot;:&quot;dukun&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:20,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:18,&quot;category&quot;:&quot;sate&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:22,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:18,&quot;category&quot;:&quot;sate&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:22,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:19,&quot;category&quot;:&quot;stem&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:12,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:19,&quot;category&quot;:&quot;stem&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:12,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:20,&quot;category&quot;:&quot;tes&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:12,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:20,&quot;category&quot;:&quot;tes&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:12,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:21,&quot;category&quot;:&quot;tes&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:1278,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:21,&quot;category&quot;:&quot;tes&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:1278,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:22,&quot;category&quot;:&quot;p&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:0,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:22,&quot;category&quot;:&quot;p&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:0,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:23,&quot;category&quot;:&quot;p&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:0,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:23,&quot;category&quot;:&quot;p&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:0,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:24,&quot;category&quot;:&quot;abcdef&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:343434,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:24,&quot;category&quot;:&quot;abcdef&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:343434,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                    <option
                                                                        value="{&quot;id&quot;:25,&quot;category&quot;:&quot;abcdef&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:343434,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}">
                                                                        {&quot;id&quot;:25,&quot;category&quot;:&quot;abcdef&quot;,&quot;created_at&quot;:null,&quot;updated_at&quot;:null,&quot;status_id&quot;:343434,&quot;valid_deleted&quot;:0,&quot;delete_html_code&quot;:&quot;&quot;}
                                                                    </option>
                                                                </select>
                                                                <div class="invalid-feedback"></div>
                                                            </div>

                                                            <div class="form-group highlight-addon has-success">
                                                                <label for="youtube">Link URL <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="url_link" required
                                                                    id="url_link" required class="form-control w-75">
                                                                <div class="invalid-feedback"></div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-lg-4 ml-3">
                                                    <div class="dropzone mb-3" id="dropzone">
                                                        <p>Drag an image here <span class="text-danger">*</span></p>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="image" id="imageInput">

                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/chart.js/Chart.min.js"></script>
    <script src="plugins/sparklines/sparkline.js"></script>
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="dist/js/demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>