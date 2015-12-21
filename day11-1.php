<?php

echo "Next pass is ".getNextPass('vzbxkghb')."\n";

function getNextPass($current){
    //Lazy solution - loop over each and check it's valid
    $current++;
    while(!valid($current)){
        $current++;
    }
    return $current;
}

function valid($pass){
    if(preg_match('/i|o|l/',$pass)){
        return false;
    }
    if(!preg_match('/(.)\1.*(.)\2/',$pass)){
        return false;
    }
    $prev = -1;
    $straightLen = 1;
    for($x = 0; $x < strlen($pass); $x++){
        $cur = substr($pass,$x,1);
        $prev++;

        if($cur == $prev){
            $straightLen++;
            if($straightLen >= 3){
                return true;
            }
        }else{
            $straightLen = 1;
        }
        $prev = $cur;
    }
    return false;
}
