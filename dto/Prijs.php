<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
class Rate
{
	private $discount;
	private $class;
	private $rate;

	public function __construct($discount, $class, $rate)
	{
		$this->korting = $discount;
		$this->klasse = $class;
		$this->prijs = $rate;
	}

	public function getDiscount()
	{
		return $this->korting;
	}

	public function getClass()
	{
		return $this->klasse;
	}

	public function getRates()
	{
		return $this->prijs;
	}
}
?>