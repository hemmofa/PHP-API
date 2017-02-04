<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/ReisStop.php');

class JourneyPart
{
	private $journeyType;
	private $transportationType;
	private $shiftNumber;
	private $journeyStops;
	private $carrier;
	private $status;
	private $journeyDetails;

	public function __construct($journeyType, $transportationType, $shiftNumber, $journeyStops, $carrier, $status, $journeyDetails)
	{
		$this->reisSoort = $journeyType;
		$this->vervoerType = $transportationType;
		$this->ritNummer = $shiftNumber;
		$this->reisStops = $journeyStops;
		$this->vervoerder = $carrier;
		$this->status = $status;
		$this->reisDetails = $journeyDetails;
	}

	public function getJourneyType()
	{
		return $this->reisSoort;
	}

	public function getTransportationType()
	{
		return $this->vervoerType;
	}

	public function getShiftNumber()
	{
		return $this->ritNummer;
	}

	public function getJourneyStops()
	{
		return $this->reisStops;
	}

	public function getCarrier()
	{
		return $this->vervoerder;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getJourneyDetails()
	{
		return $this->reisDetails;
	}
}
?>