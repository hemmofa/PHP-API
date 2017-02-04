<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Melding.php');
require_once(dirname(__FILE__).'/ReisDeel.php');

class JourneyOption
{
	private $numberOfChanges;
	private $scheduledTravelTime;
	private $actualTravelTime;
	private $optimal;
	private $plannedDepartureTime;
	private $actualDepartureTime;
	private $plannedArrivalTime;
	private $actualArrivalTime;
	private $alert;
	private $journeyParts;

	public function __construct($numberOfChanges, $scheduledTravelTime, $actualTravelTime, $optimal, $plannedDepartureTime, $actualDepartureTime, $plannedArrivalTime, $actualArrivalTime, $alert, $journeyParts)
	{
		$this->aantalOverstappen = $numberOfChanges;
		$this->geplandeReisTijd = $scheduledTravelTime;
		$this->actueleReisTijd = $actualTravelTime;
		$this->optimaal = $optimal;
		$this->geplandeVertrekTijd = $plannedDepartureTime;
		$this->actueleVertrekTijd = $actualDepartureTime;
		$this->geplandeAankomstTijd = $plannedArrivalTime;
		$this->actueleAankomstTijd = $actualArrivalTime;
		$this->melding = $alert;
		$this->reisDelen = $journeyParts;
	}

	public function getNumberOfChanges()
	{
		return $this->aantalOverstappen;
	}

	public function getPlannedJourneyTime()
	{
		return $this->geplandeReisTijd;
	}

	public function getActualJourneyTime()
	{
		return $this->actueleReisTijd;
	}

	public function isOptimal()
	{
		return $this->optimaal;
	}

	public function getPlannedDepartureTime()
	{
		return $this->geplandeVertrekTijd;
	}

	public function getActualDepartureTime()
	{
		return $this->actueleVertrekTijd;
	}

	public function getPlannedArrivalTime()
	{
		return $this->geplandeAankomstTijd;
	}

	public function getActualArrivalTime()
	{
		return $this->actueleAankomstTijd;
	}

	public function getAlert()
	{
		return $this->melding;
	}

	public function getJourneyParts()
	{
		return $this->reisDelen;
	}
}
?>