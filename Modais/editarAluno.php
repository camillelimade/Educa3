<?php

include_once '../Controller/CConexao.php';

$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['idAluno'])) {
    $retorna = ['status' => false, 'msg' => 'Tente mais tarde!'];
} elseif (empty($dados['NomeAluno'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome aluno!'];
}elseif (empty($dados['AnoTurma'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome!'];
}elseif (empty($dados['nomeTurma'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome turma!'];
}  else {
    $query_Alunos = "UPDATE aluno
                LEFT JOIN turma ON aluno.Turma_idTurma = turma.idTurma
                SET NomeAluno=:NomeAluno,
                turma.AnoTurma=:AnoTurma,
                turma.nomeTurma=:nomeTurma
                WHERE idAluno=:idAluno";
    
    
    $edit_Alunos = $conn->prepare($query_Alunos);
    $edit_Alunos->bindParam(':idAluno', $dados['idAluno']);
    $edit_Alunos->bindParam(':NomeAluno', $dados['NomeAluno']);
    $edit_Alunos->bindParam(':AnoTurma', $dados['AnoTurma']);
    $edit_Alunos->bindParam(':nomeTurma', $dados['nomeTurma']);

    if ($edit_Alunos->execute()) {
        $retorna = ['status' => true, 'msg' => '<div class="alert alert-success" role="alert">Aluno editado com sucesso</div>'];
    } else {
        $retorna = ['status' => false, 'msg' =>'<div class="alert alert-danger" role="alert">Erro: Aluno não editado com sucesso</div>'];
    }
}

$var = json_encode($retorna);
echo $var;

?>