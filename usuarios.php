<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios - Biblioteca</title>
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
            <a href="libros.php">Libros</a>
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

    <main class="table-container">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchUserInput" placeholder="Buscar por nombre o email..." />
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
    </main>

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

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR USUARIO -->
    <div class="modal" id="userDeleteModal">
        <div class="modal-content">
            <h3 class="warning">¿Está seguro que desea eliminar este usuario?</h3>
            <div class="modal-buttons">
                <button class="btn-confirm" id="confirmDeleteUserBtn">Sí</button>
                <button class="btn-cancel" onclick="document.getElementById('userDeleteModal').style.display='none'">No</button>
            </div>
        </div>
    </div>

    <script src="assets/js/libros.js"></script>
    <script src="assets/js/usuarios.js"></script>
</body>

</html>
