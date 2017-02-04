<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Prijs.php');

class Product
{
	private $name;
	private $rates;

	public function __construct($name, $rates)
	{
		$this->naam = $name;
		$this->prijzen = $rates;
	}

	public function getName()
	{
		return $this->naam;
	}

	public function getRates()
	{
		return $this->prijzen;
	}
}
?>