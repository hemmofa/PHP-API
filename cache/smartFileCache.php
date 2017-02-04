<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2016, 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Cache.php');

/**
 * This is a Cache implementation that stores the responses on disk in a directory structure.
 */
class SmartFileCache extends Cache
{
	private $tmpDir;

	public function __construct($retriever, $tmpDir)
	{
		parent::__construct($retriever);
		$this->tmpDir = $tmpDir;
	}

	public function getStations()
	{
		$tmpFile = $this->initTmpDir("stations")."stations.xml";
		if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCacheStations() > time())
		{
			return file_get_contents($tmpFile);
		}
		else
		{
			$xml = $this->getRetriever()->getStations();
			file_put_contents($tmpFile, $xml);
			return $xml;
		}
	}

	public function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null)
	{
		$tmpFile = $this->initTmpDir("prijzen", $fromStation, $toStation, $viaStation, $dateTime)."prijzen.xml";
		if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCacheRates() > time())
		{
			return file_get_contents($tmpFile);
		}
		else
		{
			$xml = $this->getRetriever()->getRates($fromStation, $toStation, $viaStation, $dateTime);
			file_put_contents($tmpFile, $xml);
			return $xml;
		}
	}

	public function getDepartureBoard($station)
	{
		$tmpFile = $this->initTmpDir("avt", $station)."vertrektijden.xml";
		if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCacheDepartureBoard() > time())
		{
			return file_get_contents($tmpFile);
		}
		else
		{
			$xml = $this->getRetriever()->getDepartureBoard($station);
			file_put_contents($tmpFile, $xml);
			return $xml;
		}
	}

	public function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null)
	{
		$tmpFile = $this->initTmpDir("treinplanner", $fromStation, $toStation, $viaStation, $previousAdvices, $nextAdvices, $dateTime, $departure, $hslAllowed, $yearCard)."treinplanner.xml";
		if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCacheJourneyPlanner() > time())
		{
			return file_get_contents($tmpFile);
		}
		else
		{
			$xml = $this->getRetriever()->getTrainScheduler($fromStation, $toStation, $viaStation, $previousAdvices, $nextAdvices, $dateTime, $departure, $hslAllowed, $yearCard);
			file_put_contents($tmpFile, $xml);
			return $xml;
		}
	}

	public function getDisruptions($station, $actual = null, $unplanned = null, $language = 'en')
	{
		$tmpFile = $this->initTmpDir("storingen", $station, $actual, $unplanned, $language)."storingen.xml";
		if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCacheDisruptions() > time())
		{
			return file_get_contents($tmpFile);
		}
		else
		{
			$xml = $this->getRetriever()->getDisruptions($station, $actual, $unplanned, $language);
			file_put_contents($tmpFile, $xml);
			return $xml;
		}
	}

	private function initTmpDir($functionName)
	{
		$arguments = func_get_args();
		$strTmpDir = $this->tmpDir . "/";
		$strTmpRootDir = $this->tmpDir . "/";
		foreach ($arguments as $arg)
		{
			if ($arg === null)
			{
				$strTmpDir .= "NULL-";
			}
			elseif ($arg instanceof Station)
			{
				$strTmpDir .= $arg->getCode() . "-";
			}
			elseif (is_bool($arg))
			{
				$strTmpDir .= ($arg ? "TRUE" : "FALSE") . "-";
			}
			elseif (is_int($arg))
			{
				$strTmpDir .= $arg . "-";
			}
			elseif (is_string($arg))
			{
				$strTmpDir .= $arg . "-";
			}
			else
			{
				trigger_error("SmartFileCache::initTmpDir got an object of unknown type", E_USER_WARNING);
				$strTmpDir .= $arg . "-";
			}
		}
		if (!file_exists($strTmpDir))
		{
			//mkdir($strTmpDir, 0700, TRUE);
		}
		$strTmpDir = $strTmpRootDir . md5($strTmpDir) . "-";
		//die($strTmpDir);
		return $strTmpDir;
	}
}
?>