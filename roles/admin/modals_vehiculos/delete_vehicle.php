<?php
  session_start();
  // Eliminar header('Content-Type: application/json')

  try {
      error_log("delete_vehicle.php started");
      require_once('../../conecct/conex.php');
      $db = new Database();
      $con = $db->conectar();

      if (!isset($_SESSION['documento'])) {
          error_log("No session documento");
          echo "No autorizado";
          exit;
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placa'])) {
          $placa = $_POST['placa'];
          error_log("Deleting vehicle with placa: $placa");
          error_log("POST data: " . print_r($_POST, true));
          
          $query = $con->prepare("DELETE FROM vehiculos WHERE placa = :placa");
          $query->bindParam(':placa', $placa, PDO::PARAM_STR);
          $result = $query->execute();
          
          if ($result) {
              error_log("Vehicle deleted for placa: $placa");
              echo "success: Vehículo eliminado";
          } else {
              error_log("No vehicle found to delete for placa: $placa");
              echo "error: No se encontró el vehículo";
          }
      } else {
          error_log("Invalid request or missing placa: " . print_r($_POST, true));
          echo "error: Solicitud inválida";
      }
  } catch (Exception $e) {
      error_log("Exception in delete_vehicle.php: " . $e->getMessage());
      echo "error: Error interno: " . $e->getMessage();
  }
  ?>