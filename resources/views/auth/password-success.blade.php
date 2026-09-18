
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">

    <title>Contraseña cambiada</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f4f6f9;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .contenedor {
            width: 100%;
            max-width: 450px;

            padding: 20px;
        }

        .card {
            background: #ffffff;

            border-radius: 12px;

            padding: 45px 35px;

            text-align: center;

            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .icono {
            width: 75px;
            height: 75px;

            margin: 0 auto 25px;

            border-radius: 50%;

            background: #e8f7ee;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #28a745;

            font-size: 42px;
            font-weight: bold;
        }

        h1 {
            margin: 0 0 15px;

            color: #212529;

            font-size: 25px;
        }

        p {
            margin: 0 auto 30px;

            color: #6c757d;

            font-size: 15px;

            line-height: 1.6;
        }

        .boton {
            display: inline-block;

            width: 100%;

            padding: 13px 20px;

            background: #007bff;

            color: #ffffff;

            text-decoration: none;

            border-radius: 6px;

            font-size: 15px;

            font-weight: 600;

            transition: 0.2s;
        }

        .boton:hover {
            background: #0069d9;
        }

        .seguridad {
            margin-top: 25px;

            color: #9aa0a6;

            font-size: 12px;
        }

    </style>

</head>


<body>


<div class="contenedor">

    <div class="card">


        <!-- ICONO -->

        <div class="icono">

            ✓

        </div>


        <!-- TITULO -->

        <h1>

            ¡Contraseña cambiada!

        </h1>


        <!-- MENSAJE -->

        <p>

            Su contraseña ha sido cambiada
            correctamente.

            <br>

            Ahora puede iniciar sesión
            utilizando su nueva contraseña.

        </p>


        <!-- BOTON -->

        <a href="{{ url('/') }}"
           class="boton">

            Volver al inicio de sesión

        </a>


        <!-- MENSAJE SEGURIDAD -->

        <div class="seguridad">

            Por seguridad, recuerde no compartir
            su contraseña con otras personas.

        </div>


    </div>

</div>


</body>

</html>

