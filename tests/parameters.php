<?php

	require "Factory.class.php";
	require "Adapter.class.php";
	require "adapter/Ini.class.php";
	require "adapter/Json.class.php";
	require "adapter/Php.class.php";
	require "adapter/Yaml.class.php";
	require "Parameters.class.php";


	$p = new \pthreat\Parameters(Array('a'));
	$p->required('b','c');

