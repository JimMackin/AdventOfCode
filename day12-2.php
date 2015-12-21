<?php



function calcJSON($json){
    $ob = json_decode($json,1);
    echo "Sum is ". sum($ob)."\n";
}

function sum($arr){
    $total = 0;
    if(is_red($arr)){
        return 0;
    }
    foreach($arr as $item){
        if(is_red($item)){
            continue;
        }
        if(is_array($item)){
            $total += sum($item);
        }elseif(is_numeric($item)){
            $total += $item;
        }
    }
    return $total;
}
function is_red($ob){
    if(!is_array($ob)){
        return false;
    }
    if(!is_assoc($ob)){
        return false;
    }
    foreach($ob as $val){
        if($val === 'red'){
            return true;
        }
    }
    return false;
}
function is_assoc($arr){
    return $arr !== array_values($arr);
}

$json = file_get_contents("day12.json");

calcJSON($json);

