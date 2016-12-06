<?php

	namespace pthreat\config\adapter{

		use pthreat\config\Adapter;

		class Ini extends Adapter{

			protected function __parse($contents, Array $options=Array()){

				$return	=	parse_ini_string($contents,$sections=TRUE);

				if(!$return){

					$this->setLastError('Unable to parse INI file');

				}

				return $return;

			}

			protected function __dump(Array $contents=Array()){

				return $this->toIni($contents);

			}

			private function toIni($contents, Array $parent=Array()){

				$output = '';
				foreach ($contents as $k => $v) {

					$index = str_replace(' ', '-', $k);

					if (is_array($v)) {
						$sec = array_merge((array) $parent, (array) $index);
						$output .= PHP_EOL . '[' . join('.', $sec) . ']' . PHP_EOL;
						$output .= $this->toIni($v, $sec);
					} else {
						$output .= "$index=";
						if (is_numeric($v) || is_float($v)) {
							$output .= "$v";
						} elseif (is_bool($v)) {
							$output .= ($v===true) ? 1 : 0;
						} elseif (is_string($v)) {
							$output .= "'".addcslashes($v, "'")."'";
						} else {
							$output .= "$v";
						}
						$output .= PHP_EOL;
					}
				}
				return $output;

			}

		}

	}
