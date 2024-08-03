document.addEventListener('DOMContentLoaded', function() {
    // Manejo de mensajes de éxito y error
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('success');

    if (errorMessage) {
        showAlert(errorMessage, 'error', function() {
            window.location.href = './?controlador=ventas&accion=informacion';
        });
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=ventas&accion=informacion';
        });
    }

    // Paginación
    const rowsPerPage = 10;
    const table = document.getElementById('recibosTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const pagination = document.getElementById('pagination');
    const pageCount = Math.ceil(rows.length / rowsPerPage);

    function showPage(page) {
        for (let i = 0; i < rows.length; i++) {
            rows[i].style.display = 'none';
        }
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        for (let i = start; i < end && i < rows.length; i++) {
            rows[i].style.display = '';
        }
    }

    function createPagination() {
        pagination.innerHTML = '';
        for (let i = 1; i <= pageCount; i++) {
            const li = document.createElement('li');
            li.className = 'page-item';
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.innerText = i;
            a.addEventListener('click', function(e) {
                e.preventDefault();
                showPage(i);
            });
            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    createPagination();
    showPage(1);
});
