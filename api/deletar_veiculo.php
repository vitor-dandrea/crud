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

    try {
        // Deletar da tabela `dim_condicoes_veiculo`
        $sqlCondicoes = "DELETE FROM dim_condicoes_veiculo WHERE veiculo_id = :veiculo_id";
        $stmt = $pdo->prepare($sqlCondicoes);
        $stmt->execute([':veiculo_id' => $veiculo_id]);

        // Deletar da tabela `dim_veiculos`
        $sqlVeiculos = "DELETE FROM dim_veiculos WHERE veiculo_id = :veiculo_id";
        $stmt = $pdo->prepare($sqlVeiculos);
        $stmt->execute([':veiculo_id' => $veiculo_id]);

        header("Location: veiculos.php");
    } catch (Exception $e) {
        die("Erro ao deletar veículo: " . $e->getMessage());
    }
} else {
    die("ID do veículo não fornecido.");
}
?>
