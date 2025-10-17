<?php
    //Incluindo conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    //Recebendo os dados da requisição
    $dados_requisicao = $_REQUEST;

    //Obtendo registros
    $query_Quantidade_Alunos = "SELECT COUNT(idAluno) AS Quantidade_al FROM aluno";
    
    $result_Quantidade_Alunos = $conn->prepare($query_Quantidade_Alunos);

    $result_Quantidade_Alunos->execute();
    $row_Quantidade_Alunos = $result_Quantidade_Alunos->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT 
        aluno.NomeAluno,
        aluno.idAluno,
        aluno.Turma_idTurma,
        aluno.EmailAluno,
        'aluno' AS tipo,
        turma.nomeTurma,
        turma.AnoTurma
    FROM aluno
    LEFT JOIN turma ON aluno.Turma_idTurma = turma.idTurma";

    if(!empty($dados_requisicao['search']['value'])){
        $sql .= " WHERE aluno.NomeAluno LIKE :NomeAluno ";
        $sql .= " OR aluno.idAluno LIKE :idAluno ";
    }
    
    $sql .= " ORDER BY aluno.NomeAluno LIMIT :inicio , :quantidade";

    $result_Alunos = $conn->prepare($sql); 

    $result_Alunos->bindParam(':inicio',$dados_requisicao['start'],PDO::PARAM_INT);
    $result_Alunos->bindParam(':quantidade',$dados_requisicao['length'],PDO::PARAM_INT);

    if(!empty($dados_requisicao['search']['value'])){
        $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
        $result_Alunos->bindParam(':NomeAluno', $valor_pesq, PDO::PARAM_STR);
        $result_Alunos->bindParam(':idAluno', $valor_pesq, PDO::PARAM_STR);
    }

    //Executar a Query
    $result_Alunos->execute();

    while($row_al = $result_Alunos->fetch(PDO::FETCH_ASSOC)) {
        //var_dump($row_Emp);
        extract($row_al);
        $registro = [];
        $registro[] = $idAluno;
        $registro[] = $NomeAluno;
        $registro[] = $AnoTurma;
        $registro[] = $nomeTurma;
        $registro[] = $EmailAluno;
        $registro[] = "<button type='button' class='btn btn-outline-warning' id='$idAluno' onclick='editAluno($idAluno)'><i class='fas fa-pen'></i></button>";
        $registro[] = " <button type='button' class='btn btn-outline-danger' id='$idAluno' onclick='excluirAluno($idAluno)'><i class='fas fa-trash-alt'></i></button>";

        $dados[] = $registro;
    }
    //var_dump($dados);

    //Criando array de informações a serem retornadas para o JavaScript
    $resultado =[
        "draw" => intval($dados_requisicao['draw']),
        "recordsTotal" => intval($row_Quantidade_Alunos['Quantidade_al']), //Quantidade de registros
        "recordsFiltered" => intval($row_Quantidade_Alunos['Quantidade_al']), //Total de registros quando tiver pesquisa
        "data" => $dados //Array de dados com o registro
    ];
    //var_dump($resultado);

    $var = json_encode($resultado);
    echo $var;
?>