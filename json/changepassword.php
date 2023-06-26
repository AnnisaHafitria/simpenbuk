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

    $data = [
        'password' => md5($_POST['password'])
    ];

    $id = isset($_GET['id']) ? $_GET['id'] : 1;
    
    $sql = $user->changePassword($data, $id);
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