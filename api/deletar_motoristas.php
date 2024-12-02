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
    $motorista_id = $_GET['id'];

    try {
        // Deletar da tabela `dim_condicoes_veiculo`
        // $sqlCondicoes = "DELETE FROM dim_condicoes_veiculo WHERE motorista_id$motorista_id = :motorista_id$motorista_id";
        // $stmt = $pdo->prepare($sqlCondicoes);
        // $stmt->execute([':motorista_id$motorista_id' => $motorista_id]);

        // Deletar da tabela `dim_motoristas`
        $sqlMotoristas = "DELETE FROM dim_motoristas WHERE motorista_id$motorista_id = :motorista_id$motorista_id";
        $stmt = $pdo->prepare($sqlMotoristas);
        $stmt->execute([':motorista_id$motorista_id' => $motorista_id]);

        header("Location: motoristas.php");
    } catch (Exception $e) {
        die("Erro ao deletar motorista: " . $e->getMessage());
    }
} else {
    die("ID do motorista não fornecido.");
}
?>
