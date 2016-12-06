<?php

	namespace pthreat\config\adapter{

		use pthreat\config\Adapter;

		class Php extends Adapter{

			protected function __parse($contents,Array $options=Array()){

				$config	=	$this->getConfig();

				if($config instanceof \stdClass){

					$config	=	(Array)$config;

				}

				if(!is_array($config)){

					$error	=	'Given argument is not a PHP Array';
					$this->setLastError($error);

					return FALSE;

				}

				return $config;

			}

			protected function __dump(Array $contents){

				return var_export($contents,TRUE);

			}

		}

	}
