<?php

include_once '../Controller/CConexao.php';

$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['idProf'])) {
    $retorna = ['status' => false, 'msg' => 'Tente mais tarde!'];
} elseif (empty($dados['NomeProf'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome!'];
}  elseif (empty($dados['MateriaProf'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo disciplina!'];
} else {
    $query_prof = "UPDATE prof 
                SET NomeProf=:NomeProf,
                EmailProf=:EmailProf,
                MateriaProf=:MateriaProf
                WHERE idProf=:idProf";
    
    
    $edit_Prof = $conn->prepare($query_prof);
    $edit_Prof->bindParam(':idProf', $dados['idProf']);
    $edit_Prof->bindParam(':NomeProf', $dados['NomeProf']);
    $edit_Prof->bindParam(':EmailProf', $dados['EmailProf']);
    $edit_Prof->bindParam(':MateriaProf', $dados['MateriaProf']);

    if ($edit_Prof->execute()) {
        $retorna = ['status' => true, 'msg' => '<div class="alert alert-success" role="alert">Professor editado com sucesso</div>'];
    } else {
        $retorna = ['status' => false, 'msg' =>'<div class="alert alert-danger" role="alert">Erro: Professor não editado com sucesso</div>'];
    }
}

$var = json_encode($retorna);
echo $var;

?>