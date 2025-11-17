<?php
$password = "Geheim123";

$options = ['cost' => 12];

$hash = password_hash($password, PASSWORD_DEFAULT, $options);
if ($hash === false) {
    echo "Hashing failed\n";
    exit(1);
}

echo "Hash:\n$hash\n";

?>