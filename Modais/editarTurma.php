<?php

include_once '../Controller/CConexao.php';

$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['idTurma'])) {
    $retorna = ['status' => false, 'msg' => 'Tente mais tarde!'];
} elseif (empty($dados['AnoTurma'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo ano!'];
} elseif (empty($dados['NomeTurma'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome!'];
} else {
    try {
        $query_Turmas = "UPDATE turma 
                SET AnoTurma=:AnoTurma,
                NomeTurma=:NomeTurma
                WHERE idTurma=:idTurma";
        
        $edit_Turmas = $conn->prepare($query_Turmas);
        $edit_Turmas->bindParam(':idTurma', $dados['idTurma']);
        $edit_Turmas->bindParam(':AnoTurma', $dados['AnoTurma']);
        $edit_Turmas->bindParam(':NomeTurma', $dados['NomeTurma']);

        if ($edit_Turmas->execute()) {
            $retorna = ['status' => true, 'msg' => '<div class="alert alert-success" role="alert"> Turma editado com sucesso</div>'];
        } else {
            $retorna = ['status' => false, 'msg' => '<div class="alert alert-danger" role="alert">Erro: Turma não editado com sucesso</div>'];
        }
    } catch (PDOException $e) {
        $retorna = ['status' => false, 'msg' => '<div class="alert alert-danger" role="alert">Erro: ' . $e->getMessage() . '</div>'];
    }
}

$var = json_encode($retorna);
echo $var;

?>