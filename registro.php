<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/conexion.php');

// Si el usuario ya está logueado, redirige
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'admin': header('Location: admin/index.php'); exit;
        case 'vendedor': header('Location: vendedor/index.php'); exit;
        case 'cliente': header('Location: cliente/index.php'); exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - TecnoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 🎨 VARIABLES CONSISTENTES - MISMO QUE LOGIN */
        :root {
            --primary: #0097e6;
            --secondary: #38788e;
            --accent: #fbc531;
            --dark: #2f3640;
            --light: #f5f6fa;
            --success: #4cd137;
            --danger: #e84118;
            --gray: #718093;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* 🎯 CONTENEDOR CENTRADO - MISMO TAMAÑO QUE LOGIN */
        .register-box {
            background: white;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px; /* MISMO ANCHO QUE LOGIN */
            animation: fadeInUp 0.8s ease;
        }

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

        /* 📝 ENCABEZADO - MISMO ESTILO QUE LOGIN */
        .form-header {
            text-align: center;
            margin-bottom: 2rem; /* Mismo margen que login */
        }

        .logo {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .form-header h2 {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .form-header p {
            color: var(--gray);
            font-size: 1rem;
        }

        /* 🚨 ALERTAS - MISMO ESTILO QUE LOGIN */
        .alert-error {
            background: #fee;
            border: 1px solid #f5c6cb;
            color: var(--danger);
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }

        .alert-error i {
            font-size: 1.1rem;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* 📱 FORMULARIO - MISMO ESTILO QUE LOGIN */
        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 15px 20px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light);
            appearance: none;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,151,230,0.1);
        }

        .input-group select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23718093' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1.1rem;
        }

        /* 🔘 BOTÓN - MISMO ESTILO QUE LOGIN */
        .btn-register {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-register:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,151,230,0.3);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* 🔗 ENLACES - MISMO ESTILO QUE LOGIN */
        .register-links {
            text-align: center;
            margin-top: 1.5rem; /* Mismo margen que login */
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .register-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .register-links a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: 'o';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 0 15px;
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* 📱 VALIDACIÓN DE CONTRASEÑA */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            display: none;
        }

        .strength-weak { color: var(--danger); }
        .strength-medium { color: var(--accent); }
        .strength-strong { color: var(--success); }

        /* 📱 RESPONSIVE - MISMO QUE LOGIN */
        @media (max-width: 480px) {
            .register-box {
                padding: 40px 30px;
                margin: 10px;
            }
            
            .form-header h2 {
                font-size: 1.7rem;
            }
            
            .logo {
                font-size: 2rem;
            }
        }

        /* ✨ EFECTO DE CARGA - MISMO QUE LOGIN */
        .btn-loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="register-box">
    <!-- ENCABEZADO -->
    <div class="form-header">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>Crear Cuenta</h2>
        <p>Únete a TecnoMarket y empieza a disfrutar de la mejor tecnología</p>
    </div>

    <!-- MENSAJES DE ERROR -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <form action="registrar_usuario.php" method="POST" autocomplete="off" id="registerForm">
        <div class="input-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="nombre" placeholder="Nombre completo" required>
        </div>

        <div class="input-group">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="clave" id="password" placeholder="Contraseña" required minlength="6">
            <div id="passwordStrength" class="password-strength"></div>
        </div>

        <div class="input-group">
            <i class="fas fa-user-tag input-icon"></i>
            <select name="rol" required>
                <option value="" disabled selected>Selecciona tu rol</option>
                <option value="cliente">Cliente</option>
                
            </select>
        </div>

        <button type="submit" class="btn-register" id="registerBtn">
            <i class="fas fa-user-plus"></i>
            Crear Cuenta
        </button>

        <div class="divider"></div>

        <div class="register-links">
            <a href="login.php">
                <i class="fas fa-sign-in-alt"></i> ¿Ya tienes cuenta? Inicia sesión aquí
            </a>
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');

    // Validación de contraseña en tiempo real
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = '';
        let strengthClass = '';

        if (password.length === 0) {
            passwordStrength.style.display = 'none';
            return;
        }

        if (password.length < 6) {
            strength = 'Contraseña débil';
            strengthClass = 'strength-weak';
        } else if (password.length < 8) {
            strength = 'Contraseña media';
            strengthClass = 'strength-medium';
        } else {
            strength = 'Contraseña fuerte';
            strengthClass = 'strength-strong';
        }

        passwordStrength.textContent = strength;
        passwordStrength.className = 'password-strength ' + strengthClass;
        passwordStrength.style.display = 'block';
    });

    // Animación de envío del formulario
    registerForm.addEventListener('submit', function(e) {
        // Validación adicional antes de enviar
        const password = passwordInput.value;
        if (password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            return;
        }

        // Animación de carga
        registerBtn.classList.add('btn-loading');
        registerBtn.innerHTML = '<i class="fas fa-spinner"></i> Creando cuenta...';
        
        // Simular envío
        setTimeout(() => {
            registerBtn.classList.remove('btn-loading');
            registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Crear Cuenta';
        }, 2000);
    });

    // Efecto de focus en inputs
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Validación de email básica
    const emailInput = document.querySelector('input[type="email"]');
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.style.borderColor = 'var(--danger)';
        } else {
            this.style.borderColor = '#e0e0e0';
        }
    });
});
</script>

</body>
</html>