<?php
session_start();
require_once('../variable/variable.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;

    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    if ($currentUser['role'] != 'Superadmin' && $currentUser['role'] != 'Admin') {
        header("HTTP/1.1 403");
        echo json_encode('unauthorize');
        return;
    }

    $id = isset($_GET['id']) ? $_GET['id'] : 1;

    $sql = "DELETE FROM books WHERE id = {id}";
    $sql = str_replace('{id}', $id, $sql);
    $book = $var->query($sql);

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