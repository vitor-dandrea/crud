/*document.addEventListener('DOMContentLoaded', () => {
    const createBtn = document.getElementById('createBtn');
    const dispatchForm = document.getElementById('dispatchForm');
    const dispatchTable = document.getElementById('dispatchTable').querySelector('tbody');

    const fetchData = async () => {
        const response = await fetch('api/read.php');
        const data = await response.json();
        dispatchTable.innerHTML = '';
        data.forEach(d => {
            dispatchTable.innerHTML += `
                <tr>
                    <td>${d.despacho_id}</td>
                    <td>${d.peso}</td>
                    <td>${d.tipo_produto}</td>
                    <td>${d.valor}</td>
                    <td>
                        <button onclick="deleteDispatch(${d.despacho_id})">Excluir</button>
                    </td>
                </tr>`;
        });
    };

    const createDispatch = async () => {
        const formData = new FormData(dispatchForm);
        const response = await fetch('api/create.php', {
            method: 'POST',
            body: formData,
        });
        if (response.ok) {
            fetchData();
        }
    };

    createBtn.addEventListener('click', createDispatch);
    fetchData();
});

const deleteDispatch = async (id) => {
    const response = await fetch('api/delete.php?id=' + id, { method: 'GET' });
    if (response.ok) {
        document.dispatchEvent(new Event('DOMContentLoaded'));
    }
};
*/