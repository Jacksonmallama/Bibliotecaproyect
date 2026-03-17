<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel - Biblioteca</title>
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
                    <li><a href="panel.php#libros" onclick="switchTab('libros')">Libros</a></li>
                    <li><a href="panel.php#usuarios" onclick="switchTab('usuarios')">Usuarios</a></li>
                    <li><a href="panel.php#prestamos" onclick="switchTab('prestamos')">Préstamos</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div id="toast" class="toast"></div>  <!-- Contenedor para notificaciones -->

    <!-- SECCIÓN LIBROS -->
    <div class="table-container" id="librosSection">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar libro..." />
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

    <!-- SECCIÓN USUARIOS -->
    <div class="table-container" id="usuariosSection" style="display: none;">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchUserInput" placeholder="Buscar usuario..." />
                <i class="bi bi-search"></i>
            </div>
            <a href="#" class="btn-add" id="btnAddUser">AGREGAR USUARIO</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyUsuarios"></tbody>
        </table>
    </div>

    <!-- SECCIÓN PRÉSTAMOS -->
    <div class="table-container" id="prestamosSection" style="display: none;">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchPrestamosInput" placeholder="Buscar préstamo..." />
                <i class="bi bi-search"></i>
            </div>
            <a href="#" class="btn-add" id="btnAddPrestamo">AGREGAR PRÉSTAMO</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha préstamo</th>
                    <th>Fecha devolución</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyPrestamos"></tbody>
        </table>
    </div>

    <!-- MODAL LIBRO -->
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

    <!-- MODAL ELIMINAR LIBRO -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3>¿Está seguro que desea eliminar este libro?</h3>
            <div class="modal-buttons">
                <button class="btn-confirm" onclick="confirmDelete()">Sí</button>
                <button class="btn-cancel" onclick="closeDeleteModal()">No</button>
            </div>
        </div>
    </div>

    <!-- MODAL USUARIO -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <h3 id="userModalTitle">Agregar usuario</h3>
            <input type="hidden" id="userId">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" id="userNombre">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="userEmail">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" id="userTelefono">
            </div>

            <div class="modal-buttons">
                <button class="btn-confirm" id="saveUserBtn">Guardar</button>
                <button class="btn-cancel" onclick="document.getElementById('userModal').style.display='none'">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- MODAL PRÉSTAMO -->
    <div class="modal" id="prestamoModal">
        <div class="modal-content">
            <h3 id="prestamoModalTitle">Agregar préstamo</h3>
            <input type="hidden" id="prestamoId">
            <input type="hidden" id="prestamoEstado" value="prestado">

            <div class="form-grid">
                <div class="form-group">
                    <label>Usuario</label>
                    <select id="prestamoUsuario"></select>
                </div>

                <div class="form-group">
                    <label>Libro</label>
                    <select id="prestamoLibro"></select>
                </div>

                <div class="form-group">
                    <label>Fecha préstamo</label>
                    <input type="date" id="prestamoFecha" />
                </div>

                <div class="form-group">
                    <label>Fecha devolución</label>
                    <input type="date" id="prestamoFechaDevolucion" />
                </div>
            </div>

            <div class="modal-buttons">
                <button class="btn-confirm" id="savePrestamoBtn">Guardar</button>
                <button class="btn-cancel" onclick="document.getElementById('prestamoModal').style.display='none'">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- MODAL ELIMINAR USUARIO -->
    <div class="modal" id="userDeleteModal">
        <div class="modal-content">
            <h3 class="warning">¿Está seguro que desea eliminar este usuario?</h3>
            <div class="modal-buttons">
                <button class="btn-confirm" id="confirmDeleteUserBtn">Sí</button>
                <button class="btn-cancel" onclick="document.getElementById('userDeleteModal').style.display='none'">No</button>
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

    <script>
        // Función para cambiar de pestaña desde el menú
        function switchTab(tab) {
            const librosSection = document.getElementById('librosSection');
            const usuariosSection = document.getElementById('usuariosSection');
            const prestamosSection = document.getElementById('prestamosSection');

            librosSection.style.display = 'none';
            usuariosSection.style.display = 'none';
            prestamosSection.style.display = 'none';

            if (tab === 'libros') {
                librosSection.style.display = 'block';
            } else if (tab === 'usuarios') {
                usuariosSection.style.display = 'block';
            } else if (tab === 'prestamos') {
                prestamosSection.style.display = 'block';
            }
        }

        // Detectar hash en la URL
        window.addEventListener('load', function() {
            const hash = window.location.hash.substr(1) || 'libros';
            switchTab(hash);
        });
    </script>
    <script src="assets/js/libros.js"></script>
    <script src="assets/js/usuarios.js"></script>
    <script src="assets/js/prestamos.js"></script>
</body>

</html>
