<?php
if(!function_exists("print_r_html")){
	function print_r_html ($arr) {
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>