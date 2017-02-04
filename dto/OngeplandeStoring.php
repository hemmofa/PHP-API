<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Storing.php');

class UnplannedDisruption extends Disruption
{
	private $date;

	public function __construct($id, $line, $message, $cause, $date)
	{
		parent::__construct($id, $line, $message, $cause);
		$this->datum = $date;
	}

	public function getDate()
	{
		return $this->datum;
	}
}
?>