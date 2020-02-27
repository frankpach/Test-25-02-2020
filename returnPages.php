<?php

$ini = parse_ini_file('app.ini');

$options = getopt('', ['flyerID:']);

if (isset($options['flyerID'])) {
    if (is_numeric($options['flyerID'])) {
        $res = getData($ini['server'], $ini['db_name'], $ini['db_user'], $ini['db_password'], intval($options['flyerID']));
    }
}

$res = getData($ini['server'], $ini['db_name'], $ini['db_user'], $ini['db_password']);

return $res;

function getData($servername, $dbname, $username, $password, $flyerId = false)
{
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $conn->query("SELECT page.page_number as page_number, page.filename as file_name FROM page
            WHERE page.flyer_id = '" . $flyerId . "' 
            ORDER BY page_number ASC")
            ->fetchAll();

        $conn = null;
        return $query;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}