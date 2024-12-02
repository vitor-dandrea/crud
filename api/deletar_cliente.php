<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $dsn = "mysql:host=localhost;dbname=transportadoradb";
    $username = "root"; // Altere se necessário
    $password = ""; // Altere se necessário
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        $sql = "DELETE FROM DIM_CLIENTES WHERE cliente_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        header("Location: clientes.php");
    } catch (PDOException $e) {
        die("Erro ao conectar: " . $e->getMessage());
    }
}
?>
