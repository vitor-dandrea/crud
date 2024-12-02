<?php
// Conexão PDO
$dsn = "mysql:host=localhost;dbname=transportadoradb";
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

// Adicionar cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO DIM_CLIENTES (nome, endereco, telefone, `e-mail`, cep) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['nome'], $_POST['endereco'], $_POST['telefone'], $_POST['email'], $_POST['cep']]);
}

// Buscar clientes
$sql = "SELECT * FROM DIM_CLIENTES";
$stmt = $pdo->query($sql);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Transportadora</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Clientes</h1>
    </header>
    <main>
        <form action="clientes.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="endereco" placeholder="Endereço" required>
            <input type="text" name="telefone" placeholder="Telefone" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="text" name="cep" placeholder="CEP" required>
            <button type="submit">Adicionar Cliente</button>
        </form>
        
        <h2>Lista de Clientes</h2>
        
        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Pesquisar clientes...">
            <button onclick="filtrarClientes()">Pesquisar</button>
        </div>
        
        <table class="clients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>CEP</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="clients-body">
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['cliente_id'] ?></td>
                        <td><?= $cliente['nome'] ?></td>
                        <td><?= $cliente['endereco'] ?></td>
                        <td><?= $cliente['telefone'] ?></td>
                        <td><?= $cliente['e-mail'] ?></td>
                        <td><?= $cliente['cep'] ?></td>
                        <td>
                            <a href="deletar_cliente.php?id=<?= $cliente['cliente_id'] ?>">Deletar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script>
        function filtrarClientes() {
            const searchValue = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("#clients-body tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                let match = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                    }
                });

                row.style.display = match ? "" : "none";
            });
        }
    </script>
</body>
</html>
