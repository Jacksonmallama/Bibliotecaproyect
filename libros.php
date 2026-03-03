<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>
    <header class="navbar">
        <button class="btn-menu-toggle" onclick="toggleSideMenu()">
            <i class="bi bi-list"></i>
        </button>
        <div class="logo">📚 BookDoc</div>

        <nav class="menu">
            <a href="#">Inicio</a>
            <a href="#">Catálogo</a>
            <a href="#">Sobre nosotros</a>
            <a href="#">Contacto</a>
            <button class="btn-login" onclick="openLoginModal()">Iniciar Sesión</button>
        </nav>

        <!-- SIDE MENU -->
        <div class="side-menu" id="sideMenu">
            <button class="btn-close-menu" onclick="closeSideMenu()">
                <i class="bi bi-x"></i>
            </button>
            <div class="side-menu-content">
                <h4>Panel de Control</h4>
                <ul class="side-links">
                    <li><a href="libros.php">Libros</a></li>
                    <li><a href="usuarios.php">Usuarios</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div id="toast" class="toast"></div>

    <div class="table-container">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar por ISBN..." />
                <i class="bi bi-search"></i>
            </div>
            <a href="#" class="btn-add" onclick="openAddModal()">AGREGAR</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Año edición</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyLibros"></tbody>
        </table>
    </div>

    <div class="modal" id="formModal">
        <div class="modal-content">
            <h3 id="modalTitle">Agregar libro</h3>
            <div class="form-group">
                <label>ISBN <span>*</span></label>
                <input type="text" id="isbn">
            </div>

            <div class="form-group">
                <label>Nombre <span>*</span></label>
                <input type="text" id="nombre">
            </div>

            <div class="form-group">
                <label>Autor <span>*</span></label>
                <input type="text" id="autor">
            </div>

            <div class="form-group">
                <label for="year"><strong>Año edición *</strong></label>
                <input type="number" id="year">
            </div>

            <div class="modal-buttons">
                <button class="btn-confirm" onclick="saveBook()">Guardar</button>
                <button class="btn-cancel" onclick="closeFormModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3>¿Está seguro que desea eliminar este libro?</h3>
            <div class="modal-buttons">
                <button class="btn-confirm" onclick="confirmDelete()">Sí</button>
                <button class="btn-cancel" onclick="closeDeleteModal()">No</button>
            </div>
        </div>
    </div>

    <!-- MODAL LOGIN -->
    <div class="modal" id="loginModal">
        <div class="modal-content login-box">
            <h3>Bienvenido a BookDoc</h3>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" placeholder="ejemplo@email.com">
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" placeholder="********">
            </div>

            <div class="modal-buttons">
                <button class="btn-confirm">Ingresar</button>
                <button class="btn-cancel" onclick="closeLoginModal()">Cancelar</button>
            </div>

            <div class="register-link">
                ¿No tienes cuenta?
                <button class="btn-register">Registrarse</button>
            </div>
        </div>
    </div>

    <script src="assets/js/libros.js"></script>
</body>

</html>