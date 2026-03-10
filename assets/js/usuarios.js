// CRUD de usuarios: listado, búsqueda, modal, crear, editar y borrar
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('tbodyUsuarios')) return;

    const tbodyUsuarios = document.getElementById('tbodyUsuarios');
    const searchUserInput = document.getElementById('searchUserInput');
    const btnAddUser = document.getElementById('btnAddUser');
    const userModal = document.getElementById('userModal');
    const userModalTitle = document.getElementById('userModalTitle');
    const userIdInput = document.getElementById('userId');
    const userNombre = document.getElementById('userNombre');
    const userEmail = document.getElementById('userEmail');
    const userTelefono = document.getElementById('userTelefono');
    const saveUserBtn = document.getElementById('saveUserBtn');

    function listUsers() {
        fetch('create_usuarios.php?action=list')
            .then(res => res.json())
            .then(data => {
                tbodyUsuarios.innerHTML = '';
                data.forEach(u => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${u.ID}</td>
                        <td>${u.Nombre}</td>
                        <td>${u.Email}</td>
                        <td>${u.Telefono || ''}</td>
                        <td>
                            <div class="actions">
                                <button class="btn-action btn-edit" data-id="${u.ID}"><i class="bi bi-pencil"></i></button>
                                <button class="btn-action btn-delete" data-id="${u.ID}"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    `;
                    tbodyUsuarios.appendChild(tr);
                });
                // attach listeners after building rows
                tbodyUsuarios.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editUser(id);
                    });
                });
                tbodyUsuarios.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteUser(id);
                    });
                });
            });
    }

    function openUserModal(isEdit = false) {
        userModal.style.display = 'flex';
        if (!isEdit) {
            userModalTitle.innerText = 'Agregar usuario';
            userIdInput.value = '';
            userNombre.value = '';
            userEmail.value = '';
            userTelefono.value = '';
        }
    }

    function closeUserModal() {
        userModal.style.display = 'none';
    }

    window.editUser = function(id) {
        fetch('create_usuarios.php?action=get&id=' + id)
            .then(res => res.json())
            .then(u => {
                userModalTitle.innerText = 'Editar usuario';
                userIdInput.value = u.ID;
                userNombre.value = u.Nombre;
                userEmail.value = u.Email;
                userTelefono.value = u.Telefono || '';
                openUserModal(true);
            });
    };

    let userToDeleteId = null;
    window.deleteUser = function(id) {
        userToDeleteId = id;
        document.getElementById('userDeleteModal').style.display = 'flex';
    };

    saveUserBtn.addEventListener('click', function() {
        const id = userIdInput.value;
        const form = new FormData();
        if (id) {
            form.append('action', 'update');
            form.append('ID', id);
        } else {
            form.append('action', 'insert');
        }
        form.append('Nombre', userNombre.value);
        form.append('Email', userEmail.value);
        form.append('Telefono', userTelefono.value);

        fetch('create_usuarios.php', {
            method: 'POST',
            body: form
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status === 'error') {
                if (typeof showToast === 'function') showToast(resp.message || 'Error');
                return;
            }
            closeUserModal();
            listUsers();
            if (typeof showToast === 'function') showToast(id ? 'Usuario actualizado' : 'Usuario creado');
        });
    });

    if (searchUserInput) {
        searchUserInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#tbodyUsuarios tr').forEach(row => {
                const id = row.children[0].textContent.toLowerCase();
                const nombre = row.children[1].textContent.toLowerCase();
                const email = row.children[2].textContent.toLowerCase();
                const telefono = row.children[3].textContent.toLowerCase();
                
                const matches = id.includes(filter) || 
                               nombre.includes(filter) || 
                               email.includes(filter) || 
                               telefono.includes(filter);
                
                row.style.display = matches ? '' : 'none';
            });
        });
    }

    btnAddUser.addEventListener('click', function(e) {
        e.preventDefault();
        openUserModal(false);
    });

    // Listener para el botón de confirmación de eliminar
    const confirmBtn = document.getElementById('confirmDeleteUserBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!userToDeleteId) return;
            fetch('create_usuarios.php?action=delete&id=' + userToDeleteId)
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 'error') {
                        if (typeof showToast === 'function') showToast(resp.message || 'Error al eliminar');
                    } else {
                        if (typeof showToast === 'function') showToast('Usuario eliminado');
                        listUsers();
                    }
                    document.getElementById('userDeleteModal').style.display = 'none';
                    userToDeleteId = null;
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('userDeleteModal').style.display = 'none';
                    userToDeleteId = null;
                });
        });
    }

    // inicializa
    listUsers();
});
