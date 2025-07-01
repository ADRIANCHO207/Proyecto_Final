<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Flotax AGC</title>
    <!-- Favicon del sitio -->
    <link rel="shortcut icon" href="css/img/Imagen_de_WhatsApp_2025-05-21_a_las_07.17-Photoroom__1_-removebg-preview.png">
    <!-- Estilos generales personalizados -->
    <link rel="stylesheet" href="css/stylos_generales.css">
    <!-- Fuente Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Estilos CSS personalizados y responsivos para la página "Sobre Nosotros" */
        * {
      margin: 0;
      padding: 0;
      font-family: "Inter", sans-serif;
      list-style: none;
      text-decoration: none;
      box-sizing: border-box;
    }

    body {
      line-height: 1.6;
      color: #333;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
    }

    /* Hero Section */
    .hero-section {
      position: relative;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      text-align: center;
      padding: 100px 20px;
      overflow: hidden;
    }

    .hero-section::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: 0 auto;
    }

    .hero-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      padding: 8px 20px;
      border-radius: 50px;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 20px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
      font-size: clamp(2.5rem, 5vw, 4rem);
      font-weight: 800;
      margin-bottom: 20px;
      background: linear-gradient(45deg, #fff, #e0e7ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-subtitle {
      font-size: clamp(1.1rem, 2vw, 1.5rem);
      opacity: 0.9;
      font-weight: 300;
    }

    /* Content Sections */
    .contenido {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 60px 0;
      padding: 0 20px;
    }

    /* Historia Section */
    .historia-section {
      background: transparent;
    }

    .content-card {
      background: white;
      padding: 60px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 900px;
      position: relative;
      overflow: hidden;
    }

    .content-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: #d32f2f;
    }

    /* Section Icons */
    .section-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 60px;
      background: #d32f2f;
      border-radius: 50%;
      color: white;
      margin-bottom: 20px;
    }

    .section-icon.green {
      background: #d32f2f;
    }

    .section-icon.purple {
      background: #d32f2f;
    }

    .section-icon.blue {
      background: #d32f2f;
    }

    /* Images */
    .imagen {
      margin: auto;
      width: 50%;
      height: 80%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-container {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      transform: rotate(-2deg);
      transition: all 0.3s ease;
    }

    .image-container:hover {
      transform: rotate(0deg) scale(1.02);
    }

    .image-container.purple {
      transform: rotate(2deg);
    }

    .image-container.blue {
      transform: rotate(-1deg);
    }

    .image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .image-container:hover img {
      transform: scale(1.1);
    }

    .image-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .image-container:hover .image-overlay {
      opacity: 1;
    }

    /* Info Sections */
    .info {
      margin-left: 40px;
      margin-right: 40px;
      width: 50%;
      text-align: left;
    }

    .section-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .info h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #1a202c;
      margin: 0;
    }

    .info p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #4a5568;
      margin-bottom: 25px;
    }

    /* Feature Tags */
    .feature-tags {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .tag {
      background: #d32f2f;
      color: white;
      padding: 8px 16px;
      border-radius: 25px;
      font-size: 0.9rem;
      font-weight: 500;
    }

    /* Highlight Boxes */
    .highlight-box {
      background: #f7fafc;
      padding: 25px;
      border-radius: 15px;
      border-left: 4px solid #d32f2f;
      margin-top: 25px;
    }

    .highlight-box.blue {
      border-left-color: #d32f2f;
    }

    .highlight-box h3 {
      color: #2d3748;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .highlight-box ul {
      list-style: none;
      padding: 0;
    }

    .highlight-box li {
      padding: 5px 0;
      color: #4a5568;
      position: relative;
      padding-left: 20px;
    }

    .highlight-box li::before {
      content: "•";
      color: #e53e3e;
      font-weight: bold;
      position: absolute;
      left: 0;
    }

    /* CTA Section */
    .cta-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      text-align: center;
      padding: 80px 20px;
      margin-top: 80px;
    }

    .cta-content {
      max-width: 600px;
      margin: 0 auto;
    }

    .cta-content h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .cta-content p {
      font-size: 1.2rem;
      opacity: 0.9;
      margin-bottom: 40px;
    }

    .cta-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-primary,
    .btn-secondary {
      padding: 15px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .btn-primary {
      background: white;
      color: #667eea;
    }

    .btn-primary:hover {
      background: #f7fafc;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
      background: transparent;
      color: white;
      border: 2px solid white;
    }

    .btn-secondary:hover {
      background: white;
      color: #d32f2f;
      transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .hero-section {
        padding: 60px 20px;
      }

      .contenido {
        flex-direction: column;
        text-align: center;
        margin: 40px 0;
      }

      .imagen,
      .info {
        width: 90%;
        margin: 20px auto;
      }

      .content-card {
        padding: 40px 30px;
        margin: 20px;
      }

      .section-header {
        justify-content: center;
      }

      .info h2 {
        font-size: 2rem;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn-primary,
      .btn-secondary {
        width: 200px;
      }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
      .contenido {
        flex-direction: column;
        align-items: center;
      }

      .imagen,
      .info {
        width: 80%;
        margin: 20px auto;
      }
    }

    @media (min-width: 1025px) {
      .contenido {
        flex-direction: row;
        max-width: 1200px;
        margin: 60px auto;
      }

      .imagen {
        width: 50%;
      }

      .info {
        width: 50%;
      }
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .content-card,
    .info,
    .imagen {
      animation: fadeInUp 0.6s ease-out;
    }

    /* Existing contact styles (keeping your original contact styles) */
    h1 {
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: 900;
    }

    .contenido_info {
      display: flex;
      justify-content: center;
      width: 100%;
    }

    .informa {
      width: 50%;
      height: 100%;
    }

    .informa h2 {
      padding-left: 10px;
      font-weight: 700;
      font-size: 25px;
    }

    .informa p {
      text-align: justify;
      padding-left: 10px;
    }

    .informa a {
      padding-left: 10px;
      text-decoration: none;
      color: black;
      transition: 1s;
    }

    .informa a:hover {
      text-decoration: underline;
      color: #d32f2f;
    }

    .informa .img2 {
      padding-left: 10px;
      width: 100%;
    }

    .informa img {
      width: 100%;
      border-radius: 20px;
    }

    .contenido_form {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 50%;
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .formulario {
      width: 100%;
      max-width: 450px;
      background: #333;
      padding: 50px 60px 70px;
      text-align: center;
      border-radius: 10%;
    }

    .form label {
      color: #fff;
      display: flex;
    }

    .form input {
      border: none;
      outline: none;
      width: 100%;
      padding: 5px;
      border-radius: 10px;
    }

    .form .input_field {
      padding-top: 10px;
    }

    .form .input_fiel {
      padding-top: 10px;
    }

    .form .input_fiel .input_mensa {
      outline: none;
      border-radius: 10px;
      width: 100%;
      height: 250px;
    }

    .boton {
      padding-top: 10px;
    }

    .boton button {
      background: #d32f2f;
      padding: 5px 30px;
      border: none;
      transition: .5s;
    }

    .boton button:hover {
      background: #fff;
      color: #d32f2f;
    }

    .warnings {
      width: 200px;
      font-size: 10px;
      text-align: center;
      margin: auto;
      color: #fff;
      padding-top: 10px;
      opacity: 0;
    }

    /* Contact form responsive styles */
    @media (max-width: 767px) {
      .contenido_info {
        flex-direction: column;
        align-items: center;
        padding: 10px;
      }

      .informa,
      .contenido_form {
        width: 100%;
      }

      .formulario {
        width: 90%;
        max-width: none;
        background: #333;
        padding: 30px 20px;
        border-radius: 20px;
        box-sizing: border-box;
      }

      .form input,
      .form .input_fiel .input_mensa {
        width: 100%;
      }

      .form label {
        font-size: 14px;
      }

      .boton button {
        width: 100%;
      }
    }

    @media (min-width: 768px) and (max-width: 1199px) {
      .contenido_info {
        flex-direction: column;
        align-items: center;
      }

      .informa,
      .contenido_form {
        width: 80%;
      }

      .informa h2 {
        font-size: 24px;
      }

      .formulario {
        padding: 40px;
        border-radius: 10%;
      }

      .form .input_fiel .input_mensa {
        height: 200px;
      }
    }

    @media (min-width: 1200px) {
      .contenido_info {
        flex-direction: row;
        justify-content: center;
        gap: 30px;
        padding: 20px;
      }

      .informa,
      .contenido_form {
        width: 45%;
      }

      .formulario {
        padding: 60px;
      }

      .form .input_fiel .input_mensa {
        height: 250px;
      }

      .informa h2 {
        font-size: 28px;
      }

      .informa p {
        font-size: 16px;
      }
    }

    </style>
</head>
<body>
<?php
    // Incluye el encabezado del sitio (barra de navegación, logo, etc.)
    include ('header.html');
?>

 
    <!-- Sección de Historia de la empresa -->
    <div class="contenido historia-section">
        <div class="content-card">
            <div class="section-icon">
                <!-- Icono SVG representando historia/tiempo -->
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M12 7v5l4 2"/>
                </svg>
            </div>
            <h2>Nuestra Historia</h2>
            <p>
                Flotax AGC nació de la necesidad de mejorar la eficiencia en la gestión de flotas vehiculares.
                Fundada por un equipo de expertos en logística y tecnología, nuestra empresa ha crecido 
                rápidamente gracias a nuestro compromiso con la excelencia y la innovación. 
                A lo largo de los años, hemos alcanzado importantes hitos que han consolidado 
                nuestra posición como líderes en el sector.
            </p>
        </div>
    </div>

    <!-- Sección Sobre Nosotros: misión de la empresa y valores principales -->
    <div class="contenido about-section">
        <div class="info">
            <div class="section-header">
                <div class="section-icon green">
                    <!-- Icono SVG representando innovación/tecnología -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l8-4v18"/>
                        <path d="M19 21V11l-6-4"/>
                    </svg>
                </div>
                <h2>Sobre Nosotros</h2>
            </div>
            <p>
                En Flotax AGC, nos dedicamos a revolucionar la gestión de flotas vehiculares mediante 
                soluciones tecnológicas innovadoras y eficientes. Hemos trabajado incansablemente para 
                proporcionar a nuestros clientes herramientas avanzadas que optimicen la operación, 
                seguridad y rendimiento de sus vehículos.
            </p>
            <div class="feature-tags">
                <span class="tag">Innovación</span>
                <span class="tag">Tecnología</span>
                <span class="tag">Eficiencia</span>
            </div>
        </div>
        
        <div class="imagen">
            <div class="image-container">
                <!-- Imagen ilustrativa de la empresa -->
                <img src="css/img/ima2.jpg" alt="Carros en movimiento">
                <div class="image-overlay"></div>
            </div>
        </div>
    </div>

    <!-- Sección Misión: objetivos y pilares de la empresa -->
    <div class="contenido mission-section">
        <div class="imagen">
            <div class="image-container purple">
                <!-- Imagen relacionada con la misión -->
                <img src="css/img/imasobrenosotros.jpg" alt="Gestión de flotas">
                <div class="image-overlay"></div>
            </div>
        </div>
        <div class="info">
            <div class="section-header">
                <div class="section-icon purple">
                    <!-- Icono SVG de misión/objetivo cumplido -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 12l2 2 4-4"/>
                    </svg>
                </div>
                <h2>Misión</h2>
            </div>
            <p>
                Brindar una solución integral y eficiente para la gestión de flotas vehiculares, 
                que permita a las empresas optimizar el control de mantenimiento, documentación y 
                operaciones logísticas, promoviendo la seguridad, 
                el cumplimiento normativo y la sostenibilidad en el manejo de sus vehículos.
            </p>
            <div class="highlight-box">
                <h3>Nuestros Pilares</h3>
                <ul>
                    <li>Seguridad y cumplimiento normativo</li>
                    <li>Optimización de operaciones</li>
                    <li>Sostenibilidad ambiental</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Sección Visión: proyección y futuro de la empresa -->
    <div class="contenido vision-section">
        <div class="info">
            <div class="section-header">
                <div class="section-icon blue">
                    <!-- Icono SVG de visión/futuro -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <h2>Visión</h2>
            </div>
            <p>
                Convertirnos en el software líder en gestión de flotas vehiculares, reconocido por nuestra 
                innovación tecnológica, facilidad de uso y capacidad de adaptarnos a las necesidades de 
                empresas de todos los tamaños, fomentando 
                la eficiencia operativa y contribuyendo al desarrollo de un transporte más organizado y seguro.
            </p>
            <div class="highlight-box blue">
                <h3>Hacia el Futuro</h3>
                <p>Liderando la transformación digital del transporte con soluciones que se adaptan a empresas de todos los tamaños.</p>
            </div>
        </div>

        <div class="imagen">
            <div class="image-container blue">
                <!-- Imagen ilustrativa de visión/futuro -->
                <img src="css/img/slider.jpg" alt="Visión de futuro">
                <div class="image-overlay"></div>
            </div>
        </div>
    </div>

   

<?php
    // Incluye el pie de página del sitio
    include ('footer.html');
?>
</body>
</html>
