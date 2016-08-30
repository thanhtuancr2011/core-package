<?php 
namespace Comus\Core\Services;

class TimeService{
	
	public static function setTimeZoneCurrentUser($timeZoneUser, $timeZoneCreate){

		$time = strtotime($timeZoneCreate) - intval($timeZoneUser*60);

		return $time;
	}
}