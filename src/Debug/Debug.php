<?php

	namespace Temmel\DHLPackageAPI\Debug;

	class Debug  {
		static function debug($params){
			echo '<pre>';
			print_r($params);
			echo '</pre>';
		}
	}