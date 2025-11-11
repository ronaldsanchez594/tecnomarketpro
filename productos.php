<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/conexion.php');
include('includes/header.php');

// Obtener categorías únicas
$categorias_query = mysqli_query($conexion, "SELECT DISTINCT categoria FROM productos WHERE categoria IS NOT NULL AND categoria <> '' ORDER BY categoria ASC");

// Filtro y búsqueda
$filtro = isset($_GET['categoria']) ? mysqli_real_escape_string($conexion, $_GET['categoria']) : '';
$busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';

$query = "SELECT * FROM productos WHERE 1";
if ($filtro) $query .= " AND categoria='$filtro'";
if ($busqueda) $query .= " AND (nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%')";
$query .= " ORDER BY id DESC";

$res = mysqli_query($conexion, $query);
?>

<section class="productos-section">
    <div class="container">
        <!-- HEADER MEJORADO -->
        <div class="page-header">
            <h2 class="section-title">Catálogo de Productos</h2>
            <p class="page-subtitle">Descubre nuestra selección de tecnología de vanguardia</p>
        </div>

        <!-- 🔍 BÚSQUEDA Y FILTROS MEJORADOS -->
        <div class="filters-container">
            <form method="GET" class="filtro-busqueda-modern">
                <div class="search-section">
                    <div class="search-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="buscar" placeholder="Buscar productos por nombre o descripción..." 
                               value="<?php echo htmlspecialchars($busqueda); ?>">
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-controls">
                        <div class="select-group">
                            <i class="fas fa-filter"></i>
                            <select name="categoria">
                                <option value="">Todas las categorías</option>
                                <?php 
                                if($categorias_query) {
                                    mysqli_data_seek($categorias_query, 0); // Reset pointer
                                    while($cat = mysqli_fetch_assoc($categorias_query)): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($cat['categoria']); ?>" 
                                            <?php if($filtro == $cat['categoria']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($cat['categoria']); ?>
                                    </option>
                                <?php 
                                    endwhile; 
                                }
                                ?>
                            </select>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn-buscar">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>

                            <?php if($filtro || $busqueda): ?>
                            <a href="productos.php" class="btn-limpiar">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>

            <!-- FILTROS ACTIVOS -->
            <?php if($filtro || $busqueda): ?>
            <div class="active-filters">
                <span class="filters-label">Filtros aplicados:</span>
                <?php if($busqueda): ?>
                <span class="filter-tag">
                    Búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"
                    <a href="?<?php echo $filtro ? 'categoria='.urlencode($filtro) : ''; ?>" class="remove-filter">×</a>
                </span>
                <?php endif; ?>
                <?php if($filtro): ?>
                <span class="filter-tag">
                    Categoría: <?php echo htmlspecialchars($filtro); ?>
                    <a href="?<?php echo $busqueda ? 'buscar='.urlencode($busqueda) : ''; ?>" class="remove-filter">×</a>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- 📊 ESTADÍSTICAS RÁPIDAS -->
        <div class="products-stats">
            <div class="stat-item">
                <i class="fas fa-boxes"></i>
                <span class="stat-number"><?php echo mysqli_num_rows($res); ?></span>
                <span class="stat-label">Productos encontrados</span>
            </div>
            <?php if($filtro): ?>
            <div class="stat-item">
                <i class="fas fa-tag"></i>
                <span class="stat-label">Categoría: <?php echo htmlspecialchars($filtro); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- 📦 PRODUCTOS GRID MEJORADO -->
        <div class="productos-grid-modern">
            <?php if(mysqli_num_rows($res) == 0): ?>
                <div class="no-products">
                    <div class="no-products-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No se encontraron productos</h3>
                    <p>Intenta con otros términos de búsqueda o categorías</p>
                    <div class="no-products-actions">
                        <a href="productos.php" class="btn-buscar">
                            <i class="fas fa-redo"></i> Ver todos los productos
                        </a>
                        <a href="#" class="btn-limpiar" onclick="document.querySelector('input[name=\"buscar\"]').value='';document.querySelector('select[name=\"categoria\"]').value='';document.querySelector('form').submit();">
                            <i class="fas fa-times"></i> Limpiar búsqueda
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php while($p = mysqli_fetch_assoc($res)): 
                    $stockBajo = $p['stock'] <= 5;
                    $agotado = $p['stock'] == 0;
                ?>
                    <div class="producto-card-modern">
                        <!-- BADGES MEJORADOS -->
                        <div class="producto-badges">
                            <?php if($agotado): ?>
                                <div class="producto-badge badge-agotado">AGOTADO</div>
                            <?php elseif($stockBajo): ?>
                                <div class="producto-badge badge-oferta">ÚLTIMAS UNIDADES</div>
                            <?php endif; ?>
                        </div>

                        <!-- IMAGEN MEJORADA -->
                        <div class="producto-img">
                            <?php if(!empty($p['imagen']) && file_exists('imagenes/productos/'.$p['imagen'])): ?>
                                <img src="imagenes/productos/<?php echo $p['imagen'];?>" 
                                     alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="producto-img-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>Imagen no disponible</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- INFORMACIÓN MEJORADA -->
                        <div class="producto-info">
                            <div class="producto-header">
                                <h3 class="producto-nombre"><?php echo htmlspecialchars($p['nombre']); ?></h3>
                                <?php if(!empty($p['categoria'])): ?>
                                <span class="categoria-tag">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($p['categoria']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="producto-desc"><?php echo htmlspecialchars($p['descripcion']); ?></p>
                            
                            <div class="producto-meta">
                                <div class="stock-info <?php echo $agotado ? 'stock-agotado' : ($stockBajo ? 'stock-bajo' : 'stock-ok'); ?>">
                                    <i class="fas fa-box"></i>
                                    <?php echo $agotado ? 'Agotado' : $p['stock'] . ' unidades'; ?>
                                </div>
                            </div>

                            <div class="producto-footer">
                                <div class="producto-precio">S/ <?php echo number_format($p['precio'], 2); ?></div>
                                
                                <?php if(!$agotado): ?>
                                    <form class="agregar-carrito-modern" data-id="<?php echo $p['id']; ?>">
                                        <div class="cantidad-control">
                                            <button type="button" class="cantidad-btn minus">-</button>
                                            <input type="number" name="cantidad" value="1" min="1" max="<?php echo $p['stock']; ?>">
                                            <button type="button" class="cantidad-btn plus">+</button>
                                        </div>
                                        <button type="submit" class="btn-add-modern">
                                            <i class="fas fa-cart-plus"></i> Agregar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-add-modern" disabled>
                                        <i class="fas fa-times-circle"></i> Agotado
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* 🎨 VARIABLES (MANTENIDAS) */
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

/* 🎯 ESTILOS GENERALES MEJORADOS */
.productos-section {
    padding: 2rem 0;
    background: var(--light);
    min-height: 80vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* HEADER MEJORADO */
.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: var(--dark);
    font-weight: 700;
}

.page-subtitle {
    color: var(--gray);
    font-size: 1.1rem;
}

/* 🔍 FILTROS Y BÚSQUEDA MEJORADOS */
.filters-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.filtro-busqueda-modern {
    padding: 2rem;
}

.search-section {
    margin-bottom: 1.5rem;
}

.search-group {
    position: relative;
    max-width: 500px;
    margin: 0 auto;
}

.search-group input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--light);
}

.search-group input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,151,230,0.1);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

.filter-section {
    border-top: 1px solid #f0f0f0;
    padding-top: 1.5rem;
}

.filter-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.select-group {
    position: relative;
    display: flex;
    align-items: center;
}

.select-group i {
    position: absolute;
    left: 15px;
    color: var(--gray);
    z-index: 2;
}

.select-group select {
    padding: 12px 20px 12px 45px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    background: var(--light);
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 200px;
}

.select-group select:focus {
    border-color: var(--primary);
    outline: none;
}

.button-group {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-buscar {
    background: var(--primary);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-buscar:hover {
    background: var(--secondary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,151,230,0.3);
}

.btn-limpiar {
    background: transparent;
    color: var(--gray);
    border: 2px solid var(--gray);
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-limpiar:hover {
    color: var(--danger);
    border-color: var(--danger);
    transform: translateY(-2px);
}

/* FILTROS ACTIVOS */
.active-filters {
    padding: 1rem 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filters-label {
    font-weight: 600;
    color: var(--dark);
}

.filter-tag {
    background: var(--primary);
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.remove-filter {
    color: white;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.remove-filter:hover {
    background: rgba(255,255,255,0.2);
}

/* ESTADÍSTICAS */
.products-stats {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-item i {
    color: var(--primary);
    font-size: 1.2rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
}

.stat-label {
    color: var(--gray);
    font-weight: 500;
}

/* 📦 PRODUCTOS GRID MEJORADO */
.productos-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.producto-card-modern {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.producto-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.producto-badges {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 2;
}

.producto-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-oferta {
    background: var(--danger);
    color: white;
}

.badge-agotado {
    background: var(--gray);
    color: white;
}

.producto-img {
    height: 200px;
    overflow: hidden;
    background: var(--light);
    display: flex;
    align-items: center;
    justify-content: center;
}

.producto-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.producto-img-placeholder {
    text-align: center;
    color: var(--gray);
}

.producto-img-placeholder i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    display: block;
}

.producto-card-modern:hover .producto-img img {
    transform: scale(1.05);
}

.producto-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.producto-header {
    margin-bottom: 1rem;
}

.producto-nombre {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: var(--dark);
    font-weight: 600;
    line-height: 1.3;
}

.categoria-tag {
    background: var(--light);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.producto-desc {
    color: var(--gray);
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    flex: 1;
}

.producto-meta {
    margin-bottom: 1rem;
}

.stock-info {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    font-size: 0.9rem;
}

.stock-ok { color: var(--success); }
.stock-bajo { color: var(--accent); }
.stock-agotado { color: var(--danger); }

.producto-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.producto-precio {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
}

.agregar-carrito-modern {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.cantidad-control {
    display: flex;
    align-items: center;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.cantidad-btn {
    background: var(--light);
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.3s ease;
    font-weight: 600;
}

.cantidad-btn:hover {
    background: #e0e0e0;
}

.cantidad-control input {
    width: 50px;
    border: none;
    text-align: center;
    font-weight: 600;
    padding: 8px 0;
}

.cantidad-control input:focus {
    outline: none;
}

.btn-add-modern {
    flex: 1;
    background: var(--primary);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-add-modern:hover:not(:disabled) {
    background: var(--secondary);
    transform: translateY(-1px);
}

.btn-add-modern:disabled {
    background: var(--gray);
    cursor: not-allowed;
    transform: none;
}

.btn-add-modern.added {
    background: var(--success);
}

/* 📱 NO RESULTS MEJORADO */
.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.no-products-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1.5rem;
}

.no-products h3 {
    color: var(--gray);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.no-products p {
    color: var(--gray);
    margin-bottom: 2rem;
}

.no-products-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* 📱 RESPONSIVE MEJORADO */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }

    .filtro-busqueda-modern {
        padding: 1.5rem;
    }

    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .select-group {
        width: 100%;
    }

    .select-group select {
        width: 100%;
    }

    .button-group {
        justify-content: center;
    }

    .products-stats {
        flex-direction: column;
        gap: 1rem;
    }

    .productos-grid-modern {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .agregar-carrito-modern {
        flex-direction: column;
    }

    .cantidad-control {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .productos-grid-modern {
        grid-template-columns: 1fr;
    }

    .active-filters {
        flex-direction: column;
        align-items: start;
        gap: 0.5rem;
    }

    .no-products-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 🛒 AGREGAR AL CARRITO
    document.querySelectorAll('.agregar-carrito-modern').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const id = form.dataset.id;
            const cant = form.querySelector('input[name="cantidad"]').value;
            const btn = form.querySelector('.btn-add-modern');
            
            try {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                const res = await fetch('includes/ajax_carrito.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'accion=agregar&id=' + id + '&cantidad=' + cant
                });
                
                const data = await res.json();

                if (data.ok) {
                    // Actualizar contador del carrito
                    const cartCount = document.getElementById('cartCount');
                    if(cartCount) {
                        cartCount.innerText = data.count;
                    }

                    // Feedback visual
                    btn.innerHTML = '<i class="fas fa-check"></i> Agregado';
                    btn.classList.add('added');
                    
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('added');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error('Error en la respuesta');
                }
            } catch (err) {
                console.error(err);
                btn.innerHTML = '<i class="fas fa-times"></i> Error';
                btn.style.background = 'var(--danger)';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-cart-plus"></i> Agregar';
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        });
    });

    // CONTROLES DE CANTIDAD MEJORADOS
    document.querySelectorAll('.cantidad-control').forEach(control => {
        const input = control.querySelector('input');
        const minus = control.querySelector('.minus');
        const plus = control.querySelector('.plus');
        const max = parseInt(input.max) || 999;

        minus.addEventListener('click', () => {
            let value = parseInt(input.value) || 1;
            if (value > 1) {
                input.value = value - 1;
            }
        });

        plus.addEventListener('click', () => {
            let value = parseInt(input.value) || 1;
            if (value < max) {
                input.value = value + 1;
            }
        });

        input.addEventListener('change', () => {
            let value = parseInt(input.value) || 1;
            if (value < 1) input.value = 1;
            if (value > max) input.value = max;
        });
    });

    // AUTO-SUBMIT AL CAMBIAR CATEGORÍA EN MÓVIL
    const categoriaSelect = document.querySelector('select[name="categoria"]');
    if (window.innerWidth < 768) {
        categoriaSelect?.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>

<?php include('includes/footer.php'); ?>