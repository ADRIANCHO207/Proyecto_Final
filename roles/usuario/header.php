<div class="header">
    <div class="logo">
        <a href="/proyecto/roles/usuario/index.php">
            <img src="/proyecto/roles/usuario/css/img/logo_sinfondo.png" alt="Logo">
            <span class="empresa">Flotax AGC</span>
        </a>
    </div>
    <div class="menu">
        <a href="/proyecto/roles/usuario/index.php" class="boton">Inicio</a>
        <a href="/proyecto/roles/usuario/vehiculos/registrar_vehiculos.php" class="boton">Registrar Vehículo</a>
        <div class="dropdown">
      <a href="#" class="boton">Historiales ▾</a>
      <div class="dropdown-content">
        <a href="/proyecto/roles/usuario/historiales/ver_soat.php">Historial de SOAT</a>
        <a href="/proyecto/roles/usuario/historiales/ver_tecnomecanica.php">Historial de Tecnomecánica</a>
        <a href="/proyecto/roles/usuario/historiales/ver_licencia.php">Historial de Licencia de Conducción</a>
        <a href="/proyecto/roles/usuario/historiales/ver_llantas.php">Historial de Llantas</a>
        <a href="/proyecto/roles/usuario/historiales/ver_mantenimiento.php">Historial de Mantenimiento</a>
      </div>
    </div> 
    
  </div>

    <div class="perfil" onclick="openModal()">
        <img src="<?= $_SESSION['foto_perfil'] ?>" alt="Foto de perfil" class="imagen-usuario">
 
        
        <div class="info-usuario">
            <span><?php echo htmlspecialchars($nombre_completo); ?></span>
            <span>Perfil Usuario</span>
        </div>
    </div>
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <button class="close" onclick="closeModal()">Cerrar</button>
            <h2>Información del Usuario</h2>
            <?php
            // Usar la misma lógica que en el header: simplemente mostrar la imagen con cache-busting
            $imagePath = htmlspecialchars($foto_perfil) . '?v=' . time();
            ?>
            <img src="<?php echo $imagePath; ?>" alt="Foto de Perfil" class="usu_imagen" style="max-width: 100px; height: 100px;">
            <?php if ($foto_perfil === '/proyecto/roles/usuario/css/img/perfil.jpg'): ?>
            <?php endif; ?>
            <?php
            $user_query = $con->prepare("SELECT documento, nombre_completo, email, telefono FROM usuarios WHERE documento = :documento");
            $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
            $user_query->execute();
            $user = $user_query->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><strong>Documento:</strong> <?php echo htmlspecialchars($user['documento']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre_completo']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono']); ?></p>
            <form action="/proyecto/roles/usuario/actualizar_foto.php" method="post" enctype="multipart/form-data">
                <label for="foto_perfil">Cambiar Foto de Perfil:</label>
                <p class="upload-instructions">Formatos: JPEG, PNG, GIF. Máximo 5MB. Recomendado: 512x512 píxeles.</p>
            <div class="input-file-custom">
                <button class="input-file-btn">
                    <i class="bi bi-cloud-upload"></i> Elegir archivo
                </button>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/jpeg,image/png,image/gif">
                </div>
                <br>
                <button type="submit" class="boton">Actualizar Foto</button>
            </form>
            <form action="/proyecto/roles/usuario/actualizar_foto.php" method="post">
                <input type="hidden" name="reset_image" value="1">
                <button type="submit" class="boton">Borrar Imagen</button>
            </form>
        </div>
    </div>

    <script>
    function openModal() {
        document.getElementById('profileModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('profileModal').style.display = 'none';
    }
    </script>
</div>

<div class="sidebar">
    <a href="/proyecto/includes/salir.php" class="logout" title="Cerrar Sesión">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</div>

<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #ffffff, #f1f1f1);
        padding: 20px 40px;
        border-bottom: 3px solid #d32f2f;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: scale(1.05);
    }

    .logo img {
        width: 75px;
        height: 70px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .empresa {
        font-size: 32px;
        font-weight: 700;
        color: #d32f2f;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .logo a{
        text-decoration: none;
        text-align:center;
        display: flex;
        align-items: center;
    }

    .menu {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu .boton {
        background: linear-gradient(135deg, #d32f2f, #b71c1c);
        color: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: solid 3px #d32f2f;
        transition: transform 0.3s ease;
    }
    

    .menu .boton:hover {
        background: transparent;
        border: solid 3px #d32f2f;
        transform: scale(1.05);
        color: #333;
    }

    .perfil {
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .perfil:hover {
        transform: scale(1.05);
    }

    .imagen-usuario {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        margin-right: 5px;
        border: 2px solid #d32f2f;
        object-fit: cover;
    }

    .info-usuario {
        text-align: right;
    }

    .info-usuario span {
        display: block;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }

    .info-usuario span:last-child {
        font-size: 14px;
        font-weight: 400;
        color: #666;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 650px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .modal-content h2 {
        color: #d32f2f;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .modal-content p {
        margin: 5px 0;
        font-size: 16px;
        color: #333;
    }

    .modal-content p strong {
        color: #d32f2f;
    }

    .modal-content form {
        margin-top: 6px;
    }

    .modal-content label{
        color: #d32f2f;
        font-weight: 600;
    }

    .modal-content input[type="file"] {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        width: 100%;
        font-size: 16px;
    }

    .input-file-custom {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .input-file-custom input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        height: 100%;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
    }

    .input-file-btn {
        background-color: #d32f2f;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        border: none;
        width: 100%;
        justify-content: center;
        transition: background-color 0.3s ease;
    }


    .input-file-custom:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(211, 47, 47, 0.5);
    }



    .modal-content .boton {
        background: linear-gradient(135deg, #d32f2f, #b71c1c);
        color: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: solid 3px #d32f2f;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .modal-content .boton:hover {
        background: transparent;
        border: solid 3px #d32f2f;
        transform: scale(1.05);
        color: #333;
        box-shadow: 0 6px 15px rgba(211, 47, 47, 0.5);
    }

    .usu_imagen {
        width: 100px;
        border: solid 3px #d32f2f;
        display: block;
        border-radius: 50%;
        margin-left: auto;
        margin-right: auto;
        object-fit: cover;
    }

    .modal-content .close {
        position: absolute;
        bottom: 20px;
        right: 15px;
        padding: 8px 15px;
        background: #333;
        color: #fff;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .modal-content .close:hover {
        transform: scale(1.05);
    }
     .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Estilo del enlace principal */
    .boton {
      background-color:rgb(255, 255, 255);
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      display: inline-block;
    }

    /* Submenú oculto */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 180px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
    }

    /* Enlaces dentro del submenú */
    .dropdown-content a {
      color: black;
      padding: 10px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    /* Mostrar submenú al pasar el mouse por el enlace principal */
    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown:hover .boton {
      background-color:rgb(255, 255, 255);
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 10;
    }

    .logout {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #d32f2f, #b71c1c);
        color: #fff;
        border-radius: 50%;
        text-decoration: none;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .logout:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(211, 47, 47, 0.5);
    }

    .logout::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        right: 0;
        background-color: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .logout:hover::after {
        opacity: 1;
        visibility: visible;
    }
        
</style>