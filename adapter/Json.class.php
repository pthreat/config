<?php

	namespace pthreat\config\adapter{

		use pthreat\config\Adapter;

		class Json extends Adapter{

			protected function __parse($contents,Array $options=Array()){

				$return	=	json_decode($contents,$asArray=TRUE);
				$error	=	json_last_error();

				if($error){

					$this->setLastError($this->getJsonError($error));

				}

				return $return;

			}

			protected function __dump(Array $contents=Array()){

				$return	=	json_encode($contents);
				$error	=	json_last_error();

				if($error){

					$this->setLastError($this->getJsonError($error));

				}

				return $return;

			}

			public function getJsonError($error){

				switch ($error){

					case \JSON_ERROR_NONE:
						$msg	=	'';
					return;

					case \JSON_ERROR_DEPTH:
						$msg	=	'Maximum stack depth exceeded';
					break;

					case \JSON_ERROR_STATE_MISMATCH:
						$msg	=	'Underflow or the modes mismatch';
					break;

					case \JSON_ERROR_CTRL_CHAR:
						$msg	=	'Unexpected control character found';
					break;

					case \JSON_ERROR_SYNTAX:
						$msg	=	'Syntax error, malformed JSON';
					break;

					case \JSON_ERROR_UTF8:
						$msg	=	'Malformed UTF-8 characters, possibly incorrectly encoded';
					break;

					default:
						$msg	=	'Unknown error';
					break;
    			}

				return $msg;

			}

		}

	}
