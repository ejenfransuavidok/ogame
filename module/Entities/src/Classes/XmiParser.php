<?php

/**
 * 
 * @27.02.2017
 * 
 */

namespace Entities\Classes;

class FileNotFound extends \Exception
{
}

class ErrorXmiParse extends \Exception
{
}

class XmiParser
{
    
    public function parse($file, $matches)
    {
        $result = array();
        if(!file_exists($file)) {
            throw new FileNotFound($file);
        }
        else {
            $content = file_get_contents($file);
            $content = str_replace ('UML:', 'XMI.', $content);
            if($xml = simplexml_load_string($content)) {
                foreach($matches as $match) {
                    $result [] = $xml->xpath($match);
                }
            }
            else {
                 throw new ErrorXmiParse("Error: Cannot create object");
            }
        }
        return $result;
    }
    
}
