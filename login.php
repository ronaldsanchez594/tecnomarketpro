<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/conexion.php');

// Redirección si ya hay sesión activa
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
    <title>Iniciar Sesión - TecnoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 🎨 VARIABLES CONSISTENTES */
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

        /* 🎯 CONTENEDOR CENTRADO */
        .login-box {
            background: white;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
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

        /* 📝 ENCABEZADO */
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
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

        /* 🚨 ALERTAS */
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

        /* 📱 FORMULARIO */
        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .input-group input:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,151,230,0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1.1rem;
        }

        /* ✅ CHECKBOX */
        .remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
            cursor: pointer;
        }

        .remember-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-group label {
            color: var(--dark);
            cursor: pointer;
            font-size: 0.95rem;
        }

        /* 🔘 BOTÓN */
        .btn-login {
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

        .btn-login:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,151,230,0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* 🔗 ENLACES */
        .login-links {
            text-align: center;
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .login-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .login-links a:hover {
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

        /* 📱 RESPONSIVE */
        @media (max-width: 480px) {
            .login-box {
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

        /* ✨ EFECTO DE CARGA */
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

<div class="login-box">
    <!-- ENCABEZADO -->
    <div class="form-header">
        <div class="logo">
            <i class="fas fa-laptop"></i>
        </div>
        <h2>Iniciar Sesión</h2>
        <p>Ingresa tus credenciales para continuar</p>
    </div>

    <!-- MENSAJES DE ERROR -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <form action="validar.php" method="POST" autocomplete="off" id="loginForm">
        <div class="input-group">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="clave" placeholder="Contraseña" required>
        </div>

        <div class="remember-group">
            <input type="checkbox" name="recordar" id="recordar">
            <label for="recordar">Recordar mi sesión</label>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i>
            Iniciar Sesión
        </button>

        <div class="divider"></div>

        <div class="login-links">
            <a href="registro.php">
                <i class="fas fa-user-plus"></i> ¿No tienes cuenta? Regístrate aquí
            </a>
            <a href="recuperar.php">
                <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
            </a>
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    loginForm.addEventListener('submit', function(e) {
        // Animación de carga
        loginBtn.classList.add('btn-loading');
        loginBtn.innerHTML = '<i class="fas fa-spinner"></i> Iniciando sesión...';
        
        // Simular envío (esto se ejecutará antes de la redirección)
        setTimeout(() => {
            loginBtn.classList.remove('btn-loading');
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
        }, 2000);
    });

    // Efecto de focus en inputs
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});
</script>

</body>
</html>