<?php
/*
Genetic algorithm solution for Advent Of Code Day 15 -1.
I'd initially assumed a brute force algorithm wouldn't be feasible (I was wrong)
so did it this way.
*/
function calcBest($in){
  $populationSize = 20;
  $survivorSize = 5;
    $ingredients = getIngredients($in);
    $pop = generateInitialPopulation($ingredients,$populationSize);
    $bestSize = 0;
    $bestSizeUnchanged = 0;
    $count = 0;
    while(true){
        //Sort by fitness
        usort($pop,function($a,$b){
          return $b['score'] - $a['score'];
        });
        $lastBestSize = $bestSize;
        $bestSize = $pop[0]['score'];

        if($lastBestSize === $bestSize){
          $bestSizeUnchanged++;
        }else{
          $bestSizeUnchanged = 0;
        }
        if($bestSizeUnchanged > 10000){
          break;
        }
        //Choose the n best
        $pop = array_slice($pop,0,$survivorSize);
        //Breed new population
        $pop = breedNewpop($pop,$populationSize, $ingredients);
        $count++;
        if($count > 1000000){
          break;
        }
    }
    uasort($pop,function($a,$b){
      return $b['score'] - $a['score'];
    });
    print_r(array_shift($pop));
}
function normalise($survivor){
  $total = 0;
  foreach($survivor as $ingredient => $amount){
    if($ingredient == 'score' || $ingredient == 'calorie'){
      continue;
    }
    if($amount >= 0){
      $total += $amount;
    }
  }
  foreach($survivor as $ingredient => $amount){
    if($ingredient == 'score' || $ingredient == 'calorie'){
      continue;
    }
    if($amount >= 0){
      $survivor[$ingredient] = (int)(($amount / $total)* 100);
    }else{
      $survivor[$ingredient] = 0;
    }
  }
  return $survivor;
}
function mutate($survivor,$ingredients){
  $freeAmount = 0;
  foreach($survivor as $ingredient => $amount){
    if(rand(0,100) <= 2){
      $change = rand(-5,5);
      $survivor[$ingredient] = $amount + $change;
      $freeAmount -= $change;
    }
  }
$survivor = normalise($survivor);
  $survivor['score'] = calculateFitness($survivor,$ingredients);
  return $survivor;
}

function breedNewPop($pop,$newPopSize, $ingredients){
  $newPop = array();
   foreach($pop as $survivor){
       $newPop[] = $survivor;
       $newPop[] = mutate($survivor,$ingredients);
   }
   if(count($newPop) < $newPopSize){
     $newPop = array_merge(generateInitialPopulation($ingredients, $newPopSize - count($newPop)),$newPop);
   }
   return $newPop;
}

function calculateFitness($solution, $ingredients){
  $cookieComp = array();
  foreach($solution as $ingredient => $amount){
    if($ingredient == 'score' || $ingredient == 'calorie'){
      continue;
    }
    foreach($ingredients[$ingredient] as $property => $val){
      if(empty($cookieComp[$property])){
          $cookieComp[$property] = 0;
      }
      $cookieComp[$property] = $cookieComp[$property] + ($amount * $val);
    }
  }
  $score = 1;
  foreach($cookieComp as $property => $val){
    if($property == 'calories' || $property == 'score'){
      continue;
    }
    if($val < 0){
      $val = 1;
    }
    $score = $score * $val;
  }
  return $score;
}

function generateInitialPopulation($ingredients, $count){
    $pop = array();
    for($x = 0; $x < $count; $x++){
        $solution = array();
        $remaining = 100;
        foreach($ingredients as $name => $ingredient){
          $amount = rand(0,$remaining);
          $solution[$name] = $amount;
          $remaining -= $amount;
        }
        $solution['score'] = calculateFitness($solution,$ingredients);
       $pop[] = $solution;
    }
    return $pop;
}

function getIngredients($in){
    $ingredients = array();
    $pattern = "/^(.*): capacity (-?[0-9]*), durability (-?[0-9]*), flavor (-?[0-9]*), texture (-?[0-9]*), calories (-?[0-9]*)$/";
    foreach(explode("\n",$in) as $ingredientLine){
      $matches = array();
      preg_match($pattern, $ingredientLine,$matches);
      $name = $matches[1];
      $capacity = $matches[2];
      $durability = $matches[3];
      $flavor = $matches[4];
      $texture = $matches[5];
      $calories = $matches[6];
      $ingredients[$name] = array('capacity' => $capacity,'durability' => $durability,'flavor' => $flavor,'texture'=>$texture,'calories'=>$calories);
    }
    return $ingredients;
}

$in  ="Sprinkles: capacity 2, durability 0, flavor -2, texture 0, calories 3
Butterscotch: capacity 0, durability 5, flavor -3, texture 0, calories 3
Chocolate: capacity 0, durability 0, flavor 5, texture -1, calories 8
Candy: capacity 0, durability -1, flavor 0, texture 5, calories 8";

calcBest($in);
