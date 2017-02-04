<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 * Copyright 2017 Luc Gommans <nsapidto@lgms.nl>
 *
 * This file is part of phpNS.
 *
 * phpNS is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * phpNS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * phpNS. If not, see <http://www.gnu.org/licenses/>.
 */
class Station implements JsonSerializable
{
	private $name;
	private $code;
	private $country;
	private $latitude;
	private $longitude;
	private $type;
	private $UICCode;

	// Contains the keys 'short', 'middle' and 'long' for the short, middle and long names; and
	// possibly other indices if it has other aliases.
	private $names;

	public function __construct($name, $code, $country, $latitude, $longitude, $type, $names, $UICCode)
	{
		$this->name = $name;
		$this->code = $code;
		$this->country = $country;
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->type = $type;
		$this->names = $names;
		$this->UICCode = $UICCode;
	}

	public function getNames()
	{
		return $this->names;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function isAlias()
	{
		// Alias is kept for backwards compatibility; it's always false.
		// See $names for aliases.
		return false;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getUICCode()
	{
		return $this->UICCode;
	}

	public function getImportance() {
		// TODO: validate this
		switch ($this->type) {
			case 'megastation': return 0;
			case 'knooppuntIntercitystation': return 1;
			case 'intercitystation': return 2;
			case 'knooppuntSneltreinstation': return 3;
			case 'knooppuntStoptreinstation': return 4;
			case 'sneltreinstation': return 5;
			case 'stoptreinstation': return 6;
			case 'facultatiefStation': return 7;
		}
		return null;
	}

	public function jsonSerialize() {
        return [
			'name' => $this->getName(),
			'code' => $this->getCode(),
			'country' => $this->getCountry(),
			'type' => $this->getType()
        ];
    }
}
