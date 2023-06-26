<?php
session_start();
require_once('../variable/variable.php');
require_once('../model/bookuser.php');
require_once('../vendor/autoload.php');

use Carbon\Carbon;

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;
    $bookuser = new BookUser;
    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    $book = $var->query("UPDATE `books` SET stock = stock - ".$_POST['quantity']." WHERE id = '".$_POST['book_id']."';");

    if ($var->connection()->affected_rows == 0) {
        header("HTTP/1.1 400");
        echo json_encode([
            "status" => 'failed'
        ]);    
        return;
    }

    $data = [
        [
            'user_id' => $currentUser['role'] == 'Member' ? $currentUser['id'] : $_POST['user_id'],
            'book_id' => $_POST['book_id'],
            'quantity' => $_POST['quantity'],
            'borrow_date' => $_POST['borrow_date'],
            'due_date' => Carbon::parse($_POST['borrow_date'])->addDays(7)->format('Y-m-d'),
            'date_return' => null,
            'penalties' => null,
            'borrow_note' => $_POST['borrow_note'],
            'return_note' => null,
            'return_quantity' => null,
            'status' => 1,
        ]
    ];

    $sql = $bookuser->create($data);
    $bookuser = $var->query($sql);

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