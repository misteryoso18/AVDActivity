<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculateController extends Controller
{
    public function add () {
        $n1 = 5;   
        $n2 = 3;     
        $sum = $n1 + $n2; 
        return "Sum is: ".$sum; 
    }
    public function subtract () {
        $n1 = 5;   
        $n2 = 3;    
        $sub = $n1 - $n2; 
        return "Difference is: ".$sub;
    }
    public function multiply () {
        $n1 = 5;   
        $n2 = 3;     
        $mul = $n1 * $n2;
        return "Product is: ".$mul; 
    }
    public function divide () {
        $n1 = 5;   
        $n2 = 3;     
        $div = $n1 / $n2; 
        return "Quotient is: ".$div;
    }
    public function modulo () {
        $n1 = 5;   
        $n2 = 3;  
        $mod = $n1 % $n2; 
        return "Remainder is: ".$mod; 
    }
    

    

}
