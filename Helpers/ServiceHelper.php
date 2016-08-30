<?php
if ( ! function_exists('getBaseUrl')) {
	function getBaseUrl()
	{
		return \Config::get('app.base_url');
	}
}

function d($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

