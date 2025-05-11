<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container, .content, .header, .title {
            box-sizing: border-box;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            text-align: center;
            border: 1px solid #000;
            padding: 20px;
            height: 1000px; /* Ajusta esta altura según sea necesario */
            box-sizing: border-box;
            position: relative;
            page-break-inside: avoid; /* Evitar que el contenedor se divida entre dos páginas */
            overflow: hidden; /* Evita desbordamiento */
        }
        @page {
            size: A4;
            margin: 0; /* Ajusta el margen si es necesario */
        }
        .logo {
            position: absolute;
            width: 30%;
            left: 5px;
            top: 5px;
        }
        .title {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            font-size: 20px;
        }
        .content {
            text-align: left;
            margin: 20px 0;
            height: 550px;
        }
        .firma {
            position: absolute;
            width: 75%;
            left: 420px;
            bottom: 450px;
        }
        .firma img{
            width: 90vw;
        }
        .content p {
            word-wrap: break-word; /* O usa overflow-wrap: break-word; en su lugar */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <!-- Aquí puedes agregar tu logo -->
                <img src="http://sage.larioja.edu.ar/img/logo_gob_lr.jpg" alt="Logo" class="logo">
            </div>
            <div class="title">
                REGISTRO PROVINCIAL DE CERTIFICADOS Y TITULOS<br>
                PROVINCIA DE LA RIOJA
            </div>
        </div>
        <hr>
        <div class="content">
            <p>Se hace constar a efectos de su presentacion ante quien corresponda que:</p>
            <p><strong>{{ $datos['name'] }}</strong></p>
            <p><strong>DNI: {{ $datos['dni'] }}</strong> <span class="spacer"></span><strong>ha registrado el dia:</strong> {{ $datos['registration_date'] }}</p>
            <p><strong>Bajo el N&deg; de registro:</strong> {{ $datos['registration_number'] }}</p>
            <br><br>

            <p><strong>El {{ $datos['tipoOperacion']}}:</strong> {{ $datos['title'] }}</p>
            <p><strong>Otorgado por:</strong> {{ $datos['institution'] }}</p>
            <p><strong>Fecha de Egreso:</strong> {{ $datos['graduation_date'] }}</p>
            <hr>
            <p><strong>URL Titulo Digital:</strong> <a href="{{ $datos['URL_doc'] }}" target="_blank" rel="noopener noreferrer">VER INFORMACIÓN DEL TITULO/CERTIFICADO DIGITAL</p>
            <hr>
            <p><strong>URL Documento Extra:</strong> <a href="{{ $datos['URL_doc2'] }}" target="_blank" rel="noopener noreferrer">VER INFORMACIÓN DEL TITULO/CERTIFICADO DIGITAL EXTRA</p>
            
        </div>
        <br>
        <div class="firma">
            <!-- Aquí puedes agregar tu logo -->
            <img src="http://sage.larioja.edu.ar/storage/firma.jpg" alt="Logo" class="logo">
        </div>
    </div>
</body>
</html>
