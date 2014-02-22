<?php

include('conf.php');

if(!$conf['abuse'] || $conf['abuse'] == 'your@email.here')
	die('you MUST configure an abuse-email');

fetch($conf + array(
	'table' => 'minute_replicate',
	'base' => 'http://planet.osm.org/replication/minute/',
	'firstSeq' => 1,
));


fetch($conf + array(
	'table' => 'hour_replicate',
	'base' => 'http://planet.osm.org/replication/hour/',
	'firstSeq' => 1,
));


fetch($conf + array(
	'table' => 'day_replicate',
	'base' => 'http://planet.osm.org/replication/day/',
	'firstSeq' => 1,
));


function fetch($conf)
{
	$db = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['db'], $conf['user'], $conf['password']);
	$stm = $db->prepare('INSERT IGNORE INTO '.$conf['table'].' (sequenceNumber, timestamp) VALUES (:sequenceNumber, :timestamp)');

	$ctx = stream_context_create(array(
		'http' => array(
			'user_agent' => "updater for replicate-sequence Tool (https://github.com/MaZderMind/replicate-sequences) on ".php_uname('n').", contact ".$conf['abuse']." in case of abuse or problems",
		),
	));

	$statefile = $conf['base'].'state.txt';
	printf("fetching current remote statefile %s\n", $statefile);

	$stateText = file_get_contents($statefile, false, $ctx);
	if(!is_string($stateText))
	{
		printf("error fetching remote statefile\n");
		exit(2);
	}
	if(!preg_match('/sequenceNumber=([0-9]+)/', $stateText, $match))
	{
		printf("format error in remote statefile\n");
		exit(3);
	}
	$remoteSeq = intval($match[1]);

	$localSeq = $db->query("SELECT MAX(sequenceNumber) FROM $conf[table]")->fetchColumn();
	if(is_null($localSeq))
	{
		$localSeq = $conf['firstSeq'];
		printf("no sequenceNumber in table, starting with %u\n", $conf['firstSeq']);
	}

	printf("current local sequenceNumber is %u\n", $localSeq);
	printf("current remote sequenceNumber is %u\n", $remoteSeq);
	if($remoteSeq == $localSeq)
	{
		printf("database is current, nothing to do\n");
		return;
	}

	$seqRange = $remoteSeq - $localSeq;
	printf("fetching %u statefiles\n", $seqRange);

	for($seq = $localSeq; $seq <= $remoteSeq; $seq++)
	{
		$seqPath = str_pad($seq, 9, '0', STR_PAD_LEFT);
		$seqPath = trim(chunk_split($seqPath, 3, '/'), '/');
		
		$statefile = $conf['base'].$seqPath.'.state.txt';
		$stateText = file_get_contents($statefile);
		if(!is_string($stateText))
		{
			printf("error fetching remote statefile %s\n", $statefile);
			exit(2);
		}
		if(!preg_match('/timestamp=([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2})\\\\:([0-9]{2})\\\\:([0-9]{2})Z/', $stateText, $match))
		{
			printf("format error in remote statefile %s\n", $statefile);
			exit(3);
		}
		
		$original = array_shift($match);
		$timestamp = vsprintf('%04u-%02u-%02u %02u:%02u:%02u', $match);
		
		if($seq % 100 == 0 || $seq == $localSeq || $seq == $remoteSeq-1)
			printf("(%.2f%%) sequenceNumber=%s timestamp=%s\n", ($seq - $localSeq) / $seqRange * 100, $seq, $timestamp);
		
		$stm->execute(array(
			'sequenceNumber' => $seq, 
			'timestamp' => $timestamp, 
		));
	}
}

?>
