<?php
$hash = password_hash('admin123', PASSWORD_DEFAULT);
echo "Hash nuevo: $hash<br>";
echo "Verificación: ".password_verify('admin123', $hash) ? 'OK' : 'Fallo';
echo "<br>Verificación con hash de BD: ".password_verify('admin123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');