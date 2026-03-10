// core variables for element references
    const isbn = document.getElementById('isbn');
    const nombre = document.getElementById('nombre');
    const autor = document.getElementById('autor');
    const year = document.getElementById('year');

        let isbnToDelete = null;
        let editing = false;
        let oldISBN = null;

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.innerText = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function loadBooks() {
            fetch('estado_libros.php?action=list')
                .then(res => res.json())
                .then(data => {
                    let tbody = document.getElementById('tbodyLibros');
                    tbody.innerHTML = '';
                    data.forEach(book => {
                        tbody.innerHTML += `
                <tr>
                    <td>${book.ISBN}</td>
                    <td>${book.Name}</td>
                    <td>${book.Autor}</td>
                    <td>${book.Year_edition}</td>
                    <td>
                        <div class="actions">
                            <button class="btn-action btn-edit" onclick="openEditModal('${book.ISBN}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-action btn-delete" onclick="openDeleteModal('${book.ISBN}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                    });
                });
        }

        function openAddModal() {
            editing = false;
            oldISBN = null;

            document.getElementById('modalTitle').innerText = "Agregar libro";
            isbn.value = '';
            nombre.value = '';
            autor.value = '';
            year.value = '';

            document.getElementById('formModal').style.display = 'flex';
        }

        function openEditModal(isbnValue) {
            editing = true;
            oldISBN = isbnValue;

            document.getElementById('modalTitle').innerText = "Editar libro";
            document.getElementById('formModal').style.display = 'flex';

            fetch('estado_libros.php?action=get&isbn=' + isbnValue)
                .then(res => res.json())
                .then(book => {
                    isbn.value = book.ISBN;
                    nombre.value = book.Name;
                    autor.value = book.Autor;
                    year.value = book.Year_edition;
                });
        }

        function closeFormModal() {
            document.getElementById('formModal').style.display = 'none';
        }

        function openDeleteModal(isbn) {
            isbnToDelete = isbn;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function saveBook() {
            let data = new FormData();

            if (editing) {
                data.append('action', 'update');
                data.append('oldISBN', oldISBN);
            } else {
                data.append('action', 'insert');
            }

            data.append('ISBN', isbn.value);
            data.append('Name', nombre.value);
            data.append('Autor', autor.value);
            data.append('Year_edition', year.value);

            fetch('estado_libros.php', {
                method: 'POST',
                body: data
            })
                .then(res => res.json())
                .then(() => {
                    closeFormModal();
                    loadBooks();

                    if (editing) {
                        showToast("Libro actualizado exitosamente");
                    } else {
                        showToast("Libro agregado exitosamente");
                    }

                    editing = false;
                    oldISBN = null;
                });
        }

        function confirmDelete() {
            fetch('estado_libros.php?action=delete&isbn=' + isbnToDelete)
                .then(res => res.json())
                .then(() => {
                    closeDeleteModal();
                    loadBooks();
                    showToast("Libro eliminado exitosamente");
                });
        }

        if (document.getElementById('searchInput')) {
            document.getElementById('searchInput').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                document.querySelectorAll('#tbodyLibros tr').forEach(row => {
                    let isbn = row.children[0].textContent.toLowerCase();
                    let nombre = row.children[1].textContent.toLowerCase();
                    let autor = row.children[2].textContent.toLowerCase();
                    let year = row.children[3].textContent.toLowerCase();
                    
                    let matches = isbn.includes(filter) || 
                                 nombre.includes(filter) || 
                                 autor.includes(filter) ||
                                 year.includes(filter);
                    
                    row.style.display = matches ? '' : 'none';
                });
            });
        }

        function openLoginModal() {
        document.getElementById('loginModal').style.display = 'flex';
        }

        function closeLoginModal() {
        document.getElementById('loginModal').style.display = 'none';
        }

        function toggleSideMenu() {
            const sideMenu = document.getElementById('sideMenu');
            sideMenu.classList.toggle('active');
        }

        function closeSideMenu() {
            const sideMenu = document.getElementById('sideMenu');
            sideMenu.classList.remove('active');
        }

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            const sideMenu = document.getElementById('sideMenu');
            const btnToggle = document.querySelector('.btn-menu-toggle');
            
            if (!sideMenu.contains(event.target) && !btnToggle.contains(event.target)) {
                closeSideMenu();
            }
        });
        // Cargar lista de libros
        loadBooks();