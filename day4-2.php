<?php

$in = 'iwrupvqb';

$start = 1;
while(true){
    $md = md5($in.$start);
    if(strpos($md,'000000') === 0){
        echo "Num is $start\n";
        die();
    }
    $start++;
}
