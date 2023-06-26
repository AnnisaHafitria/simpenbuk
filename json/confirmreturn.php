<?php
session_start();
require_once('../variable/variable.php');
require_once('../model/book.php');
require_once('../vendor/autoload.php');

use Carbon\Carbon;

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

    $sql = "SELECT * FROM book_user WHERE id = '".$id."'";
    $bookUser = $var->query($sql)->fetch_assoc();

    $penalties = 0;
    $returnNote = $_POST['return_note'];
    $returnQuantity = $_POST['return_quantity'];

    $lateDate = Carbon::parse($bookUser['due_date'])->diffInDays(Carbon::now(), false);

    if ($lateDate > 0) {
        $penalties = $lateDate * 1000;
    }

    $sql = "UPDATE books SET stock= stock + ".$returnQuantity." WHERE id='".$bookUser['book_id']."'";
    $book = $var->query($sql);

    if ($var->connection()->affected_rows == 0) {
        header("HTTP/1.1 400");
        echo json_encode([
            "status" => 'failed'
        ]);    
        return;
    }

    $sql = "UPDATE book_user SET date_return=NOW(),penalties='".$penalties."',status=3,return_note='".$returnNote."',return_quantity='".$returnQuantity."' WHERE id = '".$id."'";
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