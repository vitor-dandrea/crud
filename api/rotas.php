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

// Adicionar Rota
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();

        // Inserir em dim_condicoes_veiculo
        ///$sqlCondicoes = "INSERT INTO dim_condicoes_veiculo (rota_id, observacoes, data_oleo, data_inspecao_mecanica)
                         //VALUES (:rota_id, :observacoes, :data_oleo, :data_inspecao)";
        //$stmt = $pdo->prepare($sqlCondicoes);
        //$stmt->execute([
            //':rota_id' => $_POST['rota_id'],
            //':observacoes' => $_POST['observacoes'],
            //':data_oleo' => $_POST['data_oleo'],
            //':data_inspecao' => $_POST['data_inspecao']
        //]);


        // Inserir em dim_rotas
        $sqlRotas = "INSERT INTO dim_rotas (rota_id, origem, destino, distancia, tempo_viagem_estimado)
                        VALUES (:rota_id, :origem, :destino, :distancia, :tempo_viagem_estimado)";
        $stmt = $pdo->prepare($sqlRotas);
        $stmt->execute([
            ':rota_id' => $_POST['rota_id'],
            ':origem' => $_POST['origem'],
            ':destino' => $_POST['destino'],
            ':distancia' => $_POST['distancia'],
            ':tempo_viagem_estimado' => $_POST['tempo_viagem_estimado'],
        ]);

        $pdo->commit();
        echo "Rota adicionado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao adicionar Rota: " . $e->getMessage();
    }
}

// Buscar Rotas
$sql = "SELECT * FROM dim_rotas";
$stmt = $pdo->query($sql);
$rotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Rotas - Transportadora</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1> Rotas</h1>
    </header>
    <main>
        <form action="rotas.php" method="POST">
            <input type="number" name="rota_id" placeholder="ID da Rota" required>
            <input type="text" name="origem" placeholder="Origem" required>
            <input type="text" name="destino" placeholder="Destino" required>
            <input type="number" name="distancia" placeholder="Distância (km)" required>
            <input type="number" name="tempo_viagem_estimado" placeholder="Tempo Estimado" required>
            <button type="submit">Adicionar Rota</button>
        </form>

        <h2>Lista de Rotas</h2>
        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Pesquisar rotas...">
            <button onclick="filtrarrotas()">Pesquisar</button>
        </div>

        <table class="clients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Distância (km)</th>
                    <th>Tempo Estimado</th>
                
                </tr>
            </thead>
            <tbody id="rotas-body">
                <?php foreach ($rotas as $rota): ?>
                    <tr>
                        <td><?= $rota['rota_id'] ?></td>
                        <td><?= $rota['origem'] ?></td>
                        <td><?= $rota['destino'] ?></td>
                        <td><?= $rota['distancia'] ?></td>
                        <td><?= $rota['tempo_viagem_estimado'] ?></td>
                        <td>
                            <a href="deletar_rota.php?id=<?= $rota['rota_id'] ?>">Deletar</a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script>
        function filtrarrotas() {
            const searchValue = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("#rotas-body tr");

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
