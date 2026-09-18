
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">

    <title>{{ $detalles['title'] }}</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f4f6f8;
    font-family: Arial, Helvetica, sans-serif;
    color: #333333;
">

    <!-- CONTENEDOR GENERAL -->
    <table width="100%"
           cellpadding="0"
           cellspacing="0"
           border="0"
           style="
               width: 100%;
               background-color: #f4f6f8;
               padding: 40px 15px;
           ">

        <tr>

            <td align="center">


                <!-- TARJETA DEL CORREO -->
                <table width="600"
                       cellpadding="0"
                       cellspacing="0"
                       border="0"
                       style="
                           width: 100%;
                           max-width: 600px;
                           background-color: #ffffff;
                           border: 1px solid #e2e5e8;
                           border-radius: 8px;
                           overflow: hidden;
                       ">


                    <!-- ================================= -->
                    <!-- ENCABEZADO -->
                    <!-- ================================= -->

                    <tr>

                        <td align="center"
                            style="
                                background-color: #1f2937;
                                padding: 28px 30px;
                            ">

                            <div style="
                                color: #ffffff;
                                font-size: 22px;
                                font-weight: bold;
                                letter-spacing: 0.5px;
                            ">
                                ENERGOZ
                            </div>

                        </td>

                    </tr>


                    <!-- ================================= -->
                    <!-- CONTENIDO -->
                    <!-- ================================= -->

                    <tr>

                        <td style="
                            padding: 40px;
                        ">


                            <!-- TÍTULO -->

                            <h1 style="
                                margin: 0 0 22px 0;
                                padding: 0;
                                color: #1f2937;
                                font-size: 24px;
                                font-weight: 600;
                                line-height: 1.3;
                            ">

                                {{ $detalles['title'] }}

                            </h1>


                            <!-- MENSAJE -->

                            <p style="
                                margin: 0;
                                padding: 0;
                                color: #4b5563;
                                font-size: 15px;
                                line-height: 1.7;
                            ">

                                {{ $detalles['body'] }}

                            </p>


                            <!-- ================================= -->
                            <!-- CÓDIGO -->
                            <!-- ================================= -->

                            <table width="100%"
                                   cellpadding="0"
                                   cellspacing="0"
                                   border="0"
                                   style="
                                       margin-top: 30px;
                                       background-color: #f8fafc;
                                       border: 1px solid #e5e7eb;
                                       border-radius: 8px;
                                   ">

                                <tr>

                                    <td align="center"
                                        style="
                                            padding: 25px 20px;
                                        ">


                                        <div style="
                                            margin-bottom: 12px;
                                            color: #6b7280;
                                            font-size: 12px;
                                            font-weight: bold;
                                            letter-spacing: 1px;
                                        ">

                                            CÓDIGO DE RECUPERACIÓN

                                        </div>


                                        <div style="
                                            color: #111827;
                                            font-size: 28px;
                                            font-weight: bold;
                                            letter-spacing: 5px;
                                            line-height: 1.4;
                                        ">

                                            {{ $detalles['token'] }}

                                        </div>


                                        <div style="
                                            margin-top: 12px;
                                            color: #9ca3af;
                                            font-size: 12px;
                                        ">

                                            Cópielo y péguelo en la aplicación, ojo el codigo solo tiene validez de 15 minnutos.

                                        </div>

                                    </td>

                                </tr>

                            </table>


                            <!-- ================================= -->
                            <!-- AVISO DE SEGURIDAD -->
                            <!-- ================================= -->

                            <table width="100%"
                                   cellpadding="0"
                                   cellspacing="0"
                                   border="0"
                                   style="
                                       margin-top: 30px;
                                       background-color: #f9fafb;
                                       border-left: 4px solid #2563eb;
                                   ">

                                <tr>

                                    <td style="
                                        padding: 15px 18px;
                                    ">

                                        <div style="
                                            margin-bottom: 6px;
                                            color: #374151;
                                            font-size: 13px;
                                            font-weight: bold;
                                        ">

                                            Recomendación de seguridad

                                        </div>


                                        <div style="
                                            color: #6b7280;
                                            font-size: 13px;
                                            line-height: 1.6;
                                        ">

                                            No comparta este código con otras
                                            personas. El código está destinado
                                            exclusivamente para recuperar el
                                            acceso a su cuenta.

                                        </div>

                                    </td>

                                </tr>

                            </table>


                            <!-- ================================= -->
                            <!-- SI NO SOLICITÓ -->
                            <!-- ================================= -->

                            <p style="
                                margin: 30px 0 0 0;
                                color: #6b7280;
                                font-size: 13px;
                                line-height: 1.7;
                            ">

                                Si usted no solicitó la recuperación de su
                                contraseña, puede ignorar este mensaje.
                                Su cuenta permanecerá sin cambios.

                            </p>


                        </td>

                    </tr>


                    <!-- ================================= -->
                    <!-- PIE DEL CORREO -->
                    <!-- ================================= -->

                    <tr>

                        <td align="center"
                            style="
                                padding: 25px 30px;
                                background-color: #f9fafb;
                                border-top: 1px solid #e5e7eb;
                            ">


                            <div style="
                                color: #6b7280;
                                font-size: 12px;
                                line-height: 1.6;
                            ">

                                Este es un mensaje generado automáticamente.

                            </div>


                            <div style="
                                margin-top: 8px;
                                color: #9ca3af;
                                font-size: 11px;
                            ">

                                Por favor, no responda a este correo.

                            </div>


                            <div style="
                                margin-top: 12px;
                                color: #9ca3af;
                                font-size: 11px;
                            ">

                                © {{ date('Y') }} -
                                ENERGOZ.
                                Todos los derechos reservados.

                            </div>


                        </td>

                    </tr>


                </table>


                <!-- TEXTO FUERA DE LA TARJETA -->

                <div style="
                    max-width: 600px;
                    margin-top: 15px;
                    color: #9ca3af;
                    font-size: 11px;
                    text-align: center;
                    line-height: 1.5;
                ">

                    Este correo fue enviado como parte del proceso
                    de recuperación de acceso a su cuenta.

                </div>


            </td>

        </tr>

    </table>

</body>

</html>

