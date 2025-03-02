<?php
// ถ้าเป็น ip ของ GBPrimePay ถึงจะให้ทำงานโค้ดใน if
 $white_list = [
    '203.151.205.45',
    '203.151.205.33',
    '18.143.213.62',
    '13.215.225.183',
    '54.254.171.101',
    '18.141.54.201',
    '54.151.232.117',
    '54.255.79.153'
];

if (in_array($_SERVER['REMOTE_ADDR'], $white_list)) {
    $respFile = fopen("resp-log.json", "w") or die("Unable to open file!");
    $json_str = file_get_contents('php://input');
    fwrite($respFile, $json_str . "nn");
    $json_obj = json_decode($json_str);
    // $result = $db->query("SELECT user_id FROM topup WHERE refcode = '{$json_obj->referenceNo}'");
    // $db->query("UPDATE users SET point = point + {$json_obj->amount} WHERE id = {$result->user_id}");
    fwrite($respFile, $json_obj);
    fclose($respFile);
} else {
    die('Access Denied');
}
