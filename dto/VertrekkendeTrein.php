<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
class DepartingTrain
{
	private $shiftNumber;
	private $departureTime;
	private $departureDelay;
	private $departureDelayText;
	private $finalDestination;
	private $trainType;
	private $departureTrack;
	private $departureTrackChanged;
	private $remarks;
	private $routeText;
	private $journeyHint;
	private $carrier;

	public function __construct($shiftNumber, $departureTime, $departureDelay, $departureDelayText, $finalDestination, $trainType, $departureTrack, $departureTrackChanged, $remarks, $routeText, $journeyHint, $carrier)
	{
		$this->ritNummer = $shiftNumber;
		$this->vertrekTijd = $departureTime;
		$this->vertrekVertraging = $departureDelay;
		$this->vertrekVertragingTekst = $departureDelayText;
		$this->eindBestemming = $finalDestination;
		$this->treinSoort = $trainType;
		$this->vertrekSpoor = $departureTrack;
		$this->vertrekSpoorGewijzigd = $departureTrackChanged;
		$this->opmerkingen = $remarks;
		$this->routeTekst = $routeText;
		$this->reisTip = $journeyHint;
		$this->vervoerder = $carrier;
	}

	public function getShiftNumber()
	{
		return $this->ritNummer;
	}

	public function getDepartureTime()
	{
		return $this->vertrekTijd;
	}

	public function getDepartureDelay()
	{
		return $this->vertrekVertraging;
	}

	public function getDepartureDelayText()
	{
		return $this->vertrekVertragingTekst;
	}
	 
	public function getFinalDestination()
	{
		return $this->eindBestemming;
	}
	 
	public function getTrainType()
	{
		return $this->treinSoort;
	}
	 
	public function getDepartureTrack()
	{
		return $this->vertrekSpoor;
	}

	public function hasDepartureTrackChanged()
	{
		return $this->vertrekSpoorGewijzigd;
	}

	public function getRemarks()
	{
		return $this->opmerkingen;
	}
	public function getRouteText()
	{
		return $this->routeTekst;
	}
	public function getJourneyHint()
	{
		return $this->reisTip;
	}
	public function getCarrier()
	{
		return $this->vervoerder;
	}
}
?>