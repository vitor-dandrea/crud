<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=transportadoradb', 'root', ''); // Substitua pelo seu usuário e senha
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}

// Verifica se o parâmetro 'despacho_id' foi passado
if (isset($_GET['despacho_id'])) {
    $despacho_id = $_GET['despacho_id'];

    try {
        // Inicia a transação para garantir que a exclusão nas tabelas relacionadas aconteça de forma segura
        $pdo->beginTransaction();

        // Apagar o despacho na tabela fat_despacho
        $stmt = $pdo->prepare("DELETE FROM fat_despacho WHERE despacho_id = :despacho_id");
        $stmt->bindParam(':despacho_id', $despacho_id);
        $stmt->execute();

        // Apagar o despacho na tabela dim_detalhes_despacho
        $stmt = $pdo->prepare("DELETE FROM dim_detalhes_despacho WHERE despacho_id = :despacho_id");
        $stmt->bindParam(':despacho_id', $despacho_id);
        $stmt->execute();

        // Commit da transação
        $pdo->commit();

        // Redirecionar de volta para a página principal
        header('Location: despacho.php'); // Substitua pelo nome correto do seu arquivo de listagem
        exit();

    } catch (PDOException $e) {
        // Caso ocorra um erro, faz o rollback da transação
        $pdo->rollBack();
        echo "Erro ao excluir despacho: " . $e->getMessage();
    }
} else {
    echo "ID do despacho não encontrado.";
}
?>
