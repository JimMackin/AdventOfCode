<?php



function calcJSON($json){
    $ob = json_decode($json,1);
    echo "Sum is ". sum($ob)."\n";
}

function sum($arr){
    $total = 0;
    foreach($arr as $item){
        if(is_array($item)){
            $total += sum($item);
        }elseif(is_numeric($item)){
            $total += $item;
        }
    }
    return $total;
}

$json = file_get_contents("day12.json");

calcJSON($json);

