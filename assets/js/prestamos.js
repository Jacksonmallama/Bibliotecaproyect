// CRUD de préstamos: listado, búsqueda, modal, crear, editar y borrar

document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('tbodyPrestamos')) return;

    const tbodyPrestamos = document.getElementById('tbodyPrestamos');
    const searchPrestamosInput = document.getElementById('searchPrestamosInput');
    const btnAddPrestamo = document.getElementById('btnAddPrestamo');

    const prestamoModal = document.getElementById('prestamoModal');
    const prestamoModalTitle = document.getElementById('prestamoModalTitle');
    const prestamoIdInput = document.getElementById('prestamoId');
    const prestamoUsuario = document.getElementById('prestamoUsuario');
    const prestamoLibro = document.getElementById('prestamoLibro');
    const prestamoFecha = document.getElementById('prestamoFecha');
    const prestamoFechaDevolucion = document.getElementById('prestamoFechaDevolucion');
    const prestamoEstado = document.getElementById('prestamoEstado');
    const savePrestamoBtn = document.getElementById('savePrestamoBtn');

    let prestamosData = [];

    function listPrestamos() {
        fetch('estado_prestamos.php?action=list')
            .then(res => res.json())
            .then(data => {
                prestamosData = data;
                renderPrestamosTable(data);
            });
    }

    function renderPrestamosTable(data) {
        tbodyPrestamos.innerHTML = '';
        data.forEach(p => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${p.id_prestamo}</td>
                <td>${p.usuario_nombre}</td>
                <td>${p.libro_nombre}</td>
                <td>${p.fecha_prestamo || ''}</td>
                <td>${p.fecha_devolucion || ''}</td>
                <td>${p.estado}</td>
                <td>
                    <div class="actions">
                        <button class="btn-action btn-edit" data-id="${p.id_prestamo}"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action btn-delete" data-id="${p.id_prestamo}"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            `;
            tbodyPrestamos.appendChild(tr);
        });

        tbodyPrestamos.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editPrestamo(id);
            });
        });

        tbodyPrestamos.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deletePrestamo(id);
            });
        });
    }

    function loadUsuarios() {
        return fetch('create_usuarios.php?action=list')
            .then(res => res.json())
            .then(data => {
                prestamoUsuario.innerHTML = '<option value="">(Seleccione un usuario)</option>';
                data.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.ID;
                    opt.textContent = `${u.Nombre} (${u.Email})`;
                    prestamoUsuario.appendChild(opt);
                });
            });
    }

    function loadLibros() {
        return fetch('estado_libros.php?action=list')
            .then(res => res.json())
            .then(data => {
                prestamoLibro.innerHTML = '<option value="">(Seleccione un libro)</option>';
                data.forEach(l => {
                    const opt = document.createElement('option');
                    opt.value = l.ISBN;
                    opt.textContent = `${l.Name} — ${l.Autor}`;
                    prestamoLibro.appendChild(opt);
                });
            });
    }

    function openPrestamoModal(isEdit = false) {
        prestamoModal.style.display = 'flex';

        if (!isEdit) {
            prestamoModalTitle.innerText = 'Agregar préstamo';
            prestamoIdInput.value = '';
            prestamoUsuario.value = '';
            prestamoLibro.value = '';
            prestamoFecha.value = new Date().toISOString().split('T')[0];
            prestamoFechaDevolucion.value = '';
            prestamoEstado.value = 'prestado';
        }
    }

    function closePrestamoModal() {
        prestamoModal.style.display = 'none';
    }

    function editPrestamo(id) {
        fetch('estado_prestamos.php?action=get&id=' + id)
            .then(res => res.json())
            .then(p => {
                prestamoModalTitle.innerText = 'Editar préstamo';
                prestamoIdInput.value = p.id_prestamo;
                prestamoUsuario.value = p.id_usuario;
                prestamoLibro.value = p.isbn;
                prestamoFecha.value = p.fecha_prestamo || '';
                prestamoFechaDevolucion.value = p.fecha_devolucion || '';
                prestamoEstado.value = p.estado || 'prestado';
                openPrestamoModal(true);
            });
    }

    let prestamoToDeleteId = null;
    function deletePrestamo(id) {
        const confirmed = confirm('¿Deseas eliminar este préstamo?');
        if (!confirmed) return;

        fetch('estado_prestamos.php?action=delete&id=' + id)
            .then(res => res.json())
            .then(() => {
                if (typeof showToast === 'function') showToast('Préstamo eliminado');
                listPrestamos();
            });
    }

    savePrestamoBtn.addEventListener('click', function() {
        const id = prestamoIdInput.value;
        const form = new FormData();

        if (id) {
            form.append('action', 'update');
            form.append('id_prestamo', id);
        } else {
            form.append('action', 'insert');
        }

        form.append('id_usuario', prestamoUsuario.value);
        form.append('isbn', prestamoLibro.value);
        form.append('fecha_prestamo', prestamoFecha.value);
        form.append('fecha_devolucion', prestamoFechaDevolucion.value);
        form.append('estado', prestamoEstado.value);

        fetch('estado_prestamos.php', {
            method: 'POST',
            body: form
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status === 'error') {
                if (typeof showToast === 'function') showToast(resp.message || 'Error');
                return;
            }
            closePrestamoModal();
            listPrestamos();
            if (typeof showToast === 'function') showToast(id ? 'Préstamo actualizado' : 'Préstamo agregado');
        });
    });

    if (searchPrestamosInput) {
        searchPrestamosInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#tbodyPrestamos tr').forEach(row => {
                const cells = Array.from(row.children).slice(1, 6); // ignore ID
                const text = cells.map(c => c.textContent.toLowerCase()).join(' ');
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    btnAddPrestamo.addEventListener('click', function(e) {
        e.preventDefault();
        Promise.all([loadUsuarios(), loadLibros()]).then(() => openPrestamoModal(false));
    });

    // Inicializa
    listPrestamos();
});
