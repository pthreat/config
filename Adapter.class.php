<?php

	namespace pthreat\config{

		use \pthreat\config\Factory;
		use \pthreat\config\adapter\PHP	as	PHPConfig;

		abstract class Adapter implements \ArrayAccess,\Iterator{

			private	$isFile		=	NULL;
			private	$config		=	NULL;
			private	$contents	=	'';
			private	$lastError	=	NULL;
			private	$options		=	Array();
			private	$allowPHP	=	FALSE;

			public final function __construct($config,Array $options=Array()){

				$this->setConfig($config);
				$this->setOptions($options);

			}

			public function setConfig($config){

				$this->config	=	$config;

				if(is_string($config)){

					$fileName		=	trim($config);
					$file				=	new \SplFileInfo($fileName);

					//Check if the given configuration is not an inline configuration string
					//For instance, inline JSON {a:1,b:2}

					if($file->isFile()){

						if(!$file->isReadable()){

							throw new \InvalidArgumentException('Can not read configuration file '.$this->config);

						}

						$this->isFile	=	TRUE;
						$this->config	=	$fileName;

						if($this->allowPHP){

							ob_start();
							require $fileName;
							$this->contents	=	ob_end_clean();
							return $this;

						}else{

							$this->contents	=	file_get_contents($config);

						}

						return $this;

					}

					$this->contents	=	$config;

				}

				return $this;

			}


			public function keys(){

				return array_keys($this->contents);

			}

			public function values(){

				return array_values($this->contents);

			}

			public function setAllowPHP($bool){

				$this->allowPHP	=	(boolean)$bool;
				return $this;

			}

			public function getAllowPHP(){

				return $this->allowPHP();

			}


			abstract protected function __parse($contents,Array $options=Array());

			public function parse(){

				$contents	=	$this->__parse($this->contents,$this->options);

				if(!is_array($contents)){

					$msg	=	sprintf(
											'Failed to parse configuration %s %s',
											$this->getConfig(),
											$this->getLastError()
					);

					throw new \RuntimeException($msg);

				}

				$this->contents	=	$contents;

				return $this;

			}

			abstract protected function __dump(Array $contents);

			public function dump(){

				$dump	=	$this->__dump($this->config);

				if($dump === FALSE){

					$msg	=	sprintf(
											'Failed to dump configuration. %s',
											$this->getLastError()
					);

					throw new \RuntimeException($msg);

				}

				return $dump;

			}

			public function getConfig(){

				return $this->config;

			}

			public function setOptions(Array $options){

				$this->options	=	$options;
				return $this;

			}

			public function getOptions(){

				return $this->options;

			}

			public function offsetExists($offset){

				return array_key_exists($offset,$this->contents);

			}

			public function offsetGet($offset){

				if(is_array($this->contents[$offset])){

					$cfg = new PHPConfig($this->contents[$offset]);
					return $cfg->parse();

				}

				return $this->contents[$offset];

			}

			public function offsetSet($offset,$value){

				$this->contents[$offset]	=	$value;

			}

			public function __get($offset){

				return $this->offsetGet($offset);

			}

			public function __set($offset,$value){

				$this->offsetSet($offset,$value);

			}

			public function offsetUnset($offset){

				unset($this->contents[$offset]);

			}

			protected function setLastError($error){
	
				$this->lastError	=	$error;
				return $this;

			}

			public function getLastError(){

				return $this->lastError;

			}

			public function rewind(){

				reset($this->contents);

			}

			public function current(){

				$cur	=	current($this->contents);

				if(is_array($cur)){

					$cur	=	new PHPConfig($cur);
					$cur->parse();

				}

				return $cur;

			}

			public function next(){

				return next($this->contents);

			}

			public function key(){

				return key($this->contents);

			}

			public function valid(){

				$key	=	$this->key();

				return $key!== NULL && $key !== FALSE;

			}

			public function isFile(){

				return (boolean)$this->isFile;

			}

			public function to($format){

				$f = new Factory($this->contents,$format);
				return $f->build()->parse();

			}

		}

	}
