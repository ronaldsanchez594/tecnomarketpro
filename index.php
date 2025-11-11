<?php 
include('includes/conexion.php'); 
include('includes/header.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoMarket - Tecnología Premium</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* 🎯 HERO SECTION MEJORADO */
        .modern-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .modern-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="1000,0 1000,100 0,100"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .hero-title span {
            color: var(--accent);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin: 2.5rem 0;
            flex-wrap: wrap;
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            background: rgba(255,255,255,0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .stat i {
            color: var(--accent);
            font-size: 1.3rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--dark);
        }

        .btn-primary:hover {
            background: #e1b12c;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* 📱 FEATURES SECTION */
        .features-section {
            padding: 5rem 0;
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2.5rem 2rem;
            background: var(--light);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-card p {
            color: var(--gray);
            line-height: 1.6;
        }

        /* 📱 CATEGORÍAS MEJORADAS */
        .categorias-section {
            padding: 5rem 0;
            background: var(--light);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--dark);
            position: relative;
            font-weight: 700;
        }

        .section-subtitle {
            text-align: center;
            color: var(--gray);
            font-size: 1.2rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--accent);
            margin: 10px auto;
            border-radius: 2px;
        }

        .categorias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .categoria-card {
            background: white;
            padding: 2.5rem 1.5rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .categoria-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .categoria-card i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .categoria-card:hover i {
            transform: scale(1.1);
        }

        .categoria-card h3 {
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 600;
        }

        /* 🛒 PRODUCTOS MEJORADOS */
        .productos-section {
            padding: 5rem 0;
            background: white;
        }

        .productos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .header-content {
            flex: 1;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filtros {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 10px 20px;
            border: 2px solid var(--gray);
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: var(--gray);
        }

        .filtro-btn.active,
        .filtro-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .view-all-btn {
            padding: 10px 24px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .view-all-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .productos-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .producto-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid #f0f0f0;
        }

        .producto-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .producto-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent);
            color: var(--dark);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 2;
            text-transform: uppercase;
        }

        .producto-img {
            height: 200px;
            overflow: hidden;
            position: relative;
            background: var(--light);
        }

        .producto-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .producto-card-modern:hover .producto-img img {
            transform: scale(1.1);
        }

        .producto-info {
            padding: 1.5rem;
        }

        .producto-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
            color: var(--dark);
            font-weight: 600;
            line-height: 1.3;
        }

        .producto-desc {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            min-height: 40px;
        }

        .producto-precio {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .agregar-carrito-modern {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .agregar-carrito-modern input {
            width: 70px;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            transition: border-color 0.3s ease;
        }

        .agregar-carrito-modern input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn-add-modern {
            flex: 1;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-modern:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-add-modern.added {
            background: var(--success);
        }

        /* 📱 CALL TO ACTION */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
        }

        .cta-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* 📱 RESPONSIVE MEJORADO */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                gap: 1rem;
            }
            
            .stat {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .categorias-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .productos-grid-modern {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }
            
            .productos-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .modern-header {
                padding: 60px 0;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
            
            .categorias-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .productos-grid-modern {
                grid-template-columns: 1fr;
            }
            
            .agregar-carrito-modern {
                flex-direction: column;
            }
            
            .agregar-carrito-modern input {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- 🎯 HERO SECTION MEJORADO -->
<section class="modern-header">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Tecno<span>Market</span></h1>
            <p class="hero-subtitle">Tecnología de vanguardia a precios increíbles. Descubre los últimos dispositivos con la mejor relación calidad-precio del mercado.</p>
            
            <div class="hero-stats">
                <div class="stat">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Envío Gratis +S/50</span>
                </div>
                <div class="stat">
                    <i class="fas fa-shield-alt"></i>
                    <span>Garantía 1 Año</span>
                </div>
                <div class="stat">
                    <i class="fas fa-headset"></i>
                    <span>Soporte 24/7</span>
                </div>
            </div>
            
            <div class="hero-buttons">
                <a href="#productos" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Explorar Catálogo
                </a>
                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-user"></i> Mi Cuenta
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 📱 FEATURES SECTION -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">¿Por qué elegir TecnoMarket?</h2>
        <p class="section-subtitle">Ofrecemos la mejor experiencia de compra en tecnología con beneficios exclusivos para nuestros clientes</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Entrega Rápida</h3>
                <p>Recibe tus productos en 24-48 horas con nuestro servicio de entrega express disponible en todo el país.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Pago Seguro</h3>
                <p>Transacciones 100% seguras con cifrado SSL y múltiples métodos de pago para tu tranquilidad.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3>Devoluciones Fáciles</h3>
                <p>Política de devolución de 30 días sin complicaciones. Tu satisfacción está garantizada.</p>
            </div>
        </div>
    </div>
</section>

<!-- 📱 CATEGORÍAS MEJORADAS -->
<section class="categorias-section">
    <div class="container">
        <h2 class="section-title">Explora por Categoría</h2>
        <p class="section-subtitle">Encuentra exactamente lo que necesitas en nuestra amplia gama de categorías tecnológicas</p>
        
        <div class="categorias-grid">
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Laptops'">
                <i class="fas fa-laptop"></i>
                <h3>Laptops</h3>
            </div>
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Smartphones'">
                <i class="fas fa-mobile-alt"></i>
                <h3>Smartphones</h3>
            </div>
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Audio'">
                <i class="fas fa-headphones"></i>
                <h3>Audio</h3>
            </div>
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Periféricos'">
                <i class="fas fa-keyboard"></i>
                <h3>Periféricos</h3>
            </div>
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Componentes'">
                <i class="fas fa-microchip"></i>
                <h3>Componentes</h3>
            </div>
            <div class="categoria-card" onclick="window.location.href='productos.php?categoria=Almacenamiento'">
                <i class="fas fa-hdd"></i>
                <h3>Almacenamiento</h3>
            </div>
        </div>
    </div>
</section>

<!-- 🛒 PRODUCTOS MEJORADOS -->
<section id="productos" class="productos-section">
    <div class="container">
        <div class="productos-header">
            <div class="header-content">
                <h2 class="section-title">Productos Destacados</h2>
                <p class="section-subtitle">Los productos más populares y mejor valorados por nuestra comunidad</p>
            </div>
            
            <div class="header-actions">
                <div class="filtros">
                    <button class="filtro-btn active">Todos</button>
                    <button class="filtro-btn">Ofertas</button>
                    <button class="filtro-btn">Nuevos</button>
                    <button class="filtro-btn">Populares</button>
                </div>
                <a href="productos.php" class="view-all-btn">
                    <i class="fas fa-arrow-right"></i> Ver Todos
                </a>
            </div>
        </div>

        <div class="productos-grid-modern">
            <?php
            $res = mysqli_query($conexion,"SELECT * FROM productos ORDER BY id DESC LIMIT 8");
            if(mysqli_num_rows($res)==0){
                echo '<div style="grid-column:1/-1; text-align:center; padding:3rem; background:var(--light); border-radius:15px;">
                        <i class="fas fa-box-open" style="font-size:3rem; color:var(--gray); margin-bottom:1rem;"></i>
                        <h3 style="color:var(--gray); margin-bottom:1rem;">No hay productos disponibles</h3>
                        <p style="color:var(--gray);">Pronto tendremos nuevos productos en stock.</p>
                      </div>';
            } else {
                while($p=mysqli_fetch_assoc($res)):
                    $stockBajo = $p['stock'] <= 5;
                    $agotado = $p['stock'] == 0;
            ?>
            <div class="producto-card-modern">
                <?php if($stockBajo && !$agotado): ?>
                    <div class="producto-badge">Últimas Unidades</div>
                <?php elseif($agotado): ?>
                    <div class="producto-badge" style="background:var(--danger);">Agotado</div>
                <?php else: ?>
                    <div class="producto-badge">Nuevo</div>
                <?php endif; ?>
                
                <div class="producto-img">
                    <?php if(!empty($p['imagen']) && file_exists('imagenes/productos/'.$p['imagen'])): ?>
                        <img src="imagenes/productos/<?php echo $p['imagen'];?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
                    <?php else: ?>
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:var(--gray);">
                            <i class="fas fa-image" style="font-size:3rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="producto-info">
                    <h3><?php echo htmlspecialchars($p['nombre']); ?></h3>
                    <p class="producto-desc"><?php echo substr(htmlspecialchars($p['descripcion']), 0, 80); ?>...</p>
                    <div class="producto-precio">S/ <?php echo number_format($p['precio'],2); ?></div>
                    
                    <?php if(!$agotado): ?>
                    <form class="agregar-carrito-modern" data-id="<?php echo $p['id']; ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?php echo $p['stock']; ?>">
                        <button type="submit" class="btn-add-modern">
                            <i class="fas fa-cart-plus"></i> Agregar
                        </button>
                    </form>
                    <?php else: ?>
                    <button class="btn-add-modern" disabled style="background:var(--gray);">
                        <i class="fas fa-times-circle"></i> Agotado
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; } ?>
        </div>
    </div>
</section>

<!-- 📱 CALL TO ACTION -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Listo para mejorar tu tecnología?</h2>
            <p class="cta-subtitle">Únete a miles de clientes satisfechos y descubre por qué somos la opción preferida en tecnología</p>
            <div class="hero-buttons">
                <a href="productos.php" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Comprar Ahora
                </a>
                <a href="registro.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Crear Cuenta
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
    // Scroll suave mejorado
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Filtros interactivos
    document.querySelectorAll('.filtro-btn').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            document.querySelectorAll('.filtro-btn').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            // Aquí puedes agregar la lógica de filtrado
        });
    });

    // 🛒 Agregar al carrito con AJAX (mejorado)
    document.querySelectorAll('.agregar-carrito-modern').forEach(form=>{
        form.addEventListener('submit', async e=>{
            e.preventDefault();
            const id = form.dataset.id;
            const cant = form.querySelector('input[name="cantidad"]').value;
            const btn = form.querySelector('.btn-add-modern');
            
            try {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                const res = await fetch('includes/ajax_carrito.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'},
                    body: 'accion=agregar&id='+id+'&cantidad='+cant
                });
                
                const data = await res.json();
                if(data.ok){
                    // Actualizar contador del carrito
                    const cartCount = document.getElementById('cartCount');
                    if(cartCount) cartCount.innerText = data.count;

                    // Feedback visual mejorado
                    btn.innerHTML = '<i class="fas fa-check"></i> Agregado';
                    btn.classList.add('added');
                    
                    setTimeout(()=>{ 
                        btn.innerHTML = originalText;
                        btn.classList.remove('added');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    alert('⚠️ Error al agregar el producto.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch(err){
                console.error('Error:', err);
                alert('❌ No se pudo conectar con el servidor.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    });

    // Animación de aparición mejorada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observar elementos para animación
    document.querySelectorAll('.feature-card, .categoria-card, .producto-card-modern').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Efecto hover mejorado para categorías
    document.querySelectorAll('.categoria-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>