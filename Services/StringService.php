<?php 

namespace Comus\Core\Services;

class StringService
{
    /**
     * Fomat string number to phone number.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $string  String number
     */
    public static function fomatPhoneNumber($string)
    {
        if(strlen($string) == 10) {

            $str1 = substr($string,0,3);
            $str2 = substr($string,3,3);
            $str3 = substr($string,6,4);

            return '('.$str1.') '.$str2.'-'.$str3;

        } else if(strlen($string) > 10 && strlen($string) <= 15) {

            $str1 = substr($string,0,3);
            $str2 = substr($string,3,3);
            $str3 = substr($string,6,4);
            $str4 = substr($string,10);

            return '('.$str1.') '.$str2.'-'.$str3.' x'.$str4;
        }

      return 0;         
    }
}

