<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
abstract class Disruption
{
	private $id;
	private $line;
	private $message;
	private $cause;

	public function __construct($id, $line, $message, $cause)
	{
		$this->id = $id;
		$this->traject= $line;
		$this->bericht = $message;
		$this->reden = $cause;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getLine()
	{
		return $this->traject;
	}

	public function getMessage()
	{
		return $this->bericht;
	}

	public function getReason()
	{
		return $this->reden;
	}
}
?>