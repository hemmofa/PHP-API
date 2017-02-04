<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/../retriever/Retriever.php');

/**
 * A cache is an object that keeps track of requests made, and responses retrieved.
 * When the same request is made again within the 'cache treshold', the stored response is returned, and no call to the server is done.
 * This keeps the number of requests to the server to a minimum.
 */
abstract class Cache
{
	private $retriever;

	// Seconds to cache a previous result
	private $timeToCacheStations = 86400; 		// default is 60 * 60 * 24
	private $timeToCachePrijzen = 86400; 		// default is 60 * 60 * 24
	private $timeToCacheActuelevertrektijden = 30; 		// default is 30
	private $timeToCacheTreinplanner = 60; 				// default is 60
	private $timeToCacheStoringen = 120; 			// default is 60 * 2

	public function __construct($retriever)
	{
		$this->retriever = $retriever;
	}

	protected function getRetriever()
	{
		return $this->retriever;
	}

	public function getTimeToCacheStations()
	{
		return $this->timeToCacheStations;
	}

	public function setTimeToCacheStations($timeToCacheStations)
	{
		$this->timeToCacheStations = $timeToCacheStations;
	}
	public function getTimeToCacheRates()
	{
		return $this->timeToCachePrijzen;
	}

	public function setTimeToCacheRates($timeToCachePrijzen)
	{
		$this->timeToCachePrijzen = $timeToCachePrijzen;
	}

	public function getTimeToCacheDepartureBoard()
	{
		return $this->timeToCacheActuelevertrektijden;
	}

	public function setTimeToCacheDepartureBoard($timeToCacheActuelevertrektijden)
	{
		$this->timeToCacheActuelevertrektijden = $timeToCacheActuelevertrektijden;
	}

	public function getTimeToCacheJourneyPlanner()
	{
		return $this->timeToCacheTreinplanner;
	}

	public function setTimeToCacheJourneyPlanner($timeToCacheTreinplanner)
	{
		$this->timeToCacheTreinplanner = $timeToCacheTreinplanner;
	}

	public function getTimeToCacheDisruptions()
	{
		return $this->timeToCacheStoringen;
	}

	public function setTimeToCacheDisruptions($timeToCacheStoringen)
	{
		$this->timeToCacheStoringen = $timeToCacheStoringen;
	}

	public abstract function getStations();
	public abstract function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null);
	public abstract function getDepartureBoard($station);
	public abstract function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null);
	public abstract function getDisruptions($station, $actual = null, $unplanned = null, $language = 'nl');
}
?>