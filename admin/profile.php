<?php
require_once 'header.php';

$message = '';
$error = '';
$user_id = $_SESSION['user_id'];

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if username already exists (excluding current user)
    $check_sql = "SELECT id FROM users WHERE username = '$username' AND id != '$user_id'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $error = "Username sudah digunakan oleh pengguna lain.";
    } else {
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $update_sql = "UPDATE users SET username = '$username', full_name = '$full_name'";
        
        // Handle Avatar Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['avatar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                // Define upload directory using absolute path
                $upload_dir = dirname(__DIR__) . '/uploads/avatars/';
                
                // Create directory if not exists
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $new_filename = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path)) {
                    // Delete old avatar if exists
                    if (!empty($user['avatar'])) {
                        $old_avatar_path = $upload_dir . $user['avatar'];
                        if (file_exists($old_avatar_path)) {
                            unlink($old_avatar_path);
                        }
                    }
                    $update_sql .= ", avatar = '$new_filename'";
                    $_SESSION['avatar'] = $new_filename; // Update session
                } else {
                    $error = "Gagal mengupload foto profil. Cek permissions folder uploads.";
                }
            } else {
                $error = "Format file tidak didukung (hanya JPG, JPEG, PNG, GIF).";
            }
        }
        
        if (!empty($password)) {
            if ($password === $confirm_password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_sql .= ", password = '$hashed_password'";
            } else {
                $error = "Konfirmasi password tidak cocok.";
            }
        }
        
        if (empty($error)) {
            $update_sql .= " WHERE id = '$user_id'";
            if ($conn->query($update_sql) === TRUE) {
                $message = "Profil berhasil diperbarui.";
                $_SESSION['username'] = $username; // Update session
                $_SESSION['full_name'] = $full_name; // Update session
                // Refresh user data
                $result = $conn->query($sql);
                $user = $result->fetch_assoc();
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Edit Profil
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <?php if (!empty($user['avatar']) && file_exists('../uploads/avatars/' . $user['avatar'])): ?>
                                        <img src="../uploads/avatars/<?php echo $user['avatar']; ?>" alt="Avatar" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-4x text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="avatar" class="form-label btn btn-sm btn-outline-primary">Ubah Foto</label>
                                    <input type="file" class="form-control d-none" id="avatar" name="avatar" accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5 class="mb-3">Ganti Password (Opsional)</h5>
                        <p class="text-muted small">Kosongkan jika tidak ingin mengubah password.</p>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // Check if image element exists, if not create placeholder replacement logic or just simple alert for now
            // But since we have a conditional render, let's just reload or trust the user sees the file name
            // Better: update the src of the img tag if it exists
            var img = document.querySelector('.img-thumbnail');
            if (img) {
                img.src = e.target.result;
            } else {
                // If placeholder exists, reload to show preview (or simpler: just show file name)
                // For simplicity, we won't do complex DOM manipulation here without seeing the full structure again
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once 'footer.php'; ?>