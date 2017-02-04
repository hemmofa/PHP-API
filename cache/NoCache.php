<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
/**
 * This is a Cache implementation that actually does NOT do caching at all.
 * Please, consider a proper caching stragey instead of using this implementation.
 */
require_once(dirname(__FILE__).'/Cache.php');

class NoCache extends Cache
{
	public function __construct($retriever)
	{
		parent::__construct($retriever);
	}

	public function getStations()
	{
		return $this->getRetriever()->getStations();
	}

	public function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null)
	{
		return $this->getRetriever()->getRates($fromStation, $toStation, $viaStation, $dateTime);
	}

	public function getDepartureBoard($station)
	{
		return $this->getRetriever()->getDepartureBoard($station);
	}

	public function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null)
	{
		return $this->getRetriever()->getTrainScheduler($fromStation, $toStation, $viaStation, $previousAdvices, $nextAdvices, $dateTime, $departure, $hslAllowed, $yearCard);
	}

	public function getDisruptions($station, $actual = null, $unplanned = null, $language = 'nl')
	{
		return $this->getRetriever()->getDisruptions($station, $actual, $unplanned, $language);
	}
}
?>