<?php
session_start();
require_once('../variable/variable.php');
require_once('../model/book.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;
    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    $id = isset($_GET['id']) ? $_GET['id'] : 1;
    
    $sql = "UPDATE book_user SET status=4 WHERE id = '".$id."'";
    $bookUser = $var->query($sql);

    if ($var->connection()->affected_rows == 0) {
        header("HTTP/1.1 400");
        echo json_encode([
            "status" => 'failed'
        ]);    
        return;
    }

    $sql = "SELECT * FROM book_user WHERE id = '".$id."'";
    $bookUser = $var->query($sql)->fetch_assoc();

    $sql = "UPDATE books SET stock= stock + ".$bookUser['quantity']." WHERE id='".$bookUser['book_id']."'";
    $bookUser = $var->query($sql);

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