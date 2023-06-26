<?php

Class Variable {
    private
        $dbname = 'simpenbuk',
        $dbhost = 'localhost',
        $dbuser = 'root',
        $dbpass = '',
        $connection = null;

    public 
        $url = 'http://localhost/simpenbuk';

    public function __construct()
    {
        $this->connection = new mysqli(
            $this->dbhost,
            $this->dbuser,
            $this->dbpass,
            $this->dbname
        );

        // Check connection
        if ($this->connection->connect_error) {
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
    }

    public function get($key)
    {
        return $this->$key;
    }

    public function getRole($key)
    {
        switch ($key) {
            case 1:
                return 'Superadmin';
            case 2:
                return 'Admin';
            case 3:
                return 'Member';
            default:
                return 'Unknown';
        }
    }

    public function getStatus($key)
    {
        switch ($key) {
            case 1:
                return 'Booked';
            case 2:
                return 'Borrowed';
            case 3:
                return 'Returned';
            case 4:
                return 'Canceled';
            case 5:
                return 'Missing';
            default:
                return 'Unknown';
        }
    }

    public function query($query)
    {
        return $this->connection->query($query);
    }

    public function connection()
    {
        return $this->connection;
    }

    public function removeOldImage($file)
    {
        $target_dir = dirname(__FILE__, 2)."\\uploads\\";
        if (file_exists($target_dir.$file)) {
            unlink($target_dir.$file);
        } else {
            return false;
        }
    }

    public function uploadImage($file)
    {
        $target_dir = dirname(__FILE__, 2)."\\uploads\\";
        $imageFileType = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        $filename = time(). "." . $imageFileType;
        $target_file = $target_dir . $filename;
        $uploadOk = [];

        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $uploadOk[] = 1;
        } else {
            $uploadOk[] = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk[] = 0;
        }

        // Check file size
        if ($file["size"] > 5000000) {
            $uploadOk[] = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $uploadOk[] = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if (in_array(0,$uploadOk)) {
            return [
                'status' => false,
                'filename' => ''
            ];
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $uploadOk[] = 1;
            } else {
                $uploadOk[] = 0;
            }

            return [
                'status' => !in_array(0, $uploadOk),
                'filename' => $filename
            ];;
        }
    }
}