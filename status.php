<?php

include('conf.php');

$db = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['db'], $conf['user'], $conf['password']);
$stm = $db->prepare('SELECT timestamp FROM minute_replicate WHERE timestamp < NOW() ORDER BY timestamp DESC LIMIT 1;');
$stm->execute();
$row = $stm->fetch();

$timestamp = strtotime($row['timestamp']);
$age = time() - $timestamp;
$is_ok = $age < 60*5;

if($is_ok) {
        echo 'OK';
}
else {
        http_response_code(500);
        echo 'Last Statefile too old ('.$age.' seconds)';
}

?>
