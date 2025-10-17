<?php

include '../Controller/CConexao.php';
$conexao = new CConexao();
$conn = $conexao->getConnection();

$idEmp = filter_input(INPUT_GET, "idEmprestimo", FILTER_SANITIZE_NUMBER_INT);

if(!empty($idEmp)){
    $sql = "DELETE FROM emprestimo WHERE idEmprestimo=:idEmp";
    $result = $conn->prepare($sql);
    $result->bindParam(":idEmp", $idEmp, PDO::PARAM_INT);

    if($result->execute()){
        $retorna = ['status' => true, 'msg' => "<div class='alert alert-success' role='alert'>Registro apagado com sucesso!</div>"];
    }else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Registro não apagado</div>"];
    }

}else{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'> Erro: Registro não acessado</div>"];
}

echo json_encode($retorna);