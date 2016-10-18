<?php

// Simon Willison, 16th April 2003
// Based on Lars Marius Garshol's Python XMLWriter class
// See http://www.xml.com/pub/a/2003/04/09/py-xml.html

class XMLHelper
{
    private $xml;
    private $indent;
    private $stack = array();
    
    public function __construct($indent = '  ')
    {
        $this->indent = $indent;
        $this->xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
    }
    
    public function escapeXML($str)
    {
        return utf8_decode(str_replace('&', '&amp;', $str));
    }
    
    public function _indent()
    {
        for ($i = 0, $j = count($this->stack); $i < $j; $i++) {
            $this->xml .= $this->indent;
        }
    }
    
    public function push($element, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<'.$element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' '.$key.'="'.$this->escapeXML($value).'"';
        }
        $this->xml .= ">\n";
        $this->stack[] = $element;
    }
    
    public function element($element, $content, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<'.$element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' '.$key.'="'.$this->escapeXML($value).'"';
        }
        $this->xml .= '>'.$this->escapeXML($content).'</'.$element.'>'."\n";
    }
    
    public function emptyelement($element, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<'.$element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' '.$key.'="'.$this->escapeXML($value).'"';
        }
        $this->xml .= " />\n";
    }
    
    public function pop()
    {
        $element = array_pop($this->stack);
        $this->_indent();
        $this->xml .= "</$element>\n";
    }
    
    public function getXml()
    {
        return $this->xml;
    }
}
