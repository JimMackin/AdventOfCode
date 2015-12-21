<?php
$say = "1113122113";
for($x = 0; $x < 50; $x++){
    $say = lookAndSay($say);
}
echo strlen($say)."\n";

function lookAndSay($str){
    $prevDigit = -1;
    $runs = 0;
    $out = '';
    for($x = 0; $x < strlen($str); $x++){
        $digit = substr($str,$x,1);
        if($prevDigit == -1){
            $runs++;
            $prevDigit = $digit;
            continue;
        }
        if($prevDigit == $digit){
            $runs++;
        }else{
            $out .= $runs . $prevDigit;
            $runs = 1;
        }
        $prevDigit = $digit;
    }
    $out .= $runs . $prevDigit;
    return $out;
}
