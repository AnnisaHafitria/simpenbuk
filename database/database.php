<?php
require_once('./variable/variable.php');
require_once('./model/user.php');
require_once('./model/book.php');

Class Migration
{
    public function __construct()
    {
        $var = new Variable;

        // create sp
        $sql = "
        CREATE PROCEDURE IF NOT EXISTS dropforeignkey()
        BEGIN
            DECLARE total     INT;

            SELECT 
            COUNT(*)
            INTO total
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = 'simpenbuk' 
            AND TABLE_NAME = 'book_user';
            
            IF total > 0 THEN
                ALTER TABLE book_user DROP FOREIGN KEY book_user_ibfk_1;
                ALTER TABLE book_user DROP FOREIGN KEY book_user_ibfk_2;
            END IF;
        END
        ";
        $var->query($sql);

        // call sp
        $sql = "CALL dropforeignkey()";
        $var->query($sql);

        // drop table users
        $sql = "DROP TABLE IF EXISTS users";
        $var->query($sql);

        // create table users
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(30) NOT NULL,
            password VARCHAR(50) NOT NULL,
            phone VARCHAR(30) NOT NULL,
            picture VARCHAR(30),
            address LONGTEXT NOT NULL,
            gender CHAR(1) NOT NULL,
            role CHAR(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE (email)
        )";
        $var->query($sql);

        // drop table books
        $sql = "DROP TABLE IF EXISTS books";
        $var->query($sql);

        // create table books
        $sql = "CREATE TABLE IF NOT EXISTS books (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(30) NOT NULL,
            writer VARCHAR(30) NOT NULL,
            publisher VARCHAR(30) NOT NULL,
            thumbnail VARCHAR(30) NULL,
            note LONGTEXT,
            stock INT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $var->query($sql);

        // drop table book_user
        $sql = "DROP TABLE IF EXISTS book_user";
        $var->query($sql);

        // create table book_user
        $sql = "CREATE TABLE IF NOT EXISTS book_user (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            book_id INT UNSIGNED NOT NULL,
            quantity INT UNSIGNED NOT NULL,
            borrow_date DATETIME,
            due_date DATETIME,
            date_return DATETIME,
            penalties INT,
            borrow_note LONGTEXT,
            return_note LONGTEXT,
            return_quantity INT UNSIGNED,
            status CHAR(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) 
            REFERENCES books(id),
            FOREIGN KEY (user_id) 
            REFERENCES users(id)
        )";
        $var->query($sql);

        $sql = "DROP PROCEDURE IF EXISTS dropforeignkey";
        $var->query($sql);

        // generate dummy data
        $this->seeder();
    }

    private function seeder()
    {
        $users = [
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@example.com',
                'password' => md5('superadmin'),
                'phone' => '081234567890',
                'picture' => 'avatar_default.png',
                'address' => 'Indonesia',
                'gender' => '1',
                'role' => 1
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => md5('admin'),
                'phone' => '081234567891',
                'picture' => 'avatar_default.png',
                'address' => 'Indonesia',
                'gender' => '0',
                'role' => 2
            ]
        ];

        $var = new Variable;
        $user = new User;
        $book = new Book;

        $sql = $user->create($users);
        $var->query($sql);

        $books = [
            [
                'title' => 'Lorem Ipsum',
                'writer' => 'John Doe',
                'publisher' => 'Lorem',
                'thumbnail' => 'default_placeholder.png',
                'note' => null,
                'stock' => 20
            ],
            [
                'title' => 'Dolor Sit',
                'writer' => 'John Doe',
                'publisher' => 'Lorem',
                'thumbnail' => 'default_placeholder.png',
                'note' => null,
                'stock' => 15
            ],
            [
                'title' => 'Lorem Amet',
                'writer' => 'John Doe',
                'publisher' => 'Lorem',
                'thumbnail' => 'default_placeholder.png',
                'note' => null,
                'stock' => 17
            ]
        ];

        $sql = $book->create($books);
        $var->query($sql);
    }
}

new Migration;