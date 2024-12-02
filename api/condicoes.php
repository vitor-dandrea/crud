<?php
// Conexão PDO
$dsn = "mysql:host=localhost;dbname=transportadoradb";
$username = "root";
$password = "";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

// Verificar se o ID foi passado
if (isset($_GET['id'])) {
    $veiculo_id = $_GET['id'];

    // Buscar condições do veículo
    $sql = "SELECT * FROM dim_condicoes_veiculo WHERE veiculo_id = :veiculo_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':veiculo_id' => $veiculo_id]);
    $condicoes = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die("ID do veículo não fornecido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condições do Veículo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Condições do Veículo</h1>
    </header>
    <main>
        <?php if ($condicoes): ?>
            <h2>Detalhes</h2>
            <ul>
                <li><strong>Observações:</strong> <?= $condicoes['observacoes'] ?></li>
                <li><strong>Data da Última Troca de Óleo:</strong> <?= $condicoes['data_oleo'] ?></li>
                <li><strong>Data da Última Inspeção Mecânica:</strong> <?= $condicoes['data_inspecao_mecanica'] ?></li>
            </ul>
        <?php else: ?>
            <p>Condições do veículo não encontradas.</p>
        <?php endif; ?>
        <a href="veiculos.php">Voltar</a>
    </main>
</body>
</html>
