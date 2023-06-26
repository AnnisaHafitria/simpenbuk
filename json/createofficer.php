<?php
session_start();
require_once('../variable/variable.php');
require_once('../model/user.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;
    $user = new User;
    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    if ($currentUser['role'] != 'Superadmin') {
        header("HTTP/1.1 403");
        echo json_encode('unauthorize');
        return;
    }

    $picture = 'avatar_default.png';

    if (isset($_FILES['picture'])) {
        $upload = $var->uploadImage($_FILES['picture']);
        $picture = $upload['filename']; 
    }

    $data = [
        [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password']),
            'phone' => $_POST['phone'],
            'picture' => $picture,
            'address' => $_POST['address'],
            'gender' => $_POST['gender'],
            'role' => $_POST['role']
        ]
    ];

    $sql = $user->create($data);
    $officer = $var->query($sql);

    if ($var->connection()->affected_rows == 0) {
        header("HTTP/1.1 400");
        echo json_encode([
            "status" => 'failed'
        ]);    
        return;
    }
    
    echo json_encode([
        "status" => 'success'
    ]);
    return;
}

header("HTTP/1.1 403");
echo json_encode('unauthorize');
return;