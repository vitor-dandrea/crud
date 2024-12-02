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

// Verifica se o ID da rota foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erro: ID da rota não fornecido ou inválido.");
}

$rota_id = $_GET['id'];

try {
    // Deletar rota pelo ID
    $sqlRotas = "DELETE FROM dim_rotas WHERE rota_id = :rota_id";
    $stmt = $pdo->prepare($sqlRotas);
    $stmt->execute([':rota_id' => $rota_id]);

    // Redireciona após a exclusão
    header("Location: rotas.php");
    exit;
} catch (Exception $e) {
    die("Erro ao deletar rota: " . $e->getMessage());
}
?>
