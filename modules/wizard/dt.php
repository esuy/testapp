#!/usr/bin/php
<?php

$fmt = datefmt_create( "en_EN" ,IntlDateFormatter::FULL,IntlDateFormatter::FULL,
	'America/Los_Angeles',
	IntlDateFormatter::GREGORIAN ,"d.M a" );
echo "First Formatted output is ".datefmt_format( $fmt , 0)."\n";
$fmt = datefmt_create( "de-DE" ,IntlDateFormatter::FULL,IntlDateFormatter::FULL,
	'America/Los_Angeles',
	IntlDateFormatter::GREGORIAN  );
echo "Second Formatted output is ".datefmt_format( $fmt , 0);