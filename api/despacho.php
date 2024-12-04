<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=transportadoradb', 'root', ''); // Substitua pelo seu usuário e senha
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}

// Função para adicionar despacho
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Verificar se o despacho_id já existe em dim_detalhes_despacho
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM dim_detalhes_despacho WHERE despacho_id = :despacho_id");
        $stmt->bindParam(':despacho_id', $_POST['despacho_id']);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Se o despacho_id não existe, insira-o na tabela dim_detalhes_despacho
        if ($count == 0) {
            $stmt = $pdo->prepare("INSERT INTO dim_detalhes_despacho (despacho_id) VALUES (:despacho_id)");
            $stmt->bindParam(':despacho_id', $_POST['despacho_id']);
            $stmt->execute();
        }

        // Agora, insira o despacho na tabela fat_despacho
        $stmt = $pdo->prepare("INSERT INTO fat_despacho (despacho_id, cliente_id, motorista_id, rota_id, veiculo_id, data_despacho)
                                VALUES (:despacho_id, :cliente_id, :motorista_id, :rota_id, :veiculo_id, :data_despacho)");

        // Bind dos parâmetros
        $stmt->bindParam(':despacho_id', $_POST['despacho_id']);
        $stmt->bindParam(':cliente_id', $_POST['cliente_id']);
        $stmt->bindParam(':motorista_id', $_POST['motorista_id']);
        $stmt->bindParam(':rota_id', $_POST['rota_id']);
        $stmt->bindParam(':veiculo_id', $_POST['veiculo_id']);
        $stmt->bindParam(':data_despacho', $_POST['data_despacho']);

        // Executando a query
        $stmt->execute();
        echo "Despacho adicionado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao adicionar despacho: " . $e->getMessage();
    }
}

// Exibindo todos os despachos cadastrados
$despachos = $pdo->query("SELECT f.despacho_id, c.nome AS cliente, m.nome AS motorista, r.origem AS origem, r.destino AS destino, 
                                  v.modelo AS veiculo, f.data_despacho 
                           FROM fat_despacho f
                           JOIN dim_clientes c ON f.cliente_id = c.cliente_id
                           JOIN dim_motoristas m ON f.motorista_id = m.motorista_id
                           JOIN dim_veiculos v ON f.veiculo_id = v.veiculo_id
                           JOIN dim_rotas r ON f.rota_id = r.rota_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Despacho</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Função para filtrar os despachos na tabela
        function filtrarTabela() {
            const filtro = document.getElementById('filtroDespacho').value.toLowerCase();
            const linhas = document.querySelectorAll('.clients-table tbody tr');

            linhas.forEach(linha => {
                const textoLinha = linha.textContent.toLowerCase();
                linha.style.display = textoLinha.includes(filtro) ? '' : 'none';
            });
        }
    </script>
</head>
<body>
    <header>
        <h1>Adicionar Despacho</h1>
    </header>

    <main>
        <form action="" method="POST">
            <label for="despacho_id">ID do Despacho:</label>
            <input type="text" name="despacho_id" required><br><br>

            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" required>
                <?php
                $clientes = $pdo->query("SELECT cliente_id, nome FROM dim_clientes")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clientes as $cliente) {
                    echo "<option value='" . $cliente['cliente_id'] . "'>" . $cliente['nome'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="motorista_id">Motorista:</label>
            <select name="motorista_id" required>
                <?php
                $motoristas = $pdo->query("SELECT motorista_id, nome FROM dim_motoristas")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($motoristas as $motorista) {
                    echo "<option value='" . $motorista['motorista_id'] . "'>" . $motorista['nome'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="rota_id">Rota:</label>
            <select name="rota_id" required>
                <?php
                $rotas = $pdo->query("SELECT rota_id, origem, destino FROM dim_rotas")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rotas as $rota) {
                    echo "<option value='" . $rota['rota_id'] . "'>" . $rota['origem'] . " - " . $rota['destino'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="veiculo_id">Veículo:</label>
            <select name="veiculo_id" required>
                <?php
                $veiculos = $pdo->query("SELECT veiculo_id, modelo FROM dim_veiculos")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($veiculos as $veiculo) {
                    echo "<option value='" . $veiculo['veiculo_id'] . "'>" . $veiculo['modelo'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="data_despacho">Data do Despacho:</label>
            <input type="date" name="data_despacho" required><br><br>

            <button type="submit">Adicionar Despacho</button>
        </form>

        <!-- Barra de pesquisa -->
        <div class="search-bar">
            <input type="text" id="filtroDespacho" placeholder="Filtrar despachos..." onkeyup="filtrarTabela()">
            <button onclick="filtrarTabela()">Pesquisar</button>
        </div>

        <h2>Despachos Cadastrados</h2>
        <table class="clients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Motorista</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Veículo</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($despachos as $despacho) {
                    echo "<tr>";
                    echo "<td>" . $despacho['despacho_id'] . "</td>";
                    echo "<td>" . $despacho['cliente'] . "</td>";
                    echo "<td>" . $despacho['motorista'] . "</td>";
                    echo "<td>" . $despacho['origem'] . "</td>";
                    echo "<td>" . $despacho['destino'] . "</td>";
                    echo "<td>" . $despacho['veiculo'] . "</td>";
                    echo "<td>" . $despacho['data_despacho'] . "</td>";
                    echo "<td>";
                    echo "<a href='deletar_despacho.php?despacho_id=" . $despacho['despacho_id'] . "' class='deletar' onclick='return confirm(\"Tem certeza que deseja deletar este despacho?\")'>Deletar</a>";

                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
