<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/DRException.php');

require_once(dirname(__FILE__).'/dto/Station.php');
require_once(dirname(__FILE__).'/dto/Product.php');
require_once(dirname(__FILE__).'/dto/VertrekkendeTrein.php');
require_once(dirname(__FILE__).'/dto/ReisMogelijkheid.php');
require_once(dirname(__FILE__).'/dto/GeplandeStoring.php');
require_once(dirname(__FILE__).'/dto/OngeplandeStoring.php');

require_once(dirname(__FILE__).'/retriever/Retriever.php');
require_once(dirname(__FILE__).'/retriever/cURLRetriever.php');

class dataretriever
{
	private $cache;

	public function __construct($cache)
	{
		$this->cache = $cache;
	}
	
	public function getCache()
	{
		return $this->cache;
	}

	/**
	 * This function returns the stations that are within $maxDiff kilometers from the given latitude/longitude position.
	 */
	public function getStationsByCoordinates($latitude, $longitude, $maxDiff)
	{
		$stations = $this->getStations();
		$result = array();
		foreach ($stations as $station)
		{
			$diff = Utils::getDistanceBetweenPoints($latitude, $longitude, $station->getLatitude(), $station->getLongitude());
			if ($diff < $maxDiff)
			{
				$result[] = $station;
			}
		}
	return $result;
	}

	public function getStationByCode($code)
	{
		$stations = $this->getStations();
		foreach ($stations as $station)
		{
			if ($station->getCode() === $code)
			{
				return $station;
			}
		}
	}

	public function getStations()
	{
		$xml = $this->cache->getStations();
		$xml = new SimpleXMLElement($xml);

		$result = array();
		foreach ($xml->Station as $xmlStation)
		{
			$name = (string)$xmlStation->Namen->Lang;
			$names = $xmlStation->Namen;
			$names = ["long" => (string)$names->Lang, "middle" => (string)$names->Middel, "short" => (string)$names->Kort];
			foreach ($xmlStation->Synoniemen as $synonym) {
				$names[] = (string)$synonym->Synoniem;
			}
			$code = (string)$xmlStation->Code;
			$country = (string)$xmlStation->Land;
			$lat = (string)$xmlStation->Lat;
			$long = (string)$xmlStation->Lon;
			$type = (string)$xmlStation->Type;
			$UICCode = (string)$xmlStation->UICCode;
			$station = new Station($name, $code, $country, $lat, $long, $type, $names, $UICCode);
			$result[] = $station;
		}
		return $result;
	}

	public function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null)
	{
		$xml = $this->cache->getRates($fromStation, $toStation, $viaStation, $dateTime);
		$xml = new SimpleXMLElement($xml);

		$result = array();
		foreach ($xml->Product as $xmlProduct)
		{
			$name = (string)$xmlProduct['naam'];

			$rates = array();
			foreach ($xmlProduct->Prijs as $xmlRate)
			{
				$discount = (string)$xmlRate['korting'];
				$class = (string)$xmlRate['klasse'];
				$rate = (string)$xmlRate;
				$rate = new Prijs($discount, $class, $rate);
				$rates[] = $rate;
			}
			$product = new Product($name, $rates);
			$result[] = $product;
		}
		return $result;
	}

	public function getDepartureBoard($station)
	{
		$xml = $this->cache->getDepartureBoard($station);
		$xml = new SimpleXMLElement($xml);

		$result = array();
		foreach ($xml->VertrekkendeTrein as $xmlDepartingTrain)
		{
			$shiftnumber = (string)$xmlDepartingTrain->RitNummer;
			$departureTime = Utils::ISO8601Date2UnixTimestamp($xmlDepartingTrain->VertrekTijd);
			$departureDelay = NULL;
			$departureDelayText = NULL;
			if ($xmlDepartingTrain->VertrekVertraging !== NULL && (string)$xmlDepartingTrain->VertrekVertraging !== "")
			{
				$departureDelay = Utils::ISO8601Period2UnixTimestamp($xmlDepartingTrain->VertrekVertraging, $departureTime);
				$departureDelayText = (string)$xmlDepartingTrain->VertrekVertragingTekst;
			}
			$finalDestination = (string)$xmlDepartingTrain->EindBestemming;
			$trainType = (string)$xmlDepartingTrain->TreinSoort;
			$departureTrack = (string)$xmlDepartingTrain->VertrekSpoor;
			$departureTrackChanged = Utils::string2Boolean($xmlDepartingTrain->VertrekSpoor['wijziging']);

			$remarks = array();
			if ($xmlDepartingTrain->Opmerkingen->Opmerking !== NULL)
			{
				foreach ($xmlDepartingTrain->Opmerkingen->Opmerking as $xmlRemark)
				{
					$remarks[] = trim((string)$xmlRemark);
				}
			}
			$routeText = (string)$xmlDepartingTrain->RouteTekst;
			$journeyHint = (string)$xmlDepartingTrain->ReisTip;
			$carrier = (string)$xmlDepartingTrain->Vervoerder;
			$departingTrain = new DepartingTrain($shiftnumber, $departureTime, $departureDelay, $departureDelayText, $finalDestination, $trainType, $departureTrack, $departureTrackChanged, $remarks, $routeText, $journeyHint, $carrier);
			$result[] = $departingTrain;
		}
		return $result;
	}

	public function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null)
	{
		$xml = $this->cache->getTrainScheduler($fromStation, $toStation, $viaStation, $previousAdvices, $nextAdvices, $dateTime, $departure, $hslAllowed, $yearCard);
		//die($fromStation ." ". $toStation ." ". $viaStation ." ". $previousAdvices ." ". $nextAdvices ." ". $dateTime ." ". $departure ." ". $hslAllowed ." ". $yearCard);
		//die(substr(Utils::UnixTimestamp2ISO8601Date($dateTime),0,-6));
		$xml = new SimpleXMLElement($xml);

		$result = array();
		foreach ($xml->ReisMogelijkheid as $xmlTravelOption)
		{
			$numberOfChanges = (string)$xmlTravelOption->AantalOverstappen;
			$scheduledTravelTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->GeplandeReisTijd);
			$actualTravelTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->ActueleReisTijd);
			$optimal = Utils::string2Boolean($xmlTravelOption->Optimaal);
			$plannedDepartureTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->GeplandeVertrekTijd);
			$actualDepartureTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->ActueleVertrekTijd);
			$plannedArrivalTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->GeplandeAankomstTijd);
			$actualArrivalTime = Utils::ISO8601Date2UnixTimestamp($xmlTravelOption->ActueleAankomstTijd);

			$alert = NULL;
			if ($xmlTravelOption->Melding->Id !== NULL)
			{
				$xmlAlert = $xmlTravelOption->Melding;
				$id = (string)$xmlAlert->Id;
				$serious = Utils::string2Boolean($xmlAlert->Ernstig);
				$text = (string)$xmlAlert->Text;
				$alert = new Alert($id, $serious, $text);
			}

			$journeyParts = array();
			foreach ($xmlTravelOption->ReisDeel as $xmlJourneyPart)
			{
				$journeyType = (string)$xmlJourneyPart['reisSoort'];
				$transportationType = (string)$xmlJourneyPart->VervoerType;
				$shiftNumber = (string)$xmlJourneyPart->RitNummer;
				$carrier = (string)$xmlJourneyPart->Vervoerder;
				$status = (string)$xmlJourneyPart->Status;
				$journeyDetails = "Zie dataretriever.php Line 204";
				
				$journeyStops = array();
				foreach ($xmlJourneyPart->ReisStop as $xmlJourneyPart)
				{
					$name = (string)$xmlJourneyPart->Naam;
					$time = Utils::ISO8601Date2UnixTimestamp($xmlJourneyPart->Tijd);
					$track = (string)$xmlJourneyPart->Spoor;
					$trackChange = Utils::string2Boolean($xmlJourneyPart->Spoor['wijziging']);
					$departureDelay = (string)$xmlJourneyPart->VertrekVertraging;
					$journeyStop = new JourneyStop($name, $time, $track, $trackChange, $departureDelay);
					$journeyStops[] = $journeyStop;
				}
				$journeyPart = new JourneyPart($journeyType, $transportationType, $shiftNumber, $journeyStops,$carrier,$status,$journeyDetails);
				$journeyParts[] = $journeyPart;
			}
			$journeyOption = new JourneyOption($numberOfChanges, $scheduledTravelTime, $actualTravelTime, $optimal, $plannedDepartureTime, $actualDepartureTime, $plannedArrivalTime, $actualArrivalTime, $alert, $journeyParts);
			$result[] = $journeyOption;
		}
		return $result;
	}

	public function getDisruptions($station, $actual = null, $unplanned = null, $language = 'nl')
	{
		$result = array();
		$xml = $this->cache->getDisruptions($station, $actual, $unplanned, $language);
		$xml = new SimpleXMLElement($xml);

		foreach ($xml->Ongepland->Storing as $xmlUnplannedDisruption)
		{
			$id = (string)$xmlUnplannedDisruption->id;
			$line = (string)$xmlUnplannedDisruption->Traject;
			$cause = (string)$xmlUnplannedDisruption->Reden;
			$message = (string)$xmlUnplannedDisruption->Bericht;
			$date = Utils::ISO8601Date2UnixTimestamp($xmlUnplannedDisruption->Datum);
			$unplannedDisruption = new UnplannedDisruption($id, $line, $message, $cause, $date);
			$result[] = $unplannedDisruption;
		}

		foreach ($xml->Gepland->Storing as $xmlPlannedDisruption)
		{
			$id = (string)$xmlPlannedDisruption->id;
			$line = (string)$xmlPlannedDisruption->Traject;
			$when = (string)$xmlPlannedDisruption->Periode;
			$cause = (string)$xmlPlannedDisruption->Reden;
			$advice = (string)$xmlPlannedDisruption->Advies;
			$message = (string)$xmlPlannedDisruption->Bericht;
			$plannedDisruption = new PlannedDisruption($id, $line, $message, $cause, $when, $advice);
			$result[] = $plannedDisruption;
		}
		return $result;
	}
}
?>
