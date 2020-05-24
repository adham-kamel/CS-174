<?php

function getPrimes($n){
    $prime = array_fill(0, $n + 1, true);
    $prime_values = '';
    if (!is_int($num) || $num < 0) {
        echo "My function output: Not a valid integer";
        echo "<br>";
    }
    for ($p = 2;  $p * $p <= $n; $p++){
        if ($prime[$p] == true){
            for ($i = $p * $p; $i <= $n; $i += $p){
                $prime[$i] = false;
            }
        }
    }
    for ($p = 2; $p  <= $n; $p++){
        if ($prime[$p]){
            $prime_values = $prime_values . $p . " ";
        }
    }
    echo $prime_values."<br>";
    return $prime_values;
}

function test(){
    $primes10 = getPrimes(10);
    if ($primes10 == '2 3 5 7 '){
        echo "Test Passed for getPrimes(10) as it is equal to '2 3 5 7'<br>";
    }
    else{
        echo "Test Did Not Pass for getPrimes(10) as it is not equal to '2 3 5 7'<br>";
    }
    $primes35 = getPrimes(35);
    if (strcmp($primes35, '2 3 5 7 11 13 17 19 23 29 31 ') == 0){
        echo "Test Passed for get(35) as it is equal to '2 3 5 7 11 13 17 19 23 29 31'<br>";
    }
    else{
        echo "Test Did Not Passs for getPrimes(35) as it is not equal to '2 3 5 7 11 13 17 19 23 29 31'<br>";
    }
}

test();
?>