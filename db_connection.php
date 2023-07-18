<?php
        // Kết nối đến cơ sở dữ liệu
        $servername = "localhost:3306";
        $username = "root";
        $password = "";
        $dbname = "bruh";

        $connection = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($connection->connect_error) {
            die("Không thể kết nối đến cơ sở dữ liệu: " . $conn->connect_error);
        }
?>