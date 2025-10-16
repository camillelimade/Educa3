<?php
if (isset($_GET['id']) && isset($_GET['data'])) {
    $idEmp = $_GET['id'];
    $dataRenovada = $_GET['data'];

    require_once('CConexao.php');

    try {
        $conexao = new CConexao();
        $conn = $conexao->getConnection();

        $sql = "UPDATE devolucao SET DataDevolucao = DATE_FORMAT(:dataDevolucao, '%Y/%m/%d') WHERE emprestimo_idEmprestimo = :idEmp";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dataDevolucao', $dataRenovada);
        $stmt->bindParam(':idEmp', $idEmp);

        $stmt->execute();

        if ($stmt->rowCount() >= 0) {
            echo json_encode(['success' => true, 'message' => 'Registro alterado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Falha na atualização']);
        }
    } catch (PDOException $e) {
        error_log("Erro na atualização da data: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro na atualização']);
    }
}
?>