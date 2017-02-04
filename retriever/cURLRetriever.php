<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/Retriever.php');
require_once(dirname(__FILE__).'/cURLRetrieverException.php');

/**
 * A simple Retriever implementation that uses cURL to retrieve the data.
 */
class cURLRetriever extends Retriever
{
	const SOAP_FAULT = "<soap:Fault>";
	const SOAP_FAULTSTRING_START = "<faultstring>";
	const SOAP_FAULTSTRING_END = "</faultstring>";

	const XML_ERROR_INVALID_WEBSERVICE = 2; // 002:The requested webservice is not found
	const XML_ERROR_INVALID_KEY = 6; // 006:No customer found for the specified username and password
	const XML_ERROR_UNEXPECTED = 99; // 099:An unexpected exception occured
	const XML_ERROR_LIMIT_REACHED = 13; // 013:The limit for calling this webservice has been reached
	private $requests_log;

	public function __construct($username, $password, $requests_log_file)
	{
		parent::__construct($username, $password);
		$this->requests_log = $requests_log_file;
	}

	public function getStations()
	{
		global $config;
		return $this->getXML($config['api-url-stations']);
	}

	public function getRates($fromStation, $toStation, $viaStation = null, $dateTime = null)
	{
		global $config;
		return $this->getXML($config['api-url-rates']."?from=".$fromStation->getCode()."&to=".$toStation->getCode().($viaStation !== NULL ? "&via=".$viaStation->getCode() : "").($dateTime !== NULL ? "&dateTime=".Utils::UnixTimestamp2ISO8601Date($dateTime) : ""));
	}

	public function getDepartureBoard($station)
	{
		global $config;
		return $this->getXML($config['api-url-departures']."?station=".$station->getCode());
	}

	public function getTrainScheduler($fromStation, $toStation, $viaStation = null, $previousAdvices = null, $nextAdvices = null, $dateTime = null, $departure = null, $hslAllowed = null, $yearCard = null)
	{
		global $config;
		return $this->getXML($config['api-url-journeyplanner']."?fromStation=".$fromStation."&toStation=".$toStation.($viaStation !== NULL ? "&viaStation=".$viaStation : "").($previousAdvices !== NULL ? "&previousAdvices=".$previousAdvices : "").($nextAdvices !== NULL ? "&nextAdvices=".$nextAdvices : "").($dateTime !== NULL ? "&dateTime=".substr(Utils::UnixTimestamp2ISO8601Date($dateTime),0,-6) : "").($departure !== NULL ? "&departure=".Utils::boolean2String($departure) : "").($hslAllowed !== NULL ? "&hslAllowed=".Utils::boolean2String($hslAllowed) : "").($yearCard !== NULL ? "&yearCard=".Utils::boolean2String($yearCard) : ""));
	}

	public function getDisruptions($station = null, $actual = null, $unplanned = null, $language = 'nl')
	{
		global $config;		
		return $this->getXML($config['api-url-disruptions'].($station !== NULL ? "?station=".$station->getCode() : "?=").($actual !== NULL ? "&actual=".Utils::boolean2String($actual) : "").($unplanned !== NULL ? "&unplanned=".Utils::boolean2String($unplanned) : "") . "&language=" . $language);
	}

	private function getXML($url)
	{
		if (!empty($this->requests_log)) {
			$fid = fopen($this->requests_log, 'a');
			flock($fid, LOCK_EX); // Do not check the return value, 'cause, whatcha gonna do if it failed? Just neglect to write? Let's write and pray.
			fwrite($fid, date('r') . "\t$_SERVER[REMOTE_ADDR]\t$url\r\n");
			flock($fid, LOCK_UN);
			fclose($fid);
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername() . ":" . parent::getPassword());
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		$xml = curl_exec($ch);
		
		if (curl_errno($ch) != 0)
		{
			die("<br/><br/>Er is een onverwachte fout opgetreden - onze excuses hiervoor. Klik op <a href=\"./index.php\">Home</a> om verder te gaan...");
			throw new cURLRetrieverException(cURLRetrieverException::TYPE_CURL, $url, curl_error($ch), curl_errno($ch));
		}
		
		curl_close($ch);

		if (strpos($xml, self::SOAP_FAULT) > -1)
		{
			// This is an error response
			$faultstringStartPosition = strpos($xml, self::SOAP_FAULTSTRING_START);
			$faultstringEndPosition = strpos($xml, self::SOAP_FAULTSTRING_END);
			if ($faultstringStartPosition > -1 && $faultstringEndPosition > $faultstringStartPosition)
			{
				$faultstring = substr($xml, $faultstringStartPosition + strlen(self::SOAP_FAULTSTRING_START), $faultstringEndPosition- $faultstringStartPosition - strlen(self::SOAP_FAULTSTRING_START));
				if (preg_match("/^([0-9]+):(.+)$/", $faultstring, $matches) > 0)
				{
					throw new cURLRetrieverException(cURLRetrieverException::TYPE_XML, $url, $matches[2], $matches[1]);
				}
				else
				{
					throw new cURLRetrieverException(cURLRetrieverException::TYPE_XML, $url, $faultstring);
				}
			}
			else
			{
				throw new cURLRetrieverException(cURLRetrieverException::TYPE_XML, $url);
			}
		}

		return $xml;
	}
}
?>
