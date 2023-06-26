<?php
session_start();
require_once('../variable/variable.php');
require_once('../model/book.php');

header('Content-type: application/json');

if (isset($_SESSION['login'])) {
    $var = new Variable;
    $book = new Book;
    $currentUser = $_SESSION['login']['user'];
    $currentUser['role'] = $var->getRole($currentUser['role']);

    if ($currentUser['role'] != 'Superadmin' && $currentUser['role'] != 'Admin') {
        header("HTTP/1.1 403");
        echo json_encode('unauthorize');
        return;
    }

    $thumbnail = 'default_placeholder.png';

    if (isset($_FILES['thumbnail'])) {
        $upload = $var->uploadImage($_FILES['thumbnail']);
        $thumbnail = $upload['filename']; 
    }

    $data = [
        [
            'title' => $_POST['title'],
            'writer' => $_POST['writer'],
            'publisher' => $_POST['publisher'],
            'thumbnail' => $thumbnail,
            'note' => $_POST['note'],
            'stock' => $_POST['stock'],
        ]
    ];

    $sql = $book->create($data);
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