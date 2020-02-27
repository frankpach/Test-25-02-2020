<?php
$ini = parse_ini_file('app.ini');
$servername = "localhost";
$username = "root";
$password = "";

$dbCreated = createDatabase($ini['server'], $ini['db_user'], $ini['db_password'], $ini['db_name']);

if (!$dbCreated[0]) {
    return $dbCreated[1];
}
return true;




function createDatabase($servername, $username, $password, $dbname)
{

    try {
        $conn = new PDO("mysql:host=$servername", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE " . $dbname;
        // use exec() because no results are returned
        $conn->exec($sql);
        $conn->exec('USE '.$dbname);

        $sql1 = "CREATE TABLE IF NOT EXISTS flyers (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            flyer_start_date DATE NOT NULL,
            flyer_end_date DATE NOT NULL,
            store_id INT NOT NULL,
            flyer_priority INT NOT NULL,
            category_id INT NOT NULL,
            FOREIGN KEY (store_id) REFERENCES stores(id),
            FOREIGN KEY (category_id) REFERENCES category_id(id)
            )";

        $sql2="CREATE  INDEX flyer_priority ON flyers(flyer_priority);";

        $sql3 = "CREATE TABLE IF NOT EXISTS stores (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL
            )";

        $sql4 = "CREATE TABLE IF NOT EXISTS categories (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            store_id INT(6) UNSIGNED NOT NULL,
            name VARCHAR(30) NOT NULL
            )";

        $sql5 = "CREATE TABLE IF NOT EXISTS page (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            flyer_id INT(6) UNSIGNED NOT NULL,
            page_number INT NOT NULL,
            filename VARCHAR(100),
            FOREIGN KEY (flyer_id) REFERENCES flyers(id)
            )";

        $sql6="CREATE  INDEX page_number ON page(page_number);";

        $sql = array($sql1,$sql2, $sql3, $sql4, $sql5, $sql6);

        foreach ($sql as $s){
            $conn->exec($s);
        }

        $conn = null;
        return array(true, '');

    } catch (PDOException $e) {
        $conn = null;
        return array(false, $sql . "\n" . $e->getMessage());
    }
}