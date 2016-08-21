<?php
# create html header
$html = "<html><head><meta charset='UTF-8'>";
# table style
$html .= "<style>td{border-bottom:1px solid black;border-top:1px solid black;}table{border-collapse:collapse;}</style>";
# start html body
$html .= "</head><body><table><tr><th>Station</th><th>Address</th><th>Available bikes</th><th>Free bases</th><th>Total</th></tr>";
# read xml file that contains basic information about stations
$xml_root = simplexml_load_file("http://www.bicikelj.si/service/carto");

for($m=0; $m<36; $m++)
{
	$stationName = $xml_root->markers->marker[$m]['name'];
	$number = $xml_root->markers->marker[$m]['number'];
	$address = $xml_root->markers->marker[$m]['address'];

	# read xml file that contains detailed information about stations
	$xml_station = simplexml_load_file("http://www.bicikelj.si/service/stationdetails/ljubljana/".$number);
	$availableBikes = $xml_station->available;
	$freeLocker = $xml_station->free;
	$totalLockers = $xml_station->total;

	if(intval($availableBikes) == 0)
	{
		# mark empty stations in red
		$html .= "<tr bgcolor='red'><td>".$stationName."</td>";
	}
	else
	{
		if(intval($availableBikes) == intval($totalLockers))
		{
			# mark full stations in green
			$html .= "<tr bgcolor='green'><td>".$stationName."</td>";
		}
		else
		{
			$html .= "<tr><td>".$stationName."</td>";
		}
	}
	$html .= "<td>".$address."</td>";
	$html .= "<td>".$availableBikes."</td>";
	$html .= "<td>".$freeLocker."</td>";
	$html .= "<td>".$totalLockers."</td></tr>";
}
$html .= "</table>";
$timeUpdatedSec = $xml_station->updated;
$html .= "<br />Updated: ".date('H:i:s d-M-Y', (int)$timeUpdatedSec)." UTC time";
$html .= "</body></html>";
#show webpage
echo($html);
?>
