<!-- auto_logout_modal.php -->
<div id="modalInactividad" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: none; backdrop-filter:blur(4px); z-index:9999; display:flex; align-items:center; justify-content:center;">
    <div id="apa" style="display:none;background: rgba(255, 255, 255, 0.95); padding: 25px; border-radius: 12px; text-align: center; max-width: 320px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.3); font-family: 'Poppins', sans-serif;">
        <h3 class="titumo" style="margin-bottom: 10px; font-weight: 600;">¿Sigues ahí?</h3>
        <p class="pmo" style="margin-bottom: 20px; font-size: 15px;">Por inactividad, la sesión se cerrará en <span id="tiempoRestante" style="font-weight: bold;">10</span> segundos.</p>
        <button onclick="cancelarCierre()" class="btn-grad">Seguir aquí</button>
    </div>
</div>
<style>
    .btn-grad {
        padding: 10px 20px;
        background:  #0072ff;
        background-size: 600% 600%;
        border: none;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 0 15px rgba(0, 114, 255, 0.4);
        animation: gradientMove 5s ease infinite;
        transition: transform 0.2s;
    }

    .btn-grad:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0, 114, 255, 0.7);
    }
</style>
<script>
    let tiempoInactividad = 240000; // 4 minutos
    let advertenciaTiempo = 10000;  // Mostrar advertencia 10 segundos antes
    let temporizadorInactividad;
    let temporizadorAdvertencia;
    let tiempoRestante = 10;
    let cuentaRegresiva;

    function reiniciarTemporizador() {
        document.getElementById("modalInactividad").style.display = "none";
        clearTimeout(temporizadorInactividad);
        clearTimeout(temporizadorAdvertencia);
        cerrarModal();

        temporizadorAdvertencia = setTimeout(() => {
            mostrarModal();
        }, tiempoInactividad - advertenciaTiempo);

        temporizadorInactividad = setTimeout(() => {
            window.location.href = "/Proyecto/includes/salir.php";
        }, tiempoInactividad);
    }

    function mostrarModal() {
        document.getElementById("modalInactividad").style.background = "rgba(0,0,0,0.5)";
        document.getElementById("modalInactividad").style.display = "flex";
        document.getElementById("apa").style.display = "block";
        tiempoRestante = 10;
        document.getElementById("tiempoRestante").textContent = tiempoRestante;
        cuentaRegresiva = setInterval(() => {
            tiempoRestante--;
            document.getElementById("tiempoRestante").textContent = tiempoRestante;
            if (tiempoRestante <= 0) {
                clearInterval(cuentaRegresiva);
            }
        }, 1000);
    }

    function cerrarModal() {
        document.getElementById("modalInactividad").style.display = "none";
        clearInterval(cuentaRegresiva);
    }

    function cancelarCierre() {
        reiniciarTemporizador();
    }

    window.onload = reiniciarTemporizador;
    document.onmousemove = reiniciarTemporizador;
    document.onkeypress = reiniciarTemporizador;
    document.onscroll = reiniciarTemporizador;
    document.onclick = reiniciarTemporizador;
</script>
