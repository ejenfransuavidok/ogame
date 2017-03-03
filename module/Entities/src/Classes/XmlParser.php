<?php

/**
 * 
 * @27.02.2017
 * 
 */

namespace Entities\Classes;

class ErrorXmlParse extends \Exception
{
}

class XmlParser
{
    
    public function parse($file)
    {
        $result = array();
        if(!file_exists($file)) {
            throw new FileNotFound($file);
        }
        else {
            $simple = file_get_contents($file);
            $p = xml_parser_create();
            xml_parse_into_struct($p, $simple, $vals, $index);
            xml_parser_free($p);
            $rows = $index ['ROW'];
            $cells = $index ['CELL'];
            $data = $index ['DATA'];
            $i = 0;
            while ($i < count($rows) - 1) {
                $from = $rows [$i];
                $to = $rows [$i+1];
                $one_row = array();
                $j = 0;
                while ($j <= count($data) - 1) {
                    if($data[$j] >= $from && $data[$j] <= $to) {
                        $one_row [] = $vals[$data[$j]]['value'];
                    }
                    if($data[$j] > $to) {
                        break;
                    }
                    $j++;
                }
                if(count($one_row)) {
                    $result [] = $one_row;
                }
                $i+=2;
            }
        }
        return $result;
    }
    
}
