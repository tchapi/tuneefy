<?php

class i18nHelper
{
    private $lang;
    private $currentLang;
    
    // Constructor
    public function __construct()
    {
        $lng = array();
        $availableLangs = array();
        foreach (glob(_PATH."/include/langs/lang.*.php") as $filename) {
            $splitted = explode('.', basename($filename));
            $availableLangs[] = $splitted[1];
            $lang = array();
            require_once($filename);
            $lng[$splitted[1]] = $lang;
        }
        
        if (isset($_COOKIE["tuneefyLocale"]) && isset($lng[$_COOKIE["tuneefyLocale"]])) {
            $this->currentLang = strtolower($_COOKIE["tuneefyLocale"]);
        } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && isset($lng[substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)])) {
            $this->currentLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        } else {
            $this->currentLang = 'en';
        }
        $this->lang = $lng;
    }
    
    // Returns the actual language
    public function whichLang()
    {
        return $this->currentLang;
    }
    
    // MAGIC CALL ->parameter
    public function __get($key)
    {
        echo $this->getI18NValue($key);
    }
    
    // MAGIC CALL ->parameter(arguments)
    public function __call($key, $options)
    {
        echo $this->getI18NValue($key, $options);
    }

    // Same, but does not printf
    public function get($key, $options = array())
    {
        return $this->getI18NValue($key, $options);
    }

    
    // Utilities
    private function sprintf_array($format, $arr)
    {
        return call_user_func_array('sprintf', array_merge((array)$format, (array)$arr));
    }
    
    private function getI18NValue($key, $options = array())
    {
        $val = '';
        if (isset($this->lang[$this->currentLang][$key])) {
            $val = $this->lang[$this->currentLang][$key];
        } else {
            $val = $this->lang['en'][$key];
        }
        return $this->sprintf_array($val, $options);
    }
}

$i18n = new i18nHelper();
