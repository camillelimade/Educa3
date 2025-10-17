<?php 
    //Conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    $idProf = filter_input(INPUT_GET, "idProf", FILTER_SANITIZE_NUMBER_INT);

    if(!empty($idProf)){
        $sqlProf = "SELECT idProf, NomeProf, EmailProf, MateriaProf from prof Where idProf=:idProf";

        $result_Prof = $conn->prepare($sqlProf);
        $result_Prof->bindParam(':idProf', $idProf);
        $result_Prof->execute();

        if ($result_Prof and ($result_Prof->rowCount() != 0)){
            $row_prof = $result_Prof->fetch(PDO::FETCH_ASSOC);
            $retorna = ['status' => true, 'dados' => $row_prof];
        } else{
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum professor encontrado 2 !</div>"];
        }

    } else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum professor encontrado!</div>"];
    }

    $var = json_encode($retorna);
    echo $var;
?>