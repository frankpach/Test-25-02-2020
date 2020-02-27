<?php

$ini = parse_ini_file('app.ini');

$options = getopt('',['categoryID::']);

if(isset($options['categoryID'])){
    if(is_numeric($options['categoryID'])){
        $res = getData($ini['server'], $ini['db_name'], $ini['db_user'], $ini['db_password'], intval($options['categoryID']) );
    }
}

$res = getData($ini['server'], $ini['db_name'], $ini['db_user'], $ini['db_password']);

return $res;


function getData($servername, $dbname, $username, $password, $category=false ){
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($category){
            $query = $conn->query("SELECT flyers.id as id, flyers.flyer_start_date as flyer_start_date,
            flyers.flyer_end_date as flyer_end_date, categories.name as category, flyers.flyer_priority as flyer_priority,
            stores.name  as store FROM flyers INNER JOIN stores ON stores.id=flyers.store_id INNER JOIN
            categories ON flyers.category_id=categories.id 
            WHERE flyers.store_id = '" . $category . "' 
            AND flyers.flyer_start_date <= NOW() 
            AND flyers.flyer_end_date >= NOW()
            ORDER BY flyer_priority ASC")
                ->fetchAll();
        }else{
            $query = $conn->query("SELECT flyers.id,flyers.flyer_start_date,flyers.flyer_end_date,categories.name,
            flyers.flyer_priority, stores.name  FROM flyers INNER JOIN stores ON stores.id=flyers.store_id INNER JOIN
            categories ON flyers.category_id=categories.id 
            WHERE flyers.flyer_start_date <= NOW() 
            AND flyers.flyer_end_date >= NOW()
            ORDER BY flyer_priority ASC")
                ->fetchAll();
        }

        $conn = null;
        return($query);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}