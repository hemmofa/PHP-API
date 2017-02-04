<?php
// Example.php

// Initialize API
require_once('../dataretriever.php'); 
require_once('../config.php'); 
$retriever = new cURLRetriever($config['api-username'], $config['api-password'], '');

// Set it so it uses no cache.
require_once('../cache/NoCache.php');
$cache = new NoCache($retriever);
// Initialize it
$dr = new dataretriever($cache);

// Retreive all train stations:
$stations = $dr->getStations();

// Set the station we will be looking departure times up for:
//$fromstation="WFM"; // Warffum, Netherlands
$fromstation="BE.NMBS.008821006"; // Antwerp Central, Belgium

// Apply a "little bit" of styling
?>
<style>
table { background-color: #000080; color: #fff; font-size: 1.5em; font-weight: bold;}
</style>
<?php
foreach($stations as $station) {
	if($station->getCode() == $fromstation) {
?>
Station: <?php echo $station->getName(); ?> 
<table border=1 width=750> 
<?php
$departures = $dr->getDepartureBoard($station);

foreach($departures as $departure) {
?>
	<tr>
		<td><?php echo date("H:i", $departure->getDepartureTime()); if($departure->getDepartureDelayText() != "") { echo " " . $departure->getDepartureDelayText(); ?><?php } ?></td>
		<td><?php echo $departure->getDepartureTrack(); ?></td>
		<td><?php echo $departure->getFinalDestination(); ?></td>
		<td><?php echo $departure->getTrainType(); ?></td>
	</tr>
<?php } ?>
</table>
<?php
break;
}}
//echo $stations[0]->getName();
?>
