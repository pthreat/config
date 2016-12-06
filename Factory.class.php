<?php

	namespace pthreat\config{

		class Factory{

			private	$config			=	NULL;
			private	$type				=	NULL;
			private	$detectOrder	=	Array('json','ini','php','yaml');
			private	$options			=	Array();

			public function __construct($config=NULL,$type=NULL){

				if(!is_null($config)){

					$this->setConfig($config);

				}

				if(!is_null($type)){

					$this->setType($type);

				}

			}

			public function setConfig($config){

				$config			=	is_string($config) ? trim($config) : $config;
				$this->config	=	$config;
				return $this;

			}

			public function getConfig(){

				return $this->config;

			}

			public function setType($type){

				$this->type	=	$type;
				return $this;

			}

			public function getType(){

				return $this->type;

			}

			private function getAdapterClassNameFromType($type){

				return sprintf('%s\\adapter\\%s',__NAMESPACE__,ucwords($type));

			}

			private function getFileType($file){

				if(!is_readable($file)){

					throw new \InvalidArgumentException("File $config is not readable");

				}

				$info	=	new \SplFileInfo($this->config);

				return $info->getExtension();

			}

			public function getStringType($config){

			}

			public function build(Array $options=Array()){

				if(!is_string($this->config)){

					$class	=	$this->getAdapterClassNameFromType(
																					$this->type === NULL	?	'php'	:	$this->type
					);

					return  new $class($this->config,$options);

				}

				$config	=	trim($this->config);
				$isFile	=	is_file($config);

				if(!$this->type){

					$this->setType(
										$isFile	?	$this->getFileType($config)	:
														$this->getStringType($config)
					);

				}

				$class	=	$this->getAdapterClassNameFromType($this->type);

				if(!class_exists($class)){

					$msg	=	"Configuration adapter of type \"{$this->type}\" does not exists";
					throw new \InvalidArgumentException($msg);

				}

				return new $class($this->config,$this->options);

			}

		}

	}
