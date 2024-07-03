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

</html>
