<?php
/*
+	File function for parse YAML struct.
+
+
+		@category PHP, Function, String, YAML
+		@package  Function Library
+		@since    1.0
+		@author   King Dark
+		@license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
+		@link     http://www.kingdark.org/
+
*/
define("____DK189_LIBRARY_YAML", TRUE);
if( !function_exists('yaml_from_array') ) {
	function yaml_from_array(Array $array, $lvl = 0) {
		$str	= "";
		foreach($array as $name => $value){
			if( is_array($value) ) {
				$str .= "" . str_repeat('  ', $lvl) . $name . ":" . "\n";
				$str .= yaml_from_array($value, $lvl + 1);
			} else {
				$str .= "" . str_repeat('  ', $lvl) . $name . ": " . $value . "\n";
			}
		}
		return $str;
	}
}
if( !function_exists('yaml_file_from_array') ) {
	function yaml_file_from_array($file, Array $array) {
		return file_put_contents($file, yaml_from_array($array));
	}
}
if( !function_exists('yaml_to_array') ) {
	function yaml_to_array($str) {
		if( !is_string($str) ) return false;
		$return			= array();
		$lines			= explode("\n", $str);
		$lvl			= 0;
		$floor			= array();
		$floor[$lvl]	= &$return;
		foreach($lines as $line){
			if( !empty($line) && substr($line, 0, 1) != "#" ){
				while( substr($line,0,$lvl*2) != str_repeat('  ', $lvl) ) $lvl--;
				$line	= substr($line, $lvl*2);
				$a		= explode(':', $line);
				if(!empty($a[1])){
					$floor[$lvl][$a[0]] = substr($a[1], 1);
				} else {
					$floor[$lvl][$a[0]] = array();
					$_lvl = $lvl;
					$lvl++;
					$floor[$lvl]		= &$floor[$_lvl][$a[0]];
					unset($_lvl);
				}
			}
		}
		return $return;
	}
}
if( !function_exists('yaml_file_to_array') ) {
	function yaml_file_to_array($file) {
		if( file_exists($file) ) {
			$file = file_get_contents($file);
			return yaml_to_array($file);
		} else 
			return false;
	}
}
?>