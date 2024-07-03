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
        <span>{{$nombre_negocio}}</span>
    </div>
    <div id="direccion">
        <span>{{$direccionMayusculas}}</span>
    </div>
    <hr>    
    <span>TICKET Nro.:</span><span style="padding-left: 15px">{{$nuevoComprobante}}</span>
    <br>
    <span>FECHA:</span><span style="padding-left: 40px">{{$fecha}}</span> <span>HORA: {{$hora}}</span>
    <br>
    <span>NIT/CI:</span><span style="padding-left: 44px">{{$num_documento}}</span>
    <br>
    <span>NOMBRE:</span><span style="padding-left: 29px">{{$nom_a_facturar}}</span>
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
            @foreach ($array_Z as $item)
            <tr>
                <td style="text-align:center">{{ $item['cant'] }}</td>
                <td>{{ $item['descrip'] }}</td>
                <td style="text-align: center;">{{ $item['p_u'] }}</td>
                <td style="text-align: center;">{{ $item['cant'] * $item['p_u'] }}</td>
           
            </tr>
            @endforeach
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
    <span>* CODIGO DE CONTROL: {{$nuevoComprobante}}</span>
    <br>
    <span>* Valido desde {{$fecha}} hasta {{$fechaMas7Dias}}</span>
    <br>
    <span>* Ticket para el cambio valido hasta {{$fecha}} en la misma tienda, términos y codiciones según politica de cambios y devoluiones</span>
    <br>
    <span>* Atención al cliente whatsapp {{$numero_referencia}}</span>
    <br>
    <span>Usuario: {{$nombreCompleto_1}}</span>
</body>
</html>
