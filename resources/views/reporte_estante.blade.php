<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<style>


.body_wrapper {
    padding: 10px 20px 10px 20px;
    background: rgb(255, 255, 255) none;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    max-width: 750px;
    margin: 0 auto;
    
}
   
    body{/* quitar el body para la impresion*/
        font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        position: relative;
        font-size:12px;
    }
    .derecha{
        text-align:right;
        padding-right: 10px;
    }
    .izquierda{
        text-align: left;
    }
    .centro{
        text-align: center;
    }
    span {
        font-size:10px;
        padding-left:50px;
    }
    div{
        background-image:url("{{ asset('img/borrador.png')}}");
    }
    table{
        width: 100%;
        /* border: 1px solid #024a86;
        border-collapse: collapse; */
        
    }
    .tabla2 th, .tabla2 td{
        /* border: 1px solid #024a86; */
        border-bottom: 1px solid #024a86;
        
    }
    th{
        font-size: 14px;
        border-bottom: 1px solid #024a86;
    }
    td{
        /* border-right: 1px solid #024a86; */
        border-bottom: 1px solid #024a86;
    }
    .tabla2{
        width: 100%;
       /*  border: 1px solid #024a86;
        border-collapse: collapse; */

    }
    hr {
        border: 3px solid green;
        border-radius: 2px;
    }
     
</style>
<body style="background-color: cadetblue;">
<div class="body_wrapper">

    
    <div class="centro">
        <h1>Generador de Codigos de Estante</h1>
    </div>
    @for ($i = 1; $i <= $posicion; $i++)
        @for ($j = 1; $j <= $altura; $j++)
            @if ($i<10)
                @if ($j<10)
                    <h2>{{ $codestante }}-0{{ $i }}-0{{ $j }}</h2>        
                @else
                    <h2>{{ $codestante }}-0{{ $i }}-{{ $j }}</h2>
                @endif    
            @else
                @if ($j<10)
                    <h2>{{ $codestante }}-{{ $i }}-0{{ $j }}</h2>        
                @else
                    <h2>{{ $codestante }}-{{ $i }}-{{ $j }}</h2>
                @endif
            @endif
        @endfor
    @endfor
    </div> 
</body>
</html>