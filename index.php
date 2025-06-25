


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/stylebody.css">
</head>
<body>
    <?php
    include ('header.html');

    ?>

 

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="css/img/slider3.png" class="d-block w-100" alt="Slide 1">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
            <div class="carousel-item">
                <img src="css/img/slider6.jpg" class="d-block w-100" alt="Slide 2">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
            <div class="carousel-item">
                <img src="css/img/slider7.png" class="d-block w-100" alt="Slide 3">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
    <div class="contenido">
        <img src="css/img/logo.png" alt="logo">
        <div class="parrafo">
            <h3>Bienvenido</h3>
            <p>Gestiona tu flota vehicular de manera eficiente y segura. Nuestro software te permite:
                    -Realizar un seguimiento completo del mantenimiento de tus vehículos.
                    -Optimizar costos operativos mediante análisis detallados.
                <br>
                    -Acceso rápido a la documentación de cada vehículo.
                <br>
                ¡Simplifica la gestión de tu flota y aumenta la productividad con nuestra solución integral!
                <br>
            </p>
        </div>

    </div>
<?php
    include('footer.html');

?>

    
    
</body>
</html>