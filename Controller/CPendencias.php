<?php
try {
    // Criar uma nova instância da classe de conexão
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    if ($conn) {
        // Capturar a data atual do usuário
        date_default_timezone_set('America/Fortaleza'); // Configurar o fuso horário
        $dataAtualUsuario = date("Y-m-d"); // Usar o formato Y-m-d para comparação

        // Consulta SQL para obter os dados da tabela de empréstimo
        $sql = "SELECT e.DataEmprestimo, e.idEmprestimo, e.StatusEmprestimo,
        DATE_FORMAT(e.DataEmprestimo, '%Y-%m-%d') AS DataEmprestimo,
        DATE_FORMAT(d.DataDevolucao, '%Y-%m-%d') AS Devolucao,
        DATE_FORMAT(d.DataDevolvida, '%Y-%m-%d') AS Devolvido
        FROM emprestimo e LEFT JOIN devolucao d ON e.idEmprestimo = d.emprestimo_idEmprestimo";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                $dataEmprestimo = $row['DataEmprestimo'];
                $idEmprestimo = $row['idEmprestimo'];
                $statusEmprestimo = $row['StatusEmprestimo']; 
                $dataDevolucao = $row['Devolucao'];
                $dataDevolvido = $row['Devolvido'];

                // Comparar as datas corretamente
                if (empty($dataDevolvido) && ($dataAtualUsuario > $dataDevolucao)) {
                    $sqlUpdate = "UPDATE emprestimo SET StatusEmprestimo = 3 WHERE idEmprestimo = :idEmprestimo"; // Atrasado
                } elseif (empty($dataDevolvido)) {
                    $sqlUpdate = "UPDATE emprestimo SET StatusEmprestimo = 1 WHERE idEmprestimo = :idEmprestimo"; // Em dia
                } else {
                    $sqlUpdate = "UPDATE emprestimo SET StatusEmprestimo = 2 WHERE idEmprestimo = :idEmprestimo"; // Devolvido
                }

                // Preparar e executar a atualização
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if ($stmtUpdate) {
                    $stmtUpdate->bindParam(':idEmprestimo', $idEmprestimo, PDO::PARAM_INT);
                    $stmtUpdate->execute();
                } else {
                    echo "Erro na preparação da atualização.";
                }
            }
        } else {
            echo "Erro na execução da consulta.";
        }
    } else {
        echo "Falha na conexão com o banco de dados.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>