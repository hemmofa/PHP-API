<?php
/*
 * PHP Data Retriever for Public Transport, based on phpNS
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * Copyright 2017 Hemmo de Vries <github@hemmodevries.nl>
 */
require_once(dirname(__FILE__).'/../DRException.php');

class cURLRetrieverException extends DRException
{
	/**
	 * When an cURLRetrieverException is of this type, something went wrong with cURL.
	 * getCode() will return cURL's error code.
	 * getMessage() will return cURL's error message.
	 */
	const TYPE_CURL = "curl";
	
	/**
	 * When an cURLRetrieverException is of this type, the returned XML was a soap fault.
	 * Usually, the soap fault messages from NS follow pattern <ERRNR>:<ERRMSG>.
	 * getCode() will return the <ERRNR> part (always a in digits), or NULL if the soap fault was not in the above pattern.
	 * getMessage() will return the <ERRMSG> part, or the complete soap fault if the soap fault was not in the above pattern.
	 */
	const TYPE_XML = "xml";

	/**
	 * Should either be TYPE_CURL or TYPE_XML.
	 */
	private $type;
	
	private $url;
	private $faultstring;

	public function __construct($type, $url, $faultstring = null, $faultcode = null)
	{
		$this->type = $type;
		$this->url = $url;
		$this->message = $faultstring;
		$this->code = $faultcode;
	}
	
	/**
	 * Returns the type of error. It's either TYPE_CURL or TYPE_XML. See there an explanation. 
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * Returns the URL that was requested.
	 */
	public function getUrl()
	{
		return $this->url;
	}
}
?>