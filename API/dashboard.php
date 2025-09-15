<?php
session_start();
// untuk cek di postman pakai ini:http://localhost/UNIPI_KAMDA_CISURUPAN/API/dashboard.php?user_id=1&role=mahasiswa

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

$user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';
$role    = isset($_GET['role']) ? trim($_GET['role']) : '';

if(empty($user_id) || empty($role)){
    echo json_encode([
        "status" => "error",
        "message" => "User ID dan role wajib diisi"
    ]);
    exit;
}

$response = [];

if($role === 'admin'){
    $sql = "SELECT id_user, username, role FROM users";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        $response[] = $row;
    }
} elseif($role === 'mahasiswa'){
    $sql = "SELECT * FROM mahasiswa WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} elseif($role === 'dosen'){
    $sql = "SELECT * FROM dosen WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Role tidak dikenali"
    ]);
    exit;
}

echo json_encode([
    "status" => "success",
    "data"   => $response
]);

$conn->close();
?>
