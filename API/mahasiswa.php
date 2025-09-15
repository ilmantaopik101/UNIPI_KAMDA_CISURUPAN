<?php
header('Content-Type: application/json');
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_unipi";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"Koneksi gagal: ".$conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method === 'POST' && isset($_POST['_method'])){
    $method = strtoupper($_POST['_method']);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if($method === 'GET'){
    if(isset($_GET['id_user'])){
        // READ by id_user
        $id = intval($_GET['id_user']);
        $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id_user=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if($data){
            echo json_encode(["status"=>"success","data"=>$data]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Mahasiswa tidak ditemukan"]);
        }
    } else {
        // READ all
        $result = $conn->query("SELECT * FROM mahasiswa");
        $mahasiswa = [];
        while($row = $result->fetch_assoc()){
            $mahasiswa[] = $row;
        }
        echo json_encode(["status"=>"success","data"=>$mahasiswa]);
    }
}

elseif($method === 'POST'){
    $id_user = $input['id_user'] ?? '';
    $nama    = $input['nama'] ?? '';
    $nim     = $input['nim'] ?? '';
    $prodi   = $input['prodi'] ?? '';
    $email   = $input['email'] ?? '';
    $no_hp   = $input['no_hp'] ?? null;

    if(empty($id_user) || empty($nama) || empty($nim) || empty($prodi) || empty($email)){
        echo json_encode(["status"=>"error","message"=>"Field id_user, nama, nim, prodi, email wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO mahasiswa (id_user,nama,nim,prodi,email,no_hp) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss",$id_user,$nama,$nim,$prodi,$email,$no_hp);
    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Mahasiswa berhasil ditambahkan"]);
    } else {
        echo json_encode(["status"=>"error","message"=>$stmt->error]);
    }
    $stmt->close();
}

elseif($method === 'PUT'){
    $id_user = $input['id_user'] ?? '';
    $nama    = $input['nama'] ?? '';
    $nim     = $input['nim'] ?? '';
    $prodi   = $input['prodi'] ?? '';
    $email   = $input['email'] ?? '';
    $no_hp   = $input['no_hp'] ?? null;

    if(empty($id_user) || empty($nama) || empty($nim) || empty($prodi) || empty($email)){
        echo json_encode(["status"=>"error","message"=>"Field id_user, nama, nim, prodi, email wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE mahasiswa SET nama=?, nim=?, prodi=?, email=?, no_hp=? WHERE id_user=?");
    $stmt->bind_param("sssssi",$nama,$nim,$prodi,$email,$no_hp,$id_user);
    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Mahasiswa berhasil diupdate"]);
    } else {
        echo json_encode(["status"=>"error","message"=>$stmt->error]);
    }
    $stmt->close();
}

elseif($method === 'DELETE'){
    $id_user = $input['id_user'] ?? '';
    if(empty($id_user)){
        echo json_encode(["status"=>"error","message"=>"id_user wajib diisi"]);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id_user=?");
    $stmt->bind_param("i",$id_user);
    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Mahasiswa berhasil dihapus"]);
    } else {
        echo json_encode(["status"=>"error","message"=>$stmt->error]);
    }
    $stmt->close();
}

else{
    echo json_encode(["status"=>"error","message"=>"Method tidak dikenali"]);
}

$conn->close();
?>
