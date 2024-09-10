<?php

namespace App\Helpers;

class operacionDosificacion
{
    
    public static function operacion($Llave_Dosificacion, $num_auto,$num_factura,$num_documento,$fecha_transaccion,$monto_transaccion)
    {
        
        //$Llave_Dosificacion="9rCB7Sv4X29d)5k7N%3ab89p-3(5[A";
        $numero_11=$monto_transaccion;
        $numero_convertido = str_replace(',', '.', $numero_11);
        // Convertir a float
        $numero_decimal = (float) $numero_convertido;
        // Redondear al entero más cercano
$redondeado = round($numero_decimal);
   
       /* ========== PASO 1 ============= */ 
            $arrayBloique = [
                0 => $num_auto,//num_auto
                1 => $num_factura, //num_factura
                2 => $num_documento, //num_documento
                3 => $fecha_transaccion, //fecha_transaccion
                4 => $redondeado, //monto_transaccion
            ];
            $num_auto=$arrayBloique[0];
            $arrayoperacion=[];
            foreach ($arrayBloique as $key => $value) {
                if ($key!=0) {
                    $valor=0;
                    $valor_cadena_1="";
                    $concatenador_1="";
                    $concatenador_2="";
                    $valor_cadena_2="";              
                                         
                        $valor_cadena_1 = Verhoeff::generar($value);
                        $concatenador_1 = $value.$valor_cadena_1;
                        $valor_cadena_2 = Verhoeff::generar($concatenador_1);
                        $concatenador_2 = $concatenador_1.$valor_cadena_2;
                      
                        $arrayoperacion[] = $concatenador_2;                
                }
              
           
            }
            $sumaTotal = 0;

foreach ($arrayoperacion as $valor) {
    $sumaTotal += (int)$valor; // Convertir a entero y sumar
}
 
$paso_1="";
$paso_2=$sumaTotal;
$cinco_dig_verhoeff="";
for ($i=0; $i <5 ; $i++) { 
    $dato_2 = Verhoeff::generar($paso_2);
    $cinco_dig_verhoeff=$cinco_dig_verhoeff.$dato_2;
    $paso_1 = $paso_2.$dato_2;
    $paso_2 = $paso_1;              
}
/* ========== PASO 2 ============= */ 
$numeroComoCadena = (string)$cinco_dig_verhoeff;  // Convertir el número a cadena

$digitos = str_split($numeroComoCadena);  // Dividir la cadena en un array de caracteres

// Imprimir cada dígito por separado
$arraySuma_uno_a_cada_digito_verhoeff=[];


$anteriror=0;

$array_cadenas=[];

$i_2=0;

foreach ($digitos as $digito) {
    $arraySuma_uno_a_cada_digito_verhoeff[]=((int)$digito)+1;
    $subcadena = substr($Llave_Dosificacion, $anteriror, $arraySuma_uno_a_cada_digito_verhoeff[$i_2]);
   
    $array_cadenas[]=$subcadena;

    $anteriror=$anteriror+$arraySuma_uno_a_cada_digito_verhoeff[$i_2];
    $i_2++;

}
/* ========== PASO 3 ============= */ 

$cadena_concatenada="";
    for ($i=0; $i <5 ; $i++) { 
        if ($i==0) {
            $cadena_concatenada=$num_auto.$array_cadenas[$i];   
        }else {
            $cadena_concatenada=$cadena_concatenada.$arrayoperacion[$i-1].$array_cadenas[$i];   
        }
            
    }
$llave_cifrado=$Llave_Dosificacion.$cinco_dig_verhoeff;
 // Cifrar los datos
 // Criptografar dados
 $message = $cadena_concatenada ;
 $key = $llave_cifrado;
 
 $allegedRC4String = AllegedRC4Helper::encryptMessageRC4($message, $key);
 /* ========== PASO 4 ============= */
        //cadena encriptada en paso 3 se convierte a un Array         
        $chars = str_split($allegedRC4String);
        //se suman valores ascii
        
        $ST=0;
        $sp1=0;
        $sp2=0;
        $sp3=0;
        $sp4=0;
        $sp5=0;
        
        $tmp=1;
        for($i=0; $i<strlen($allegedRC4String);$i++){
            $ST += ord($chars[$i]);
            switch($tmp){
                case 1: $sp1 += ord($chars[$i]); break;
                case 2: $sp2 += ord($chars[$i]); break;
                case 3: $sp3 += ord($chars[$i]); break;
                case 4: $sp4 += ord($chars[$i]); break;
                case 5: $sp5 += ord($chars[$i]); break;
            }    

            $tmp = ($tmp<5)?$tmp+1:1;
        }        

         /* ========== PASO 5 ============= */                                                        
            $tmp1 = floor($ST*$sp1/$arraySuma_uno_a_cada_digito_verhoeff[0]);     
            $tmp2 = floor($ST*$sp2/$arraySuma_uno_a_cada_digito_verhoeff[1]);
            $tmp3 = floor($ST*$sp3/$arraySuma_uno_a_cada_digito_verhoeff[2]);
            $tmp4 = floor($ST*$sp4/$arraySuma_uno_a_cada_digito_verhoeff[3]);
            $tmp5 = floor($ST*$sp5/$arraySuma_uno_a_cada_digito_verhoeff[4]);         
            $tmpT=$tmp1+$tmp2+$tmp3+$tmp4+$tmp5;
        $base64Value = Base64SINHelper::convert($tmpT);
        /* ========== PASO 6 ============= */  
        $message = $base64Value ;
      
        $allegedRC4String = AllegedRC4Helper::encryptMessageRC4($message, $key);
       
        $formattedValue = implode('-', str_split($allegedRC4String, 2));
     

        return $formattedValue;
    }
}