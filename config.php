<?php

// Account details NS API
$config['api-username'] = "";
$config['api-password'] = "";
$config['api-language'] = 'nl';

// The location to log API requests to
// Using a separate logs/ directory and including the date is optional, you can also just use a static filename like 'requests.log'.
$api_requests_log = 'logs/api_requests_' . date('Y-m') . '.log';

// Netherlands
$config['api-url-stations'] = "http://webservices.ns.nl/ns-api-stations-v2"; // URL to API with Station data
$config['api-url-rates'] = "http://webservices.ns.nl/ns-api-prijzen-v2"; // URL to API with Travel Rates / Prices
$config['api-url-departures'] = "http://webservices.ns.nl/ns-api-avt"; // URL to API with Departure Board
$config['api-url-journeyplanner'] = "http://webservices.ns.nl/ns-api-treinplanner"; // URL to API with Journeyplanner
$config['api-url-disruptions'] = "http://webservices.ns.nl/ns-api-storingen"; // URL to API with Disruptions
