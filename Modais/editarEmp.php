<?php

include_once '../Controller/CConexao.php';

$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['idEmprestimo'])) {
    $retorna = ['status' => false, 'msg' => 'Tente mais tarde!'];
} elseif (empty($dados['livroEmp'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo livro!'];
} elseif (empty($dados['turmaEmp'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo turma!'];
} elseif (empty($dados['alunoEmp'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo aluno!'];
}else {
    $query_Emp = "UPDATE aluno a
    INNER JOIN emprestimo e ON a.idAluno = e.aluno_idAluno
    INNER JOIN turma t ON a.Turma_idTurma = t.IdTurma
    SET a.Turma_idTurma=:NomeTurma,
    e.livro_idLivro=:TituloLivro,
    e.aluno_idAluno=:NomeAluno
    WHERE e.idEmprestimo=:idEmprestimo";    

    
    $edit_Emp = $conn->prepare($query_Emp);
    $edit_Emp->bindParam(':idEmprestimo', $dados['idEmprestimo']);
    $edit_Emp->bindParam(':TituloLivro', $dados['livroEmp']);
    $edit_Emp->bindParam(':NomeTurma', $dados['turmaEmp']);
    $edit_Emp->bindParam(':NomeAluno', $dados['alunoEmp']);

    if ($edit_Emp->execute()) {
        $retorna = ['status' => true, 'msg' => '<div class="alert alert-success" role="alert">Empréstimo editado com sucesso</div>'];
    } else {
        $retorna = ['status' => false, 'msg' =>'<div class="alert alert-danger" role="alert">Erro: Empréstimo não editado com sucesso</div>'];
    }
}

$var = json_encode($retorna);
echo $var;

?>