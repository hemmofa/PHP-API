<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Storing.php');

class PlannedDisruption extends Disruption
{
	private $when;
	private $advice;

	public function __construct($id, $line, $message, $cause, $when, $advice)
	{
		parent::__construct($id, $line, $message, $cause);
		$this->periode = $when;
		$this->advies = $advice;
	}

	public function getWhen()
	{
		return $this->periode;
	}
	
	public function getAdvice()
	{
		return $this->advies;
	}
}
?>