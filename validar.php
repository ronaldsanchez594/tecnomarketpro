<?php
include('includes/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = mysqli_real_escape_string($conexion, trim($_POST['correo']));
    $clave = trim($_POST['clave']);

    // Buscar usuario
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo' LIMIT 1";
    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $u = mysqli_fetch_assoc($res);

        // Verificar contraseña
        if (password_verify($clave, $u['clave'])) {
            // Guardar sesión
            $_SESSION['id'] = $u['id'];
            $_SESSION['nombre'] = $u['nombre'];
            $_SESSION['rol'] = $u['rol'];
            $_SESSION['email'] = $u['correo'];

            // Redirección según rol
            switch ($u['rol']) {
                case 'admin':
                    header('Location: admin/index.php');
                    break;
                case 'vendedor':
                    header('Location: vendedor/index.php');
                    break;
                case 'cliente':
                default:
                    header('Location: cliente/index.php');
                    break;
            }
            exit;
        } else {
            $_SESSION['mensaje'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['mensaje'] = "El correo no está registrado.";
    }

    // Redirigir al login con mensaje
    header('Location: login.php');
    exit;
}

// Si se accede directamente al archivo sin formulario
header('Location: login.php');
exit;
?>
