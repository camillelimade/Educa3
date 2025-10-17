<?php
    //Incluindo conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    //Recebendo os dados da requisição
    $dados_requisicao = $_REQUEST;

    //Obtendo registros
    $query_Quantidade_turma = "SELECT COUNT(idTurma) AS Quantidade_turma FROM turma";
    
    $result_Quantidade_turma = $conn->prepare($query_Quantidade_turma);

    $result_Quantidade_turma->execute();
    $row_Quantidade_turma = $result_Quantidade_turma->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT idTurma, NomeTurma, AnoTurma, AnodeInicio FROM turma";

    if(!empty($dados_requisicao['search']['value'])){
        $sql .= " WHERE idTurma LIKE :id ";
    }
    
    $sql .= " ORDER BY AnoTurma, NomeTurma LIMIT :inicio , :quantidade";
        
    $result_turma = $conn->prepare($sql);

    $result_turma->bindParam(':inicio',$dados_requisicao['start'],PDO::PARAM_INT);
    $result_turma->bindParam(':quantidade',$dados_requisicao['length'],PDO::PARAM_INT);

    if(!empty($dados_requisicao['search']['value'])){
        $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
        $result_turma->bindParam(':id', $valor_pesq, PDO::PARAM_STR);
    }

    //Executar a Query
    $result_turma->execute();

    while($row_turma = $result_turma->fetch(PDO::FETCH_ASSOC)) {
        //var_dump($row_Emp);
        extract($row_turma);
        $registro = [];
        $registro[] = $idTurma;
        $registro[] = $AnoTurma;
        $registro[] = $NomeTurma;
        $registro[] = $AnodeInicio;
        $registro[] = "<button type='button' class='btn btn-outline-warning' id='$idTurma' onclick='editTurma($idTurma)'><i class='fas fa-pen'></i></button>";
        $registro[] = " <button type='button' class='btn btn-outline-danger' id='$idTurma' onclick='excluirTurma($idTurma)'><i class='fas fa-trash-alt'></i></button>";

        $dados[] = $registro;
    }
    //var_dump($dados);

    //Criando array de informações a serem retornadas para o JavaScript
    $resultado =[
        "draw" => intval($dados_requisicao['draw']),
        "recordsTotal" => intval($row_Quantidade_turma['Quantidade_turma']), //Quantidade de registros
        "recordsFiltered" => intval($row_Quantidade_turma['Quantidade_turma']), //Total de registros quando tiver pesquisa
        "data" => $dados //Array de dados com o registro
    ];
    //var_dump($resultado);

    $var = json_encode($resultado);
    echo $var;
?>