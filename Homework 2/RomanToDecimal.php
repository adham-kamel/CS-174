<?php

    function toDecimal($roman){
        if (is_string($roman) == false){
            return "The input ".$roman. " is not a string";
        }
        $decimal = 0;
        $allowedChars = "IVXLCDM";
        $current = "";
        $count = 1;
        for($i = 0; $i < strlen($roman); $i++){
            $letter = substr($roman, $i, 1);
            if (strpos($allowedChars, $letter) === false){
                return "The letter ".$letter." is not a valid Roman Numeral";
            }
            if ($letter == "I"){
                if ($i != strlen($roman) - 1){
                    $i++;
                    $notAllowed = "CDM";
                    if (substr($roman, $i, 1) == "V"){
                        $decimal += 4;
                    }
                    else if (substr($roman, $i, 1) == "X"){
                        $decimal += 9;
                    }
                    else if (strpos($notAllowed, substr($roman, $i, 1)) === true){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    else{
                        $decimal++;
                        $i--;
                    }
                }
                else{
                    $decimal++;
                }
                if ($letter == $current){
                    $count++;
                }
                else{
                    $count = 1;
                }
                if ($count > 3){
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
                $current = "I";
            }
            else if ($letter == "X"){
                if ($decimal % 10 === 0){
                    if ($i != strlen($roman) - 1){
                        $i++;
                        $notAllowed = "DM";
                        if (substr($roman, $i, 1) == "L"){
                            $decimal += 40;
                        }
                        else if (substr($roman, $i, 1) == "C"){
                            $decimal += 90;
                        }
                        else if (strpos($notAllowed, substr($roman, $i, 1)) === true){
                            return "The input ".$roman." is not a valid Roman Numeral";
                        }
                        else{
                            $decimal += 10;
                            $i--;
                        }
                    }
                    else{
                        $decimal += 10;
                    }
                    if ($letter == $current){
                        $count++;
                    }
                    else{
                        $count = 1;
                    }
                    if ($count > 3){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    $current = "X";
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
            else if ($letter == "C"){
                if ($decimal % 100 === 0){
                    if ($i != strlen($roman) - 1){
                        $i++;
                        if (substr($roman, $i, 1) == "D"){
                            $decimal += 400;
                        }
                        else if (substr($roman, $i, 1) == "M"){
                            $decimal += 900;
                        }
                        else{
                            $decimal += 100;
                            $i--;
                        }
                    }
                    else{
                        $decimal += 100;
                    }
                    if ($letter == $current){
                        $count++;
                    }
                    else{
                        $count = 1;
                    }
                    if ($count > 3){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    $current = "C";
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
            else if ($letter == "M"){
                if ($decimal % 1000 === 0){
                    $decimal += 1000;
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
            else if ($letter == "V"){
                if ($decimal % 5 === 0){
                    $decimal += 5;
                    if ($letter == $current){
                        $count++;
                    }
                    else{
                        $count = 1;
                    }
                    if ($count > 1){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    $current = "V";
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
            else if ($letter == "L"){
                if ($decimal % 50 === 0){
                    $decimal += 50;
                    if ($letter == $current){
                        $count++;
                    }
                    else{
                        $count = 1;
                    }
                    if ($count > 1){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    $current = "L";
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
            else if ($letter == "D"){
                if ($decimal % 500 === 0){
                    $decimal += 500;
                    if ($letter == $current){
                        $count++;
                    }
                    else{
                        $count = 1;
                    }
                    if ($count > 1){
                        return "The input ".$roman." is not a valid Roman Numeral";
                    }
                    $current = "D";
                }
                else{
                    return "The input ".$roman." is not a valid Roman Numeral";
                }
            }
        }
        return $decimal;
    }

    function test(){
        $value69 = toDecimal("LXIX");
        echo "Decimal Value for LXIX: Expected: 69, Actual: ". $value69."\n";
        $value1990 = toDecimal("MCMXC");
        echo "Decimal Value for MCMXC: Expected: 1990, Actual: ". $value1990."\n";
        $valueDesc = toDecimal("MDCLXVI");
        echo "Decimal Value for MDCLXVI: Expected: 1666, Actual: ".$valueDesc."\n";
        $valueNoRN = toDecimal("E");
        echo "Decimal Value for E: Expected: The letter E is not a valid Roman Numeral, Actual: ".$valueNoRN."\n";
        $valueNotString = toDecimal(14);
        echo "Decimal Value for 14: Expected: The input 14 is not a string, Actual: ".$valueNotString."\n";
        $valueIIII = toDecimal("IIII");
        echo "Decimal Value for IIII: Expected: The input IIII is an not a valid Roman Numeral, Actual: ".$valueIIII."\n";
        $valueIC = toDecimal("IC");
        echo "Decimal Value for IC: Expected: The input IC is not a valid Roman Numeral, Actual: ".$valueIC."\n";
        $valueMCMM = toDecimal("MCMM");
        echo "Decimal Value for MCMM: Expected The input MCMM is not a valid Roman Numeral, Actual: ".$valueMCMM."\n";
    }

    test();
?>