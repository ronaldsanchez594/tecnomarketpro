<?php include('includes/conexion.php'); include('includes/header.php'); include('includes/email.php');
if(isset($_POST['enviar'])){
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo'");
    if($res && mysqli_num_rows($res)>0){
        $u = mysqli_fetch_assoc($res);
        $nueva = substr(md5(time()),0,8);
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        mysqli_query($conexion, "UPDATE usuarios SET clave='$hash' WHERE id=".$u['id']);
        $mensaje = "<p>Hola {$u['nombre']},</p><p>Tu nueva contraseña temporal es: <strong>{$nueva}</strong></p><p>Cámbiala al ingresar.</p>";
        $env = enviar_correo_simple($correo, 'Recuperación de contraseña - TecnoMarket', $mensaje);
        if($env) echo '<div class="container"><div class="card">Se envió un correo con la nueva contraseña.</div></div>';
        else echo '<div class="container"><div class="card">No se pudo enviar el correo (ver configura SMTP)</div></div>';
    } else {
        echo '<div class="container"><div class="card">Correo no registrado.</div></div>';
    }
}
?>
<div class="container"><div class="card login-box"><h2>Recuperar contraseña</h2><form method="POST" action="recuperar.php"><input type="email" name="correo" placeholder="Tu correo" required><button class="button" name="enviar" type="submit">Enviar nueva contraseña</button></form></div></div><?php include('includes/footer.php'); ?>