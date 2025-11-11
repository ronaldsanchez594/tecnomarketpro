<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/conexion.php');
include('includes/header.php');

// Inicializar carrito
if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

// Calcular totales
$total_general = 0;
$total_items = 0;
$productos_carrito = [];

foreach($_SESSION['carrito'] as $idx => $item) {
    $idProducto = intval($item['id']);
    $res = mysqli_query($conexion, "SELECT * FROM productos WHERE id=$idProducto");
    $p = mysqli_fetch_assoc($res);
    if(!$p) continue;
    
    $cantidad = min($item['cantidad'], $p['stock']);
    $subtotal = $p['precio'] * $cantidad;
    $total_general += $subtotal;
    $total_items += $cantidad;
    
    $productos_carrito[] = [
        'idx' => $idx,
        'producto' => $p,
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - TecnoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 🎨 VARIABLES MODERNAS */
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
        .carrito-section {
            padding: 3rem 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 80vh;
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

        /* 📦 CARRITO VACÍO */
        .carrito-vacio {
            background: white;
            padding: 4rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 2rem;
        }

        .carrito-vacio i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1.5rem;
        }

        .carrito-vacio h3 {
            color: var(--gray);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .carrito-vacio p {
            color: var(--gray);
            margin-bottom: 2rem;
        }

        /* 🛒 CONTENEDOR PRINCIPAL */
        .carrito-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* 📋 LISTA DE PRODUCTOS */
        .productos-lista {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .producto-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .producto-item:hover {
            background: #f8f9fa;
        }

        .producto-item:last-child {
            border-bottom: none;
        }

        .producto-imagen {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 1.5rem;
            border: 2px solid #f0f0f0;
        }

        .producto-info {
            flex: 1;
        }

        .producto-nombre {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .producto-categoria {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .producto-precio {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* 🔢 CONTROLES DE CANTIDAD */
        .cantidad-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0 2rem;
        }

        .btn-cantidad {
            width: 35px;
            height: 35px;
            border: 2px solid var(--primary);
            background: white;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-cantidad:hover {
            background: var(--primary);
            color: white;
        }

        .cantidad-input {
            width: 60px;
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .cantidad-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        /* 💰 SUBTOTAL Y ACCIONES */
        .producto-subtotal {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.2rem;
            margin: 0 2rem;
            min-width: 100px;
            text-align: center;
        }

        .btn-eliminar {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-eliminar:hover {
            background: #c23616;
            transform: scale(1.05);
        }

        /* 💳 RESUMEN DEL PEDIDO */
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

        .detalles-pedido {
            margin-bottom: 2rem;
        }

        .detalle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
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

        /* 🔘 BOTONES DE ACCIÓN */
        .acciones-carrito {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,151,230,0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c23616;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232,65,24,0.3);
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 968px) {
            .carrito-container {
                grid-template-columns: 1fr;
            }
            
            .resumen-pedido {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .producto-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .cantidad-controls {
                margin: 1rem 0;
            }
            
            .producto-subtotal {
                margin: 0.5rem 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .carrito-section {
                padding: 2rem 0;
            }
            
            .producto-item {
                padding: 1rem;
            }
            
            .resumen-pedido {
                padding: 1.5rem;
            }
            
            .total-pedido {
                font-size: 2rem;
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

        .slide-out {
            animation: slideOut 0.3s ease forwards;
        }

        @keyframes slideOut {
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    </style>
</head>
<body>

<section class="carrito-section">
    <div class="container">
        <h2 class="section-title">🛒 Mi Carrito de Compras</h2>

        <?php if(empty($_SESSION['carrito'])): ?>
            <!-- 🎪 CARRITO VACÍO -->
            <div class="carrito-vacio fade-in">
                <i class="fas fa-shopping-cart"></i>
                <h3>Tu carrito está vacío</h3>
                <p>Explora nuestros productos y añade algunos items a tu carrito</p>
                <a href="productos.php" class="btn btn-primary">
                    <i class="fas fa-store"></i> Explorar Productos
                </a>
            </div>
        <?php else: ?>
            <div class="carrito-container">
                <!-- 📋 LISTA DE PRODUCTOS -->
                <div class="productos-lista fade-in">
                    <?php foreach($productos_carrito as $item): 
                        $p = $item['producto'];
                        $idx = $item['idx'];
                    ?>
                        <div class="producto-item" data-idx="<?php echo $idx; ?>">
                            <img src="imagenes/productos/<?php echo !empty($p['imagen']) && file_exists('imagenes/productos/'.$p['imagen']) ? $p['imagen'] : 'default.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($p['nombre']); ?>" class="producto-imagen">
                            
                            <div class="producto-info">
                                <div class="producto-nombre"><?php echo htmlspecialchars($p['nombre']); ?></div>
                                <div class="producto-categoria"><?php echo htmlspecialchars($p['categoria'] ?? 'General'); ?></div>
                                <div class="producto-precio">S/ <?php echo number_format($p['precio'], 2); ?></div>
                            </div>

                            <div class="cantidad-controls">
                                <button class="btn-cantidad btn-decrease" data-idx="<?php echo $idx; ?>">-</button>
                                <input type="number" class="cantidad-input" value="<?php echo $item['cantidad']; ?>" 
                                       min="1" max="<?php echo $p['stock']; ?>" data-idx="<?php echo $idx; ?>">
                                <button class="btn-cantidad btn-increase" data-idx="<?php echo $idx; ?>">+</button>
                            </div>

                            <div class="producto-subtotal" data-idx="<?php echo $idx; ?>">
                                S/ <?php echo number_format($item['subtotal'], 2); ?>
                            </div>

                            <button class="btn-eliminar" data-idx="<?php echo $idx; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 💳 RESUMEN DEL PEDIDO -->
                <div class="resumen-pedido fade-in">
                    <div class="resumen-header">
                        <h3>Resumen del Pedido</h3>
                        <p class="total-pedido">S/ <span id="totalCarrito"><?php echo number_format($total_general, 2); ?></span></p>
                    </div>
                    
                    <div class="detalles-pedido">
                        <div class="detalle-item">
                            <span class="detalle-label">Productos:</span>
                            <span class="detalle-valor" id="totalItems"><?php echo $total_items; ?> items</span>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Subtotal:</span>
                            <span class="detalle-valor">S/ <?php echo number_format($total_general, 2); ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Envío:</span>
                            <span class="detalle-valor">Gratis</span>
                        </div>
                        <div class="detalle-item total-item">
                            <span class="detalle-label">Total:</span>
                            <span class="detalle-valor">S/ <span id="totalFinal"><?php echo number_format($total_general, 2); ?></span></span>
                        </div>
                    </div>

                    <div class="acciones-carrito">
                        <a href="pago.php" class="btn btn-primary">
                            <i class="fas fa-credit-card"></i> Proceder al Pago
                        </a>
                        <a href="productos.php" class="btn btn-secondary">
                            <i class="fas fa-plus"></i> Seguir Comprando
                        </a>
                        <button class="btn btn-danger" id="vaciarCarrito">
                            <i class="fas fa-trash"></i> Vaciar Carrito
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 🔢 ACTUALIZAR CANTIDAD
    function actualizarCantidad(idx, nuevaCantidad) {
        const formData = new URLSearchParams();
        formData.append('accion', 'actualizar');
        formData.append('idx', idx);
        formData.append('cantidad', nuevaCantidad);

        fetch('includes/ajax_carrito.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Error en la respuesta del servidor');
            return res.json();
        })
        .then(data => {
            if (data.ok) {
                // Actualizar subtotal del producto
                const subtotalElement = document.querySelector(`.producto-subtotal[data-idx="${idx}"]`);
                if (subtotalElement) {
                    subtotalElement.textContent = 'S/ ' + parseFloat(data.subtotal).toFixed(2);
                }
                
                // Actualizar totales generales
                document.getElementById('totalCarrito').textContent = parseFloat(data.total).toFixed(2);
                document.getElementById('totalFinal').textContent = parseFloat(data.total).toFixed(2);
                
                // Actualizar contador del carrito
                const cartCount = document.getElementById('cartCount');
                if (cartCount) cartCount.textContent = data.count;
                
                // Actualizar total de items
                document.getElementById('totalItems').textContent = data.count + ' items';
                
            } else {
                alert(data.msg || 'Error al actualizar la cantidad');
                location.reload();
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Error de conexión con el servidor');
        });
    }

    // ➕ BOTÓN AUMENTAR
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = this.dataset.idx;
            const input = document.querySelector(`.cantidad-input[data-idx="${idx}"]`);
            if (input) {
                const max = parseInt(input.getAttribute('max')) || 999;
                const nuevaCantidad = Math.min(parseInt(input.value) + 1, max);
                input.value = nuevaCantidad;
                actualizarCantidad(idx, nuevaCantidad);
            }
        });
    });

    // ➖ BOTÓN DISMINUIR
    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = this.dataset.idx;
            const input = document.querySelector(`.cantidad-input[data-idx="${idx}"]`);
            if (input) {
                const nuevaCantidad = Math.max(parseInt(input.value) - 1, 1);
                input.value = nuevaCantidad;
                actualizarCantidad(idx, nuevaCantidad);
            }
        });
    });

    // 📝 INPUT DIRECT
    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('change', function() {
            const idx = this.dataset.idx;
            const nuevaCantidad = Math.max(1, parseInt(this.value) || 1);
            const max = parseInt(this.getAttribute('max')) || 999;
            
            if (nuevaCantidad > max) {
                this.value = max;
                alert('No hay suficiente stock disponible');
                actualizarCantidad(idx, max);
            } else {
                this.value = nuevaCantidad;
                actualizarCantidad(idx, nuevaCantidad);
            }
        });
    });

    // 🗑️ ELIMINAR PRODUCTO
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = this.dataset.idx;
            
            if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
                fetch('includes/ajax_carrito.php?accion=quitar&idx=' + idx)
                .then(res => {
                    if (!res.ok) throw new Error('Error en la respuesta del servidor');
                    return res.json();
                })
                .then(data => {
                    if (data.ok) {
                        // Animación de eliminación
                        const item = document.querySelector(`.producto-item[data-idx="${idx}"]`);
                        if (item) {
                            item.classList.add('slide-out');
                            setTimeout(() => {
                                item.remove();
                                
                                // Actualizar totales
                                document.getElementById('totalCarrito').textContent = parseFloat(data.total).toFixed(2);
                                document.getElementById('totalFinal').textContent = parseFloat(data.total).toFixed(2);
                                
                                // Actualizar contador
                                const cartCount = document.getElementById('cartCount');
                                if (cartCount) cartCount.textContent = data.count;
                                
                                // Actualizar total de items
                                document.getElementById('totalItems').textContent = data.count + ' items';
                                
                                // Si el carrito queda vacío, recargar la página
                                if (data.count === 0) {
                                    setTimeout(() => location.reload(), 1000);
                                }
                            }, 300);
                        }
                    } else {
                        alert(data.msg || 'Error al eliminar el producto');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error de conexión con el servidor');
                });
            }
        });
    });

    // 🗑️ VACIAR CARRITO
    const vaciarBtn = document.getElementById('vaciarCarrito');
    if (vaciarBtn) {
        vaciarBtn.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres vaciar todo el carrito? Esta acción no se puede deshacer.')) {
                fetch('includes/ajax_carrito.php?accion=vaciar')
                .then(res => {
                    if (!res.ok) throw new Error('Error en la respuesta del servidor');
                    return res.json();
                })
                .then(data => {
                    if (data.ok) {
                        // Animación de vaciado
                        const items = document.querySelectorAll('.producto-item');
                        items.forEach((item, index) => {
                            setTimeout(() => {
                                item.classList.add('slide-out');
                            }, index * 100);
                        });
                        
                        setTimeout(() => {
                            // Actualizar contador
                            const cartCount = document.getElementById('cartCount');
                            if (cartCount) cartCount.textContent = '0';
                            
                            // Recargar la página para mostrar carrito vacío
                            location.reload();
                        }, items.length * 100 + 500);
                    } else {
                        alert(data.msg || 'Error al vaciar el carrito');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error de conexión con el servidor');
                });
            }
        });
    }

    // ✨ ANIMACIONES DE ENTRADA
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>