<?php

	namespace pthreat{

		use \pthreat\config\Factory	as	ConfigFactory;

		class Parameters{

			public function __construct($params=NULL){

				if($params!==NULL){

					$this->setArguments($params);

				}

			}

			public function setArguments($args){

				$this->parameters	=	new ConfigFactory($args);
				$this->parameters	=	$this->parameters->build()->parse();
				return $this;

			}

			public function getArguments(){

				return $this->parameters;

			}

			public function required($args){

				if(!is_array($args)){

					$args	=	Array($args);

				}

				$allArgs	=	func_get_args();

				if(array_diff($allArgs,$args)){

					$args	+= $allArgs;

				}

				$keys	=	$this->parameters->values();
				$diff	=	array_diff($args,$keys);

				if($amount = count($diff)){

					$missing	=	implode(', ',$diff);
					$msg		=	sprintf('Missing parameter%s %s',$amount>1 ? 's' : '', $missing);
					throw new \InvalidArgumentException($msg);

				}

				return TRUE;

			}

		}

	}
