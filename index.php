<?php
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = ''; // Database password
$db   = 'try_crud'; // Database name

$connection = mysqli_connect($host, $user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
$no         = "";
$nama       = "";
$alamat     = "";
$role       = "";
$success    = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses Delete
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from kontrarian where id='$id'";
    $q1 = mysqli_query($connection, $sql1);
    if ($q1) {
        $success = "Data berhasil dihapus";
    } else {
        $error = "Data gagal dihapus";
    }
}

// Proses Edit
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM kontrarian WHERE id='$id'";
    $q1 = mysqli_query($connection, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $no     = $r1['no'];
    $nama   = $r1['nama'];
    $alamat = $r1['alamat'];
    $role   = $r1['role'];

    if ($no == '') {
        $error = "Data tidak ditemukan";
    }
}

// Proses Create dan Update
if (isset($_POST['submit'])) {
    $no = $_POST['no'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    if ($no && $nama && $alamat && $role) {
        if ($op == 'edit') {
            $id = $_GET['id']; // Pastikan ID terambil dari URL
            $sql1 = "UPDATE kontrarian SET no = '$no', nama = '$nama', alamat = '$alamat', role = '$role' WHERE id = '$id'";
            $q1 = mysqli_query($connection, $sql1);
            if ($q1) {
                $success = "Data berhasil diupdate";
                // Reset form setelah update
                $no = "";
                $nama = "";
                $alamat = "";
                $role = "";
                $op = "";
                // Redirect untuk menghindari resubmit form
                header("Location: index.php");
                exit();
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO kontrarian(no, nama, alamat, role) VALUES ('$no', '$nama', '$alamat', '$role')";
            $q1 = mysqli_query($connection, $sql1);
            if ($q1) {
                $success = "Data berhasil ditambahkan";
                // Reset form setelah insert
                $no = "";
                $nama = "";
                $alamat = "";
                $role = "";
            } else {
                $error = "Data gagal ditambahkan";
            }
        }
    } else {
        $error = "Semua field harus diisi";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontrarian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: #121212;
            --surface: #1e1e1e;
            --primary: #bb86fc;
            --secondary: #03dac6;
            --error: #cf6679;
            --success: #00e676;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-disabled: rgba(255, 255, 255, 0.5);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
        }

        .card {
            background-color: var(--surface);
            border: none;
            border-radius: 16px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: var(--surface);
            color: var(--primary);
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 20px;
        }

        .card-body {
            padding: 24px;
        }

        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: var(--bs-gray-500);
            padding: 12px 16px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(187, 134, 252, 0.3);
        }

        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--bs-gray);
            box-shadow: 0 0 0 3px rgba(187, 134, 252, 0.3);
        }

        .form-label {
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #000;
        }

        .btn-primary:hover {
            background-color: #a06be4;
            border-color: #a06be4;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
            color: #000;
        }

        .btn-success:hover {
            background-color: #00c853;
            border-color: #00c853;
        }

        .btn-info {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: #000;
        }

        .btn-info:hover {
            background-color: #00bfa5;
            border-color: #00bfa5;
        }

        .btn-danger {
            background-color: var(--error);
            border-color: var(--error);
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #b22a3c;
            border-color: #b22a3c;
        }

        .table {
            color: var(--text-primary);
        }

        .table th {
            color: var(--primary);
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 12px;
        }

        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 16px 12px;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .alert {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: none;
        }

        .alert-success {
            background-color: rgba(0, 230, 118, 0.15);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(207, 102, 121, 0.15);
            color: var(--error);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-buttons .btn {
            padding: 6px 12px;
            font-size: 14px;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Animation Effects */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .data-row {
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .data-row:nth-child(1) {
            animation-delay: 0.1s;
        }

        .data-row:nth-child(2) {
            animation-delay: 0.2s;
        }

        .data-row:nth-child(3) {
            animation-delay: 0.3s;
        }

        .data-row:nth-child(4) {
            animation-delay: 0.4s;
        }

        .data-row:nth-child(5) {
            animation-delay: 0.5s;
        }

        .data-row:nth-child(6) {
            animation-delay: 0.6s;
        }

        .data-row:nth-child(7) {
            animation-delay: 0.7s;
        }

        .data-row:nth-child(8) {
            animation-delay: 0.8s;
        }

        .data-row:nth-child(9) {
            animation-delay: 0.9s;
        }

        .data-row:nth-child(10) {
            animation-delay: 1s;
        }

        /* Modal styling */
        .modal-content {
            background-color: var(--surface);
            border-radius: 16px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 24px;
        }

        .modal-header .modal-title {
            color: var(--primary);
            font-weight: 600;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 24px;
        }

        .close {
            color: var(--text-secondary);
            opacity: 1;
        }

        .close:hover {
            color: var(--text-primary);
        }

        /* Badge styles */
        .badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 12px;
        }

        .badge-beginner {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }

        .badge-junior {
            background-color: rgba(33, 150, 243, 0.2);
            color: #2196F3;
        }

        .badge-mid-class {
            background-color: rgba(255, 152, 0, 0.2);
            color: #FF9800;
        }

        .badge-contrarian {
            background-color: rgba(233, 30, 99, 0.2);
            color: #E91E63;
        }

        /* Custom switch for dark mode */
        .theme-switch-wrapper {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .theme-switch {
            display: inline-block;
            position: relative;
            width: 60px;
            height: 34px;
        }

        .theme-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--primary);
        }

        input:focus+.slider {
            box-shadow: 0 0 1px var(--primary);
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .slider-icon {
            color: var(--text-secondary);
            font-size: 18px;
        }

        /* Loading spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .spinner-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 64px;
            color: var(--text-disabled);
            margin-bottom: 16px;
        }

        .empty-state h3 {
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--text-disabled);
            max-width: 400px;
            margin: 0 auto 24px;
        }
    </style>
</head>

<body>
    <!-- Theme switch -->
    <div class="theme-switch-wrapper">
        <span class="slider-icon"><i class="fas fa-sun"></i></span>
        <label class="theme-switch">
            <input type="checkbox" id="theme-toggle" checked>
            <span class="slider round"></span>
        </label>
        <span class="slider-icon"><i class="fas fa-moon"></i></span>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="spinner">
        <div class="spinner"></div>
    </div>

    <div class="container animate-fade-in">
        <div class="header">
            <h1>Data Kontrarian</h1>
            <p>Yang baca monyet</p>
        </div>

        <!-- input data -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i> <?php echo ($op == 'edit') ? 'Edit Data' : 'Tambah Data Baru'; ?>
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error ?>
                    </div>
                <?php
                } ?>
                <?php
                if ($success) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $success ?>
                    </div>
                <?php
                } ?>
                <form action="" method="POST" id="dataForm">
                    <div class="mb-3 row">
                        <label for="no" class="col-sm-2 form-label">No</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no" name="no" value="<?php echo $no ?>" placeholder="Masukkan nomor">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>" placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>" placeholder="Masukkan alamat">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="role" class="col-sm-2 form-label">Role</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="role" id="role">
                                <option value="">Pilih role</option>
                                <option value="Beginner" <?php if ($role == "Beginner") echo "selected" ?>>Beginner</option>
                                <option value="Junior" <?php if ($role == "Junior") echo "selected" ?>>Junior</option>
                                <option value="Mid-class" <?php if ($role == "Mid-class") echo "selected" ?>>Mid-class</option>
                                <option value="Contrarian" <?php if ($role == "Contrarian") echo "selected" ?>>Contrarian</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <?php if ($op == 'edit') { ?>
                            <a href="index.php" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        <?php } ?>
                        <button type="submit" class="btn btn-primary" name="submit">
                            <i class="fas fa-save me-1"></i> <?php echo ($op == 'edit') ? 'Update Data' : 'Simpan Data'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- output data -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-table me-2"></i> Data Kontrarian
                    </div>
                    <div>
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari data...">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NO</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT * FROM kontrarian ORDER BY id DESC";
                            $q2 = mysqli_query($connection, $sql2);
                            $urut = 1;
                            $num_rows = mysqli_num_rows($q2);

                            if ($num_rows > 0) {
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id     = $r2['id'];
                                    $no     = $r2['no'];
                                    $nama   = $r2['nama'];
                                    $alamat = $r2['alamat'];
                                    $role   = $r2['role'];

                                    // Badge class based on role
                                    $badge_class = '';
                                    switch (strtolower($role)) {
                                        case 'beginner':
                                            $badge_class = 'badge-beginner';
                                            break;
                                        case 'junior':
                                            $badge_class = 'badge-junior';
                                            break;
                                        case 'mid-class':
                                            $badge_class = 'badge-mid-class';
                                            break;
                                        case 'contrarian':
                                            $badge_class = 'badge-contrarian';
                                            break;
                                    }
                            ?>
                                    <tr class="data-row">
                                        <th scope="row"><?php echo $urut++ ?></th>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $nama ?></td>
                                        <td><?php echo $alamat ?></td>
                                        <td><span class="badge <?php echo $badge_class ?>"><?php echo $role ?></span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="index.php?op=edit&id=<?php echo $id ?>" class="btn btn-info btn-icon">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="#" class="btn btn-danger btn-icon" onclick="confirmDelete(<?php echo $id ?>)">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-database"></i>
                                            <h3>Tidak ada data</h3>
                                            <p>Belum ada data yang ditambahkan. Silakan tambahkan data baru menggunakan form di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="deleteConfirm" class="btn btn-danger">Hapus Data</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Delete confirmation
        function confirmDelete(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteConfirm = document.getElementById('deleteConfirm');
            deleteConfirm.href = `index.php?op=delete&id=${id}`;
            deleteModal.show();
        }

        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;

        // Check for saved theme preference
        if (localStorage.getItem('darkMode') === 'false') {
            themeToggle.checked = false;
            enableLightMode();
        }

        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                enableDarkMode();
                localStorage.setItem('darkMode', 'true');
            } else {
                enableLightMode();
                localStorage.setItem('darkMode', 'false');
            }
        });

        function enableDarkMode() {
            document.documentElement.style.setProperty('--background', '#121212');
            document.documentElement.style.setProperty('--surface', '#1e1e1e');
            document.documentElement.style.setProperty('--text-primary', '#ffffff');
            document.documentElement.style.setProperty('--text-secondary', 'rgba(255, 255, 255, 0.7)');
        }

        function enableLightMode() {
            document.documentElement.style.setProperty('--background', '#f5f5f5');
            document.documentElement.style.setProperty('--surface', '#ffffff');
            document.documentElement.style.setProperty('--text-primary', '#333333');
            document.documentElement.style.setProperty('--text-secondary', '#555555');
        }

        // Form submission animation
        const dataForm = document.getElementById('dataForm');
        const spinner = document.getElementById('spinner');

        dataForm.addEventListener('submit', function() {
            spinner.classList.add('show');
            setTimeout(() => {
                spinner.classList.remove('show');
            }, 1000);
        });

        // Table search functionality
        const searchInput = document.getElementById('searchInput');
        const dataTable = document.getElementById('dataTable');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = dataTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add animation to page load
        document.addEventListener('DOMContentLoaded', function() {
            // Display success messages temporarily
            const alertSuccess = document.querySelector('.alert-success');
            if (alertSuccess) {
                setTimeout(() => {
                    alertSuccess.style.opacity = '0';
                    setTimeout(() => {
                        alertSuccess.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });

        // Animation for button clicks
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 200);
            });
        });
    </script>
</body>

</html>