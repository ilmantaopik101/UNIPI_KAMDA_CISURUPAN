<?php
header('Content-Type: application/json');

// ===== DB =====
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_unipi";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Koneksi gagal: ".$conn->connect_error]);
    exit;
}

// ===== Ambil input (mendukung form-data, x-www-form-urlencoded, atau raw JSON) =====
$input = $_POST;
$raw = file_get_contents('php://input');
if (empty($input) && !empty($raw)) {
    $json = json_decode($raw, true);
    if (is_array($json)) $input = $json;
}

// ambil & trim
$username = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? trim($input['password']) : '';
$role     = isset($input['role']) ? trim($input['role']) : 'mahasiswa';

// validasi
if (empty($username) || empty($password)) {
    echo json_encode(["status"=>"error","message"=>"Username dan password wajib diisi"]);
    exit;
}

// OPTIONAL: cek role valid (jika mau)
// -> kamu bisa cek list enum di phpMyAdmin dan sesuaikan jika perlu

// cek apakah username sudah ada
$stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
if (!$stmt) {
    echo json_encode(["status"=>"error","message"=>"Prepare gagal: ".$conn->error]);
    exit;
}
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"Username sudah terdaftar"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// hash password dan insert
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
if (!$stmt) {
    echo json_encode(["status"=>"error","message"=>"Prepare insert gagal: ".$conn->error]);
    exit;
}
$stmt->bind_param("sss", $username, $hash, $role);
if ($stmt->execute()) {
    $newId = $conn->insert_id; // id_user baru
    echo json_encode(["status"=>"success","message"=>"Register berhasil","data"=>["id_user"=>$newId,"username"=>$username,"role"=>$role]]);
} else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
