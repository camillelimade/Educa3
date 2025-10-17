<?php 
    //Conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    $idTurma = filter_input(INPUT_GET, "idTurma", FILTER_SANITIZE_NUMBER_INT);

    if(!empty($idTurma)){
        $sqlTurmas = "SELECT idTurma, NomeTurma, AnoTurma, AnodeInicio FROM turma WHERE idTurma = :idTurma";

        $result_Turma = $conn->prepare($sqlTurmas);
        $result_Turma->bindParam(':idTurma', $idTurma);
        $result_Turma->execute();

        if ($result_Turma and ($result_Turma->rowCount() != 0)){
            $row_turma = $result_Turma->fetch(PDO::FETCH_ASSOC);
            $retorna = ['status' => true, 'dados' => $row_turma];
        } else{
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhuma turma encontrado 2 !</div>"];
        }

    } else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhuma turma encontrado!</div>"];
    }

    $var = json_encode($retorna);
    echo $var;
?>