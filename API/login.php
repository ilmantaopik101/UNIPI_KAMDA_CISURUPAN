<?php
// testpostmannya : x-www-form-urlencoded
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_unipi";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi gagal: " . $conn->connect_error
    ]);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Username dan password wajib diisi"
    ]);
    exit;
}

$sql = "SELECT id_user, password, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Prepare statement gagal: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login berhasil!",
            "data" => [
                "id_user" => $user['id_user'],
                "role"    => $user['role']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username atau password salah."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Username atau password salah."
    ]);
}

$stmt->close();
$conn->close();
?>
