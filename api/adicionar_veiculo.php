<!--
<?php
require 'connection.php'; // Arquivo de conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $capacidade_carga = $_POST['capacidade_carga'];
    $tipo_veiculo = $_POST['tipo_veiculo'];
    $observacoes = $_POST['observacoes'] ?? null;
    $data_oleo = $_POST['data_oleo'] ?? null;
    $data_inspecao = $_POST['data_inspecao'] ?? null;

    try {
        // Inserir na tabela `dim_veiculos`
        $stmt = $pdo->prepare("INSERT INTO dim_veiculos (placa, modelo, ano, capacidade_carga, tipo_veiculo) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$placa, $modelo, $ano, $capacidade_carga, $tipo_veiculo]);

        // Obter o ID do veículo recém-inserido
        $veiculo_id = $pdo->lastInsertId();

        // Inserir na tabela `dim_condicoes_veiculo`
        $stmt = $pdo->prepare("INSERT INTO dim_condicoes_veiculo (veiculo_id, observacoes, data_oleo, data_inspecao_mecanica) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$veiculo_id, $observacoes, $data_oleo, $data_inspecao]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
->