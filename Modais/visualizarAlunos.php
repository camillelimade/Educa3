<?php 
    //Conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    $idAluno = filter_input(INPUT_GET, "idAluno", FILTER_SANITIZE_NUMBER_INT);

    if(!empty($idAluno)){
        $sqlAlunos = "SELECT 
        aluno.NomeAluno,
        aluno.idAluno AS idAluno,
        aluno.Turma_idTurma,
        aluno.EmailAluno,
        'aluno' AS tipo,
        turma.nomeTurma,
        turma.AnoTurma
    FROM aluno
    LEFT JOIN turma ON aluno.Turma_idTurma = turma.idTurma
    WHERE idAluno = :idAluno";

        $result_Aluno = $conn->prepare($sqlAlunos);
        $result_Aluno->bindParam(':idAluno', $idAluno);
        $result_Aluno->execute();

        if ($result_Aluno and ($result_Aluno->rowCount() != 0)){
            $row_Aluno = $result_Aluno->fetch(PDO::FETCH_ASSOC);
            $retorna = ['status' => true, 'dados' => $row_Aluno];
        } else{
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum aluno encontrado 2 !</div>"];
        }

    } else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum aluno encontrado!</div>"];
    }

    $var = json_encode($retorna);
    echo $var;
?>