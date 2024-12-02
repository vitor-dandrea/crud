<?php
// Conexão PDO
$dsn = "mysql:host=localhost;dbname=transportadoradb";
$username = "root"; // Ajuste conforme necessário
$password = ""; // Ajuste conforme necessário
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

// Adicionar veículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();

        // Inserir em dim_condicoes_veiculo
        $sqlCondicoes = "INSERT INTO dim_condicoes_veiculo (veiculo_id, observacoes, data_oleo, data_inspecao_mecanica)
                         VALUES (:veiculo_id, :observacoes, :data_oleo, :data_inspecao)";
        $stmt = $pdo->prepare($sqlCondicoes);
        $stmt->execute([
            ':veiculo_id' => $_POST['veiculo_id'],
            ':observacoes' => $_POST['observacoes'],
            ':data_oleo' => $_POST['data_oleo'],
            ':data_inspecao' => $_POST['data_inspecao']
        ]);

        // Inserir em dim_veiculos
        $sqlVeiculos = "INSERT INTO dim_veiculos (veiculo_id, placa, modelo, ano, capacidade_carga, tipo_veiculo)
                        VALUES (:veiculo_id, :placa, :modelo, :ano, :capacidade_carga, :tipo_veiculo)";
        $stmt = $pdo->prepare($sqlVeiculos);
        $stmt->execute([
            ':veiculo_id' => $_POST['veiculo_id'],
            ':placa' => $_POST['placa'],
            ':modelo' => $_POST['modelo'],
            ':ano' => $_POST['ano'],
            ':capacidade_carga' => $_POST['capacidade_carga'],
            ':tipo_veiculo' => $_POST['tipo_veiculo']
        ]);

        $pdo->commit();
        echo "Veículo adicionado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao adicionar veículo: " . $e->getMessage();
    }
}

// Buscar veículos
$sql = "SELECT * FROM dim_veiculos";
$stmt = $pdo->query($sql);
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos - Transportadora</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Veículos</h1>
    </header>
    <main>
        <form action="veiculos.php" method="POST">
            <input type="number" name="veiculo_id" placeholder="ID do Veículo" required>
            <input type="text" name="placa" placeholder="Placa" required>
            <input type="text" name="modelo" placeholder="Modelo" required>
            <input type="number" name="ano" placeholder="Ano" required>
            <input type="number" name="capacidade_carga" placeholder="Capacidade de Carga (kg)" required>
            <input type="text" name="tipo_veiculo" placeholder="Tipo de Veículo" required>
            <textarea name="observacoes" placeholder="Observações"></textarea>
            <input type="date" name="data_oleo" placeholder="Data da Última Troca de Óleo" required>
            <input type="date" name="data_inspecao" placeholder="Data da Última Inspeção Mecânica" required>
            <button type="submit">Adicionar Veículo</button>
        </form>

        <h2>Lista de Veículos</h2>
        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Pesquisar veículos...">
            <button onclick="filtrarVeiculos()">Pesquisar</button>
        </div>

        <table class="clients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Ano</th>
                    <th>Capacidade (kg)</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="vehicles-body">
                <?php foreach ($veiculos as $veiculo): ?>
                    <tr>
                        <td><?= $veiculo['veiculo_id'] ?></td>
                        <td><?= $veiculo['placa'] ?></td>
                        <td><?= $veiculo['modelo'] ?></td>
                        <td><?= $veiculo['ano'] ?></td>
                        <td><?= $veiculo['capacidade_carga'] ?></td>
                        <td><?= $veiculo['tipo_veiculo'] ?></td>
                        <td>
                            <a href="deletar_veiculo.php?id=<?= $veiculo['veiculo_id'] ?>">Deletar</a>
                            <a href="condicoes.php?id=<?= $veiculo['veiculo_id'] ?>">Condições</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script>
        function filtrarVeiculos() {
            const searchValue = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("#vehicles-body tr");

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
