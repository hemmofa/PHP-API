<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
class Alert
{
	private $id;
	private $serious;
	private $text;

	public function __construct($id, $serious, $text)
	{
		$this->id = $id;
		$this->ernstig = $serious;
		$this->text = $text;
	}

	public function getId()
	{
		return $this->id;
	}

	public function isSerious()
	{
		return $this->ernstig;
	}

	public function getText()
	{
		return $this->text;
	}
}
?>