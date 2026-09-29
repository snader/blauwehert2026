<?php

class PolicyCookie extends Cookie {

    protected static $aCoreCookies = ['PHPSESSID', 'LFT'];
    /**
     * Destroy all cookies that are safe to destroy.
     */
    public static function destroy(){
        foreach(array_keys(parent::getAll()) as $sCookie){
            if(in_array($sCookie, self::$aCoreCookies)){
                continue;
            }
            parent::clear($sCookie);
        }
    }
}