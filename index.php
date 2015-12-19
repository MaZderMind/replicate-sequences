<?php

// toolserver.org/~mazder/replicate-sequences/?2010-01-01T10:00:00Z
if(isset($_SERVER["QUERY_STRING"]) && preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/', $_SERVER["QUERY_STRING"], $match))
{
	$original = array_shift($match);
	$timestamp = vsprintf('%04u-%02u-%02u %02u:%02u:%02u', $match);
}
elseif(isset($_REQUEST['Y']) && isset($_REQUEST['m']) && isset($_REQUEST['d']) && isset($_REQUEST['H']) && isset($_REQUEST['i']) && isset($_REQUEST['s']))
{
	$timestamp = sprintf('%04u-%02u-%02u %02u:%02u:%02u', $_REQUEST['Y'], $_REQUEST['m'], $_REQUEST['d'], $_REQUEST['H'], $_REQUEST['i'], $_REQUEST['s']);
}
else
{
header('Content-Type: text/html; encoding=utf-8');
echo '<?xml version="1.0" encoding="utf-8" ?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
	<head>
		<title>replicate-sequences</title>
		
		<style type="text/css">
		form label { width: 80px; display: block; float: left; }
		form input { display: block; }
		form input[type=radio] { float: left; }
		</style>
	</head>
	<body>
		<h1>replicate-sequences</h1>
		
		<form action="#"><div>
			<fieldset>
				<legend>Timestamp</legend>
				
				<label for="Y">Year: </label>
				<input type="text" id="Y" name="Y" value="<?=date('Y')?>" size="2" />
				
				<label for="Y">Month: </label>
				<input type="text" id="m" name="m" value="<?=date('m')?>" size="2" />
				
				<label for="Y">Day: </label>
				<input type="text" id="d" name="d" value="<?=date('d')?>" size="2" />
				
				<label for="Y">Hour: </label>
				<input type="text" id="H" name="H" value="<?=date('H')?>" size="2" />
				
				<label for="Y">Minute: </label>
				<input type="text" id="i" name="i" value="<?=date('i')?>" size="2" />
				
				<label for="Y">Second: </label>
				<input type="text" id="s" name="s" value="<?=date('s')?>" size="2" />
			</fieldset>
			
			<fieldset>
				<legend>Stream</legend>
				<input type="radio" id="stream-minute" name="stream" value="minute" checked />
				<label for="stream-minute">Minutely</label>
				
				<input type="radio" id="stream-hour" name="stream" value="hour" />
				<label for="stream-hour">Hourly</label>
				
				<input type="radio" id="stream-day" name="stream" value="day" />
				<label for="stream-day">Daily</label>
			</fieldset>
			
			<input type="submit" value="fetch state-file" />
		</div></form>
		
		<hr />
		<a href='ma&#105;lto&#58;o&#115;&#109;%40mazd&#37;65rm%69nd&#37;2&#69;&#100;e'>osm&#64;mazd&#101;rmi&#110;d&#46;de</a> | 
		<a href="https://github.com/MaZderMind/replicate-sequences">get the source</a>
	</body>
</html>
<?php
exit;
}

include('conf.php');

switch($_GET['stream'])
{
	case 'hour':
		$conf['table'] = 'hour_replicate';
		$conf['base'] = 'http://planet.openstreetmap.org/replication/hour/';
		break;
	
	case 'day':
		$conf['table'] = 'day_replicate';
		$conf['base'] = 'http://planet.openstreetmap.org/replication/day/';
		break;
	
	default:
	case 'minute':
		$conf['table'] = 'minute_replicate';
		$conf['base'] = 'http://planet.openstreetmap.org/replication/minute/';
		break;
}

$db = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['db'], $conf['user'], $conf['password']);
$stm = $db->prepare('SELECT sequenceNumber, timestamp FROM '.$conf['table'].' WHERE timestamp < :timestamp ORDER BY timestamp DESC LIMIT 1;');
$stm->execute(array(
	'timestamp' => $timestamp
));
$row = $stm->fetch();

$seq = $row['sequenceNumber'];

$seqPath = str_pad($seq, 9, '0', STR_PAD_LEFT);
$seqPath = trim(chunk_split($seqPath, 3, '/'), '/');

$statefile = $conf['base'].$seqPath.'.state.txt';

header('Content-Disposition: inline; filename="state.txt"');
header('Content-Type: text/plain');
echo "#original-source: $statefile\n";
echo "#generated-by: ".(@$_SERVER['HTTPS'] ? 'https' : 'http')."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."\n";
echo file_get_contents($statefile);

?>
