<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario suspendido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            padding-top: 50px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #C6007E;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 500px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
        }

        p {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Farmacia Puerto del Rosario" class="logo">
    
        <p>Tu cuenta ha sido suspendida del usuario {{$baneo}}. Por favor, contacta al administrador para más información.</p>
    </div>
</body>
</html>
