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
        $update_sql = "UPDATE users SET username = '$username'";
        
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
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
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

<?php require_once 'footer.php'; ?>