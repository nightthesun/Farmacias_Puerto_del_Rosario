<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
     
     /* Reset básico */
     * {
        margin: 2;
        padding: 1;
        box-sizing: border-box; /* Asegura que el tamaño total incluya padding y border */
    }
    
    body {
        font-size: 11px;
        padding: 1px;
    }
    .header {
    overflow: hidden;
    white-space: nowrap; /* Evita el salto de línea */
    text-overflow: ellipsis; /* Agrega puntos suspensivos al final del texto truncado */
    text-align: center;
}
#sucursal{
    overflow: hidden;
    white-space: nowrap; /* Evita el salto de línea */
    text-overflow: ellipsis; /* Agrega puntos suspensivos al final del texto truncado */
    text-align: center;

}
#direccion{
    text-align: center;
}
  /* Estilos personalizados para el hr */
  hr {
        border: 0; /* Elimina el borde predeterminado */
        border-top: 1px dashed #333; /* Estilo de guiones, grosor y color */
        margin: 10px 0; /* Margen superior e inferior */
    }
    #linea{
        padding-left: 15px;
    }
   
    </style>
</head>
<body>
    

    <div class="header">
                <span>FARMACIA PUERTO DEL ROSARIO</span>
       
    </div>
    <div id="sucursal">
        <span>SUCURSAL 1</span>
    </div>
    <div id="direccion">
        <span> AV.JUNIN N45 EDIF. SIN NOMBRE PISO PB DETOP. TIENDA EXTERIOR, Z .VILLA ADELA</span>
    </div>
    <hr>    
    <span>TICKET Nro.:</span><span style="padding-left: 15px">107198</span>
    <br>
    <span>FECHA:</span><span style="padding-left: 40px">13/05/2024</span> <span> HORA: 18:29:26</span>
    <br>
    <span>NIT/CI:</span><span style="padding-left: 44px">8325365</span>
    <br>
    <span>NOMBRE:</span><span style="padding-left: 29px">TARQUI</span>
    <hr>

    <table >
        <thead>
            <tr>
                <th><strong>CANT.</strong></th>
                <th><strong>DESCRIPCIÓN</strong></th>
                <th><strong>P.U.</strong></th>
                <th><strong>TOT.</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>COLFALGINA 1G/2ML INY. AMPOLLA</td>
                <td style="text-align: center;">10.70</td>
                <td style="text-align: center;">10.70</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>ACTRON 400 CAPSULAS</td>
                <td style="text-align: center;">2.00</td>
                <td style="text-align: center;">4.00</td>
            </tr>
        </tbody>
       
    </table>
    <hr>
    <span style="padding-left: 130px">IMPORTE TOTAL: BS.</span><span >14.70</span>
    <br>
    <span style="padding-left: 151px">DESCUENTO: BS.</span><span >0.00</span>
    <br>
    <span style="padding-left: 118px">IMPORTE A PAGAR: BS.</span><span >14.70</span>
    <br>
    <span>IMPORTE NO VALIDO PARA CRÈDITO FISCAL</span>
    <hr>
    <span style="padding-left: 114px">PAGO EB EFECTIVO: BS.</span><span >40.00</span>
    <br>
    <span style="padding-left: 173px">CAMBIO: BS.</span><span >25.30</span>
    <hr>
    <span>* CODIGO DE CONTROL: 107198</span>
    <br>
    <span>* Valido desde 13/05/2024 hasta 20/05/2024 en la misma tienda, términos y codiciones según politica de cambios y devoluiones</span>
    <br>
    <span>* Atención al cliente whatsapp 69910577</span>
    <br>
    <span>Usuario: JEANETTE MARLENE AG</span>
</body>
</html>
