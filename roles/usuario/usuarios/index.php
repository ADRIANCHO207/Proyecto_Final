
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar SOAT</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            window.location.href = `delete.php?id=${id}`;
        }
    }
</script>
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="../logo.jpeg" alt="Logo" class="logo-redondo">
        <span class="empresa">Flotax AGC</span>
    </div>
    <div class="boton-inicio">
        <a href="../index.php" class="boton">Atras</a>
    </div>
    <div class="menu">
        <a href="index.php">Panel de control</a>
        <a href="registro_vehiculos.php">Registro de vehículos</a>
    </div>
    <div class="perfil">
        <img src="../perfil.jpg" alt="Usuario" class="imagen-usuario">
        <div class="info-usuario">
            <span>Nombres, Apellidos</span>
            <br>
            <span>Perfil Administrador</span>
        </div>
    </div>
</div>
<section id="servicios">
            <h2>Nuestros Usuarios</h2>
            <div class="galeria">
                <div class="fila">
                    <div class="cuadro">
                        <h4>USUARIO 1</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 2</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 3</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                </div>
                <div class="fila">
                    <div class="cuadro">
                        <h4>USUARIO 4</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 5</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 6</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                </div>
                <div class="fila">
                    <div class="cuadro">
                        <h4>USUARIO 7</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>  
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 8</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                    <div class="cuadro">
                        <h4>USUARIO 9</h4>
                        <img class="imagenes" src="../perfil.jpg" alt="">
                        <p class="parrafo"class="parrafo"></p>
                        <button >Editar</button>
                        <button >Eliminar</button>
                    </div>
                </div>
            </div>
        </section>