<?php
// Ganti password sesuai keinginanmu
$password_baru = 'admin123';

// Generate Hash
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

echo "<h1>Password Hash Generator</h1>";
echo "Password: <b>" . $password_baru . "</b><br>";
echo "Hash Baru: <br><textarea cols='100' rows='2'>" . $hash_baru . "</textarea>";
?>