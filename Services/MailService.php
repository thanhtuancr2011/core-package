<?php 

namespace Comus\Core\Services;

use Mail;

class MailService{

	/**
     * Email welcome.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $template  Template mail
     * @param  Array  $data	  Data mail
     */
	public static function emailWelcome($template, $data = [], $function)
	{
		Mail::send($template, $data, $function);
	}

	/**
     * Email welcome.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $template  Template mail
     * @param  Array  $data	  Data mail
     */
	public static function send($template, $data = [], $function)
	{
		try {
			Mail::send($template, $data, $function);
		} catch (\Swift_TransportException $STe) {
			//
		}

		catch(\Exception $e){
			//
		}
	}

	/**
     * Email welcome.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $template  Template mail
     * @param  Array  $data	  Data mail
     */
	public static function queue($template, $data = [], $function)
	{
		try {
			Mail::queue($template, $data, $function);
		} catch (\Swift_TransportException $STe) {
			//
		}

		catch(\Exception $e){
			//
		}
	}

	/**
     * Email welcome.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $template  Template mail
     * @param  Array  $data	  Data mail
     */
	public static function later($time, $template, $data = [], $function)
	{
		try{
			Mail::later($time, $template, $data, $function);
		} catch (\Swift_TransportException $STe) {
			//
		}

		catch(\Exception $e){
			//
		}
	}

	/**
     * Email welcome.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $template  Template mail
     * @param  Array  $data	  Data mail
     */
	public static function checkEmail($email){
		return $email;
		return env('email_test', $email);
	}
}
