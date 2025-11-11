<?php
include('includes/conexion.php');
if (session_status() === PHP_SESSION_NONE) session_start();

// 🛒 Verificar carrito vacío
if (empty($_SESSION['carrito'])) {
    include('includes/header.php');
    echo '<div class="container"><div class="card">🛒 Tu carrito está vacío. <a href="productos.php">Volver a productos</a></div></div>';
    include('includes/footer.php');
    exit;
}

// 💰 Calcular total del carrito
$total = 0;
$productos_carrito = [];
foreach ($_SESSION['carrito'] as $item) {
    $r = mysqli_query($conexion, "SELECT * FROM productos WHERE id=" . intval($item['id']));
    $p = mysqli_fetch_assoc($r);
    if ($p) {
        $subtotal = $p['precio'] * $item['cantidad'];
        $total += $subtotal;
        $productos_carrito[] = $p;
    }
}

// 🧾 Procesar pago
if (isset($_POST['procesar_pago'])) {
    if (!isset($_SESSION['id'])) {
        echo '<script>alert("⚠️ Debes iniciar sesión para continuar.");window.location="login.php";</script>';
        exit;
    }

    $id_usuario = intval($_SESSION['id']);
    
    // Datos de facturación
    $tipo_documento = mysqli_real_escape_string($conexion, $_POST['tipo_documento']);
    $numero_documento = mysqli_real_escape_string($conexion, $_POST['numero_documento']);
    $nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    
    // Datos de envío
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $departamento = mysqli_real_escape_string($conexion, $_POST['departamento']);
    $provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
    $distrito = mysqli_real_escape_string($conexion, $_POST['distrito']);
    $referencia = mysqli_real_escape_string($conexion, $_POST['referencia']);
    
    // Método de pago y envío
    $metodo_pago = mysqli_real_escape_string($conexion, $_POST['metodo_pago']);
    $metodo_envio = mysqli_real_escape_string($conexion, $_POST['metodo_envio']);
    
   
    
    // ✅ CORREGIDO: Envío gratis, no hay costo de envío
    $total_final = $total;
    
    // ✅ CORREGIDO: TODOS LOS PAGOS SON "pagado"
    $status = 'pagado'; // Siempre pagado para todos los métodos
    
    // ✅ SOLUCIÓN SEGURA - SIN CAMPOS DE TARJETA
// ✅ USA LA COLUMNA 'fecha' QUE YA EXISTE EN TU TABLA
mysqli_query($conexion, "
    INSERT INTO ventas (id_usuario, total, metodo_pago, metodo_envio, 
                       tipo_documento, numero_documento, nombre_completo, telefono, email,
                       direccion, departamento, provincia, distrito, referencia, status, fecha)
    VALUES ($id_usuario, $total, '$metodo_pago', '$metodo_envio',
            '$tipo_documento', '$numero_documento', '$nombre_completo', '$telefono', '$email',
            '$direccion', '$departamento', '$provincia', '$distrito', '$referencia', '$status', NOW())
");
    
    $id_venta = mysqli_insert_id($conexion);

    // Insertar detalles de venta y actualizar stock
    foreach ($_SESSION['carrito'] as $item) {
        $r = mysqli_query($conexion, "SELECT * FROM productos WHERE id=" . intval($item['id']));
        $p = mysqli_fetch_assoc($r);
        if (!$p) continue;
        
        $cantidad = min($item['cantidad'], $p['stock']);
        $subtotal = $p['precio'] * $cantidad;

        mysqli_query($conexion, "
            INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal)
            VALUES ($id_venta, {$p['id']}, $cantidad, {$p['precio']}, $subtotal)
        ");
        
        mysqli_query($conexion, "UPDATE productos SET stock = stock - $cantidad WHERE id={$p['id']}");
    }

    // Limpiar carrito
    $_SESSION['carrito'] = [];
    
    // Redirigir a la boleta
    header("Location: cliente/detalle_boleta.php?id=$id_venta");
    exit;
}

include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago - TecnoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 🎨 VARIABLES */
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

        /* 🎯 ESTILOS GENERALES */
        .pago-section {
            padding: 3rem 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--dark);
            position: relative;
            font-weight: 700;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--accent);
            margin: 10px auto;
            border-radius: 2px;
        }

        /* 📦 LAYOUT PRINCIPAL */
        .pago-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* 📋 FORMULARIO */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .section-title-form {
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 600;
            margin: 0;
        }

        /* 📝 CAMPOS DEL FORMULARIO */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,151,230,0.1);
            background: white;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23718093' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        /* 💳 TARJETA DE CRÉDITO */
        .tarjeta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            border-radius: 15px;
            color: white;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .tarjeta-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .tarjeta-chip {
            width: 40px;
            height: 30px;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            border-radius: 5px;
            position: relative;
        }

        .tarjeta-chip::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 1px solid rgba(0,0,0,0.3);
            border-radius: 3px;
        }

        .tarjeta-logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .tarjeta-numero {
            font-size: 1.3rem;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            font-family: 'Courier New', monospace;
        }

        .tarjeta-datos {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .tarjeta-datos span {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .tarjeta-campos {
            margin-top: 1.5rem;
        }

        .tarjeta-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }

        /* 💰 RESUMEN DEL PEDIDO */
        .resumen-pedido {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .resumen-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .resumen-header h3 {
            color: var(--dark);
            margin: 0 0 1rem 0;
            font-size: 1.5rem;
        }

        .total-pedido {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0;
        }

        .productos-resumen {
            margin-bottom: 1.5rem;
        }

        .producto-resumen {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .producto-resumen:last-child {
            border-bottom: none;
        }

        .producto-nombre {
            color: var(--dark);
            font-weight: 500;
        }

        .producto-precio {
            color: var(--primary);
            font-weight: 600;
        }

        .detalles-pedido {
            margin-bottom: 2rem;
        }

        .detalle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detalle-item:last-child {
            border-bottom: none;
        }

        .detalle-label {
            color: var(--gray);
        }

        .detalle-valor {
            font-weight: 600;
            color: var(--dark);
        }

        .total-item {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* 🔘 BOTÓN DE PAGO */
        .btn-pagar {
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
            gap: 0.8rem;
        }

        .btn-pagar:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,151,230,0.3);
        }

        .btn-pagar:disabled {
            background: var(--gray);
            cursor: not-allowed;
            transform: none;
        }

        /* ✅ PAGO EXITOSO */
        .success-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success);
            margin-bottom: 1.5rem;
        }

        .success-title {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .success-details {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        /* 📱 QR YAPE */
        .qr-section {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            margin-top: 2rem;
        }

        .qr-image {
            max-width: 250px;
            border-radius: 15px;
            margin: 1rem 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 968px) {
            .pago-container {
                grid-template-columns: 1fr;
            }
            
            .resumen-pedido {
                position: static;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .tarjeta-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .form-container, .resumen-pedido {
                padding: 1.5rem;
            }
            
            .total-pedido {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .pago-section {
                padding: 2rem 0;
            }
            
            .container {
                padding: 0 15px;
            }
            
            .success-container {
                padding: 2rem 1.5rem;
            }
        }

        /* ✨ ANIMACIONES */
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

        .fade-in {
            animation: fadeInUp 0.6s ease both;
        }
    </style>
</head>
<body>

<section class="pago-section">
    <div class="container">
        <h2 class="section-title">💳 Proceso de Pago</h2>

        <?php if (isset($_GET['success'])): 
            $id_venta = intval($_GET['id_venta']);
            $total_final = floatval($_GET['total']);
            $metodo = $_GET['success'] === 'yape' ? 'Yape' : ucfirst($_GET['metodo'] ?? 'Contra entrega');
        ?>
            <!-- ✅ PAGO EXITOSO -->
            <div class="success-container fade-in">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="success-title">¡Pago Procesado Exitosamente!</h2>
                
                <div class="success-details">
                    <div class="detail-row">
                        <span><strong>Número de Pedido:</strong></span>
                        <span>#<?= $id_venta ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Método de Pago:</strong></span>
                        <span><?= $metodo ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Total Pagado:</strong></span>
                        <span>S/ <?= number_format($total_final, 2) ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Estado:</strong></span>
                        <span style="color: var(--success);">✅ Confirmado</span>
                    </div>
                </div>

                <?php if ($_GET['success'] === 'yape'): ?>
                    <!-- 📱 SECCIÓN YAPE -->
                    <div class="qr-section">
                        <h3>📱 Escanea el código para pagar con Yape</h3>
                        <img src="imagenes/yape_qr.png" class="qr-img" alt="Código QR de Yape">
                        <p><strong>Total: S/ <?= number_format($total_final, 2) ?></strong></p>
                        <p>👤 A nombre de <strong>Ronald Aldair Sanchez Monja</strong></p>
                        <p><em>Después de realizar el pago, haz clic en el botón de abajo.</em></p>
                        <a href="cliente/detalle_boleta.php?id=<?= $id_venta ?>" class="btn-pagar" style="text-decoration: none; background: var(--success);">
                            <i class="fas fa-check"></i> ✅ Ya pagué
                        </a>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
                    <a href="cliente/boletas.php" class="btn-pagar" style="text-decoration: none; background: var(--primary);">
                        <i class="fas fa-receipt"></i> Ver Mis Boletas
                    </a>
                    <a href="productos.php" class="btn-pagar" style="text-decoration: none; background: var(--accent); color: var(--dark);">
                        <i class="fas fa-shopping-bag"></i> Seguir Comprando
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class="pago-container">
                <!-- 📋 FORMULARIO DE PAGO -->
                <div class="form-container fade-in">
                    <form method="POST" id="formPago">
                        <!-- 🧾 DATOS DE FACTURACIÓN -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h3 class="section-title-form">Datos de Facturación</h3>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento *</label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="boleta">Boleta</option>
                                        <option value="factura">Factura</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="numero_documento" id="label_documento">Número de Documento *</label>
                                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" 
                                           placeholder="Ingrese su DNI" required maxlength="8">
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="nombre_completo">Nombre Completo *</label>
                                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" 
                                           placeholder="Ingrese su nombre completo" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="telefono">Teléfono *</label>
                                    <input type="tel" name="telefono" id="telefono" class="form-control" 
                                           placeholder="Ej: 987654321" required maxlength="9">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Correo Electrónico *</label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                           placeholder="tu@email.com" required>
                                </div>
                            </div>
                        </div>

                        <!-- 🚚 DATOS DE ENVÍO -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <h3 class="section-title-form">Datos de Envío</h3>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label for="direccion">Dirección Completa *</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control" 
                                           placeholder="Ej: Av. Principal 123" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="departamento">Departamento *</label>
                                    <select name="departamento" id="departamento" class="form-control" required>
                                        <option value="">Seleccionar departamento...</option>
                                        <option value="amazonas">Amazonas</option>
                                        <option value="ancash">Áncash</option>
                                        <option value="apurimac">Apurímac</option>
                                        <option value="arequipa">Arequipa</option>
                                        <option value="ayacucho">Ayacucho</option>
                                        <option value="cajamarca">Cajamarca</option>
                                        <option value="callao">Callao</option>
                                        <option value="cusco">Cusco</option>
                                        <option value="huancavelica">Huancavelica</option>
                                        <option value="huanuco">Huánuco</option>
                                        <option value="ica">Ica</option>
                                        <option value="junin">Junín</option>
                                        <option value="la_libertad">La Libertad</option>
                                        <option value="lambayeque">Lambayeque</option>
                                        <option value="lima">Lima</option>
                                        <option value="loreto">Loreto</option>
                                        <option value="madre_de_dios">Madre de Dios</option>
                                        <option value="moquegua">Moquegua</option>
                                        <option value="pasco">Pasco</option>
                                        <option value="piura">Piura</option>
                                        <option value="puno">Puno</option>
                                        <option value="san_martin">San Martín</option>
                                        <option value="tacna">Tacna</option>
                                        <option value="tumbes">Tumbes</option>
                                        <option value="ucayali">Ucayali</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="provincia">Provincia *</label>
                                    <input type="text" name="provincia" id="provincia" class="form-control" 
                                           placeholder="Ej: Lima" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="distrito">Distrito *</label>
                                    <input type="text" name="distrito" id="distrito" class="form-control" 
                                           placeholder="Ej: Miraflores" required>
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="referencia">Referencia (Opcional)</label>
                                    <input type="text" name="referencia" id="referencia" class="form-control" 
                                           placeholder="Ej: Frente al parque, casa con rejas verdes">
                                </div>
                            </div>
                        </div>

                        <!-- 💳 MÉTODO DE PAGO -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <h3 class="section-title-form">Método de Pago</h3>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="metodo_envio">Método de Envío *</label>
                                    <select name="metodo_envio" id="metodo_envio" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="shalom">Shalom Express - GRATIS</option>
                                        <option value="otro">Otro - GRATIS</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="metodo_pago">Método de Pago *</label>
                                    <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="contra_entrega">Contra Entrega</option>
                                        <option value="yape">Yape / Plin</option>
                                        <option value="tarjeta">Tarjeta Crédito/Débito</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 📱 SECCIÓN YAPE (oculta inicialmente) -->
                            <div id="yapeSection" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background: var(--light); border-radius: 10px; text-align: center;">
                                <h4 style="margin-bottom: 1rem; color: var(--primary);">📱 Pago con Yape</h4>
                                <img src="imagenes/yape_qr.png" class="qr-img" alt="Código QR de Yape" style="max-width: 200px; border-radius: 10px; margin-bottom: 1rem;">
                                <p style="margin-bottom: 0.5rem;"><strong>Total: S/ <span id="totalYape">0.00</span></strong></p>
                                <p style="margin-bottom: 0.5rem;">👤 A nombre de <strong>Ronald Aldair Sanchez Monja</strong></p>
                                <p style="font-size: 0.9rem; color: var(--gray);"><em>Escanea el código QR con Yape para pagar</em></p>
                            </div>

                            <!-- 💳 SECCIÓN TARJETA (oculta inicialmente) -->
                            <div id="tarjetaSection" style="display: none; margin-top: 1.5rem;">
                                <div class="tarjeta-section">
                                    <div class="tarjeta-header">
                                        <div class="tarjeta-chip"></div>
                                        <div class="tarjeta-logo">VISA</div>
                                    </div>
                                    <div class="tarjeta-numero" id="tarjetaNumeroDisplay">**** **** **** ****</div>
                                    <div class="tarjeta-datos">
                                        <div>
                                            <span>NOMBRE EN LA TARJETA</span>
                                            <div id="tarjetaNombreDisplay">NOMBRE COMPLETO</div>
                                        </div>
                                        <div>
                                            <span>EXPIRA</span>
                                            <div id="tarjetaExpiracionDisplay">MM/AA</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tarjeta-campos">
                                    <div class="form-grid">
                                        <div class="form-group full-width">
                                            <label for="tarjeta_numero">Número de Tarjeta *</label>
                                            <input type="text" name="tarjeta_numero" id="tarjeta_numero" class="form-control" 
                                                   placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatearNumeroTarjeta(this)">
                                        </div>
                                        
                                        <div class="form-group full-width">
                                            <label for="tarjeta_nombre">Nombre en la Tarjeta *</label>
                                            <input type="text" name="tarjeta_nombre" id="tarjeta_nombre" class="form-control" 
                                                   placeholder="Como aparece en la tarjeta" oninput="actualizarTarjetaDisplay()">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tarjeta_expiracion">Fecha Expiración *</label>
                                            <input type="text" name="tarjeta_expiracion" id="tarjeta_expiracion" class="form-control" 
                                                   placeholder="MM/AA" maxlength="5" oninput="formatearExpiracion(this)">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tarjeta_cvv">CVV *</label>
                                            <input type="text" name="tarjeta_cvv" id="tarjeta_cvv" class="form-control" 
                                                   placeholder="123" maxlength="3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="procesar_pago" class="btn-pagar" id="btnProcesarPago">
                            <i class="fas fa-lock"></i> Confirmar y Pagar
                        </button>
                    </form>
                </div>

                <!-- 💰 RESUMEN DEL PEDIDO -->
                <div class="resumen-pedido fade-in">
                    <div class="resumen-header">
                        <h3>Resumen del Pedido</h3>
                        <p class="total-pedido">S/ <span id="totalFinal"><?= number_format($total, 2) ?></span></p>
                    </div>
                    
                    <div class="productos-resumen">
                        <?php foreach ($_SESSION['carrito'] as $item): 
                            $r = mysqli_query($conexion, "SELECT * FROM productos WHERE id=" . intval($item['id']));
                            $p = mysqli_fetch_assoc($r);
                            if ($p):
                        ?>
                            <div class="producto-resumen">
                                <span class="producto-nombre"><?= htmlspecialchars($p['nombre']) ?> x<?= $item['cantidad'] ?></span>
                                <span class="producto-precio">S/ <?= number_format($p['precio'] * $item['cantidad'], 2) ?></span>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                    
                    <div class="detalles-pedido">
                        <div class="detalle-item">
                            <span class="detalle-label">Subtotal:</span>
                            <span class="detalle-valor">S/ <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Envío:</span>
                            <span class="detalle-valor" id="costoEnvio">S/ 0.00</span>
                        </div>
                        <div class="detalle-item total-item">
                            <span class="detalle-label">Total:</span>
                            <span class="detalle-valor">S/ <span id="totalConEnvio"><?= number_format($total, 2) ?></span></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoDocumento = document.getElementById('tipo_documento');
    const labelDocumento = document.getElementById('label_documento');
    const numeroDocumento = document.getElementById('numero_documento');
    const metodoEnvio = document.getElementById('metodo_envio');
    const metodoPago = document.getElementById('metodo_pago');
    const costoEnvio = document.getElementById('costoEnvio');
    const totalConEnvio = document.getElementById('totalConEnvio');
    const totalFinal = document.getElementById('totalFinal');
    const totalYape = document.getElementById('totalYape');
    const yapeSection = document.getElementById('yapeSection');
    const tarjetaSection = document.getElementById('tarjetaSection');
    const subtotal = <?= $total ?>;

    // 🧾 CAMBIAR TIPO DE DOCUMENTO
    tipoDocumento.addEventListener('change', function() {
        if (this.value === 'factura') {
            labelDocumento.textContent = 'RUC *';
            numeroDocumento.placeholder = 'Ingrese su RUC';
            numeroDocumento.maxLength = 11;
        } else {
            labelDocumento.textContent = 'DNI *';
            numeroDocumento.placeholder = 'Ingrese su DNI';
            numeroDocumento.maxLength = 8;
        }
    });

    // 🚚 CALCULAR COSTO DE ENVÍO - ✅ CORREGIDO: Envío gratis
    metodoEnvio.addEventListener('change', function() {
        // Envío gratis, no se agrega costo
        costoEnvio.textContent = 'S/ 0.00';
        totalConEnvio.textContent = subtotal.toFixed(2);
        totalFinal.textContent = subtotal.toFixed(2);
        totalYape.textContent = subtotal.toFixed(2);
    });

    // 💳 MOSTRAR/OCULTAR SECCIONES DE PAGO
    metodoPago.addEventListener('change', function() {
        // Ocultar todas las secciones primero
        yapeSection.style.display = 'none';
        tarjetaSection.style.display = 'none';
        
        // Mostrar sección correspondiente
        if (this.value === 'yape') {
            yapeSection.style.display = 'block';
            // Actualizar total en sección Yape
            totalYape.textContent = subtotal.toFixed(2);
        } else if (this.value === 'tarjeta') {
            tarjetaSection.style.display = 'block';
        }
    });

    // 💳 FORMATEAR NÚMERO DE TARJETA
    function formatearNumeroTarjeta(input) {
        // Remover todos los espacios existentes
        let value = input.value.replace(/\s+/g, '');
        
        // Agregar espacio cada 4 dígitos
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        
        // Actualizar el input
        input.value = value;
        
        // Actualizar la visualización de la tarjeta
        actualizarTarjetaDisplay();
    }

    // 💳 FORMATEAR FECHA DE EXPIRACIÓN
    function formatearExpiracion(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        input.value = value;
        actualizarTarjetaDisplay();
    }

    // 💳 ACTUALIZAR DISPLAY DE LA TARJETA
    function actualizarTarjetaDisplay() {
        const numero = document.getElementById('tarjeta_numero').value || '**** **** **** ****';
        const nombre = document.getElementById('tarjeta_nombre').value || 'NOMBRE COMPLETO';
        const expiracion = document.getElementById('tarjeta_expiracion').value || 'MM/AA';
        
        document.getElementById('tarjetaNumeroDisplay').textContent = numero;
        document.getElementById('tarjetaNombreDisplay').textContent = nombre.toUpperCase();
        document.getElementById('tarjetaExpiracionDisplay').textContent = expiracion;
    }

    // 📝 VALIDACIÓN DE FORMULARIO
    document.getElementById('formPago').addEventListener('submit', function(e) {
        const metodoPagoVal = document.getElementById('metodo_pago').value;
        const metodoEnvioVal = document.getElementById('metodo_envio').value;
        
        if (!metodoPagoVal || !metodoEnvioVal) {
            e.preventDefault();
            alert('Por favor, complete todos los campos requeridos.');
            return;
        }
        
        // Validar campos de tarjeta si es necesario
        if (metodoPagoVal === 'tarjeta') {
            const tarjetaNumero = document.getElementById('tarjeta_numero').value.replace(/\s/g, '');
            const tarjetaNombre = document.getElementById('tarjeta_nombre').value;
            const tarjetaExpiracion = document.getElementById('tarjeta_expiracion').value;
            const tarjetaCvv = document.getElementById('tarjeta_cvv').value;
            
            if (!tarjetaNumero || tarjetaNumero.length < 16) {
                e.preventDefault();
                alert('Por favor, ingrese un número de tarjeta válido (16 dígitos).');
                return;
            }
            
            if (!tarjetaNombre) {
                e.preventDefault();
                alert('Por favor, ingrese el nombre que aparece en la tarjeta.');
                return;
            }
            
            if (!tarjetaExpiracion || tarjetaExpiracion.length !== 5) {
                e.preventDefault();
                alert('Por favor, ingrese una fecha de expiración válida (MM/AA).');
                return;
            }
            
            if (!tarjetaCvv || tarjetaCvv.length !== 3) {
                e.preventDefault();
                alert('Por favor, ingrese un CVV válido (3 dígitos).');
                return;
            }
        }
        
        // Mostrar confirmación
        if (!confirm('¿Estás seguro de que quieres proceder con el pago?')) {
            e.preventDefault();
        }
    });

    // ✨ ANIMACIONES
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>