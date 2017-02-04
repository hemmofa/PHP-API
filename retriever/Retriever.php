<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */

/**
 * A Retriever is the object that does the actual fetching of data.
 * In its basic form, it models a html GET.
 *
 * We have abstracted this, so people can implement their own ways to retrieve information. One example is support for proxies.
 * We only include the cURLRetriever in phpNS, so another example is sites that don't have cURL installed.
 */
abstract class Retriever
{
	private $username;
	private $password;

	// From now, API Url's are now set from config file. In case this ever gets used outside of the RPLN project, uncomment the following lines for NS usage.
	// $config['api-url-stations'] = "http://webservices.ns.nl/ns-api-stations-v2"; // URL to API with Station data
	// $config['api-url-rates'] = "http://webservices.ns.nl/ns-api-prijzen-v2"; // URL to API with Travel Rates / Prices
	// $config['api-url-departures'] = "http://webservices.ns.nl/ns-api-avt"; // URL to API with Departure Board
	// $config['api-url-journeyplanner'] = "http://webservices.ns.nl/ns-api-treinplanner"; // URL to API with Journeyplanner
	// $config['api-url-disruptions'] = "http://webservices.ns.nl/ns-api-storingen"; // URL to API with Disruptions
	
	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	protected function getUsername()
	{
		return $this->username;
	}

	protected function getPassword()
	{
		return $this->password;
	}

	public abstract function getStations();
	public abstract function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null);
	public abstract function getDepartureBoard($station);
	public abstract function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null);
	public abstract function getDisruptions($station = null, $actual = null, $unplanned = null, $language = 'nl');
}
