<?php

function countCode($str){
    $str = str_replace('\\\\','-',$str);
    $patt = "/\\\x../";
    $str = preg_replace($patt,'-',$str);
    $str = str_replace('\\"','"',$str);
    return strlen($str) -2;
}

function countBytes($str){
    return strlen($str);
}

function countStrings($in){
    $list = explode("\n",$in);
    $tot = 0;
    foreach($list as $str){
        if(!$str){
            continue;
        }
        $code = countCode($str);
        $bytes = countBytes($str);
        $tot -= $code;
        $tot += $bytes;
    }
   echo "Tot is $tot\n";
}



countStrings(file_get_contents('puzzle8input.txt'));
