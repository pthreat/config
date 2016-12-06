<?php

	require "Factory.class.php";
	require "Adapter.class.php";
	require "adapter/Ini.class.php";
	require "adapter/Json.class.php";
	require "adapter/Php.class.php";
	require "adapter/Yaml.class.php";

	$c = new pthreat\config\Factory('test.yaml');

	$config	=	$c->build()->parse();

	var_dump($config->to('ini')->dump());
