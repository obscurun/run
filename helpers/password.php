<?php

class Password{
    function Password() {
    }
    function getRandom($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
      $lmin = 'abcdefghjklmnpqrstuvwxyz'; // sem i ou o, para não confundir com 1 e 0
      $lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // sem i ou o, para não confundir com 1 e 0
      $num = '1234567890';
      $simb = '!@#$%*-';
      $pass = '';
      $car = '';

      $car .= $lmin;
      if($maiusculas) $car .= $lmai;
      if($numeros)    $car .= $num;
      if($simbolos)   $car .= $simb;

      $len = strlen($car);
      for($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $pass .= $car[$rand-1];
      }
      return $pass;
    }
}
?>