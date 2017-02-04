<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
class JourneyStop
{
	private $name;
	private $time;
	private $track;
	private $trackChange;
	private $departureDelay;

	public function __construct($name, $time, $track, $trackChange, $departureDelay)
	{
		$this->naam = $name;
		$this->tijd = $time;
		$this->spoor = $track;
		$this->spoorWijziging = $trackChange;
		$this->vertrekVertraging = $departureDelay;
	}

	public function getName()
	{
		return $this->naam;
	}

	public function getTime()
	{
		return $this->tijd;
	}

	public function getTrack()
	{
		return $this->spoor;
	}

	public function isTrackChanged()
	{
		return $this->spoorWijziging;
	}
	
	public function getDepartureDelay()
	{
		return $this->vertrekVertraging;
	}

}
?>