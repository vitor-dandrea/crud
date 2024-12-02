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

// Adicionar motorista
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();

        // Inserir em dim_condicoes_veiculo
        ///$sqlCondicoes = "INSERT INTO dim_condicoes_veiculo (motorista_id, observacoes, data_oleo, data_inspecao_mecanica)
                         //VALUES (:motorista_id, :observacoes, :data_oleo, :data_inspecao)";
        //$stmt = $pdo->prepare($sqlCondicoes);
        //$stmt->execute([
            //':motorista_id' => $_POST['motorista_id'],
            //':observacoes' => $_POST['observacoes'],
            //':data_oleo' => $_POST['data_oleo'],
            //':data_inspecao' => $_POST['data_inspecao']
        //]);


        // Inserir em dim_motoristas
        $sqlMotoristas = "INSERT INTO dim_motoristas (motorista_id, nome, telefone, licenca_valida, data_expiracao_licenca)
                        VALUES (:motorista_id, :nome, :telefone, :licenca_valida, :data_expiracao_licenca)";
        $stmt = $pdo->prepare($sqlMotoristas);
        $stmt->execute([
            ':motorista_id' => $_POST['motorista_id'],
            ':nome' => $_POST['nome'],
            ':telefone' => $_POST['telefone'],
            ':licenca_valida' => $_POST['licenca_valida'],
            ':data_expiracao_licenca' => $_POST['data_expiracao_licenca'],
        ]);

        $pdo->commit();
        echo "Motorista adicionado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao adicionar Motorista: " . $e->getMessage();
    }
}

// Buscar motoristas
$sql = "SELECT * FROM dim_motoristas";
$stmt = $pdo->query($sql);
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Motoristas - Transportadora</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1> Motoristas</h1>
    </header>
    <main>
        <form action="motoristas.php" method="POST">
            <input type="number" name="motorista_id" placeholder="ID do Motorista" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="telefone" placeholder="Telefone" required>
            <input type="number" name="licenca_valida" placeholder="Licença" required>
            <input type="date" name="data_expiracao_licenca" placeholder="Data de expiração da licença" required>
            <button type="submit">Adicionar Motorista</button>
        </form>

        <h2>Lista de  Motoristas</h2>
        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Pesquisar Motoristas...">
            <button onclick="filtrarVeiculos()">Pesquisar</button>
        </div>

        <table class="clients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Licença</th>
                    <th>Data de expiração da licença</th>

                </tr>
            </thead>
            <tbody id="motoristas-body">
                <?php foreach ($motoristas as $motorista): ?>
                    <tr>
                        <td><?= $motorista['motorista_id'] ?></td>
                        <td><?= $motorista['nome'] ?></td>
                        <td><?= $motorista['telefone'] ?></td>
                        <td><?= $motorista['licenca_valida'] ?></td>
                        <td><?= $motorista['data_expiracao_licenca'] ?></td>
                        <td>
                            <a href="deletar_motorista.php?id=<?= $motorista['motorista_id'] ?>">Deletar</a>
                            <a href="condicoes.php?id=<?= $motorista['motorista_id'] ?>">Condições</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script>
        function filtrarMotoristas() {
            const searchValue = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("#motoristas-body tr");

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
