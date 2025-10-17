<?php
    //Incluindo conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    //Recebendo os dados da requisição
    $dados_requisicao = $_REQUEST;

    //Obtendo registros
    $query_Quantidade_Emp = "SELECT COUNT(idEmprestimo) AS Quantidade_emp FROM emprestimo";
    $result_Quantidade_Emp = $conn->prepare($query_Quantidade_Emp);
    $result_Quantidade_Emp->execute();
    $row_Quantidade_Emp = $result_Quantidade_Emp->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_Quantidade_Emp); 
    
    
    $sqlAlunos = "
    SELECT
        livro.NomeLivro AS TituloLivro,
        genero.NomeGenero AS NomeGenero,
        emprestimo.idEmprestimo AS idEmprestimo,
        turma.AnoTurma AS AnoTurma,
        turma.NomeTurma AS NomeTurma,
        aluno.NomeAluno AS NomeAluno,
        emprestimo.DataEmprestimo AS DataEmprestimo,
        devolucao.DataDevolucao AS DataDevolucao
    FROM emprestimo
    LEFT JOIN aluno ON emprestimo.aluno_idAluno = aluno.idAluno
    INNER JOIN turma ON aluno.Turma_idTurma = turma.IdTurma
    LEFT JOIN devolucao ON emprestimo.idEmprestimo = devolucao.emprestimo_idEmprestimo
    INNER JOIN livro ON emprestimo.livro_idLivro = livro.idLivro
    INNER JOIN genero ON livro.Genero_idGenero = genero.idGenero";
    
    //Acessa o if quando ha parametros de pesquisa
    if(!empty($dados_requisicao['search']['value'])) {
        $sqlAlunos .= " WHERE livro.NomeLivro LIKE :nomeLivro ";
        $sqlAlunos .= " OR aluno.NomeAluno LIKE :nomeAluno ";
        $sqlAlunos .= " OR emprestimo.idEmprestimo LIKE :idEmprestimo ";
    }

    $sqlAlunos .= " LIMIT :inicio , :quantidade";

    
    $result_Emprestimos = $conn->prepare($sqlAlunos);
    $result_Emprestimos->bindParam(':inicio',$dados_requisicao['start'], PDO::PARAM_INT);
    $result_Emprestimos->bindParam(':quantidade',$dados_requisicao['length'], PDO::PARAM_INT);

    if(!empty($dados_requisicao['search']['value'])) {
        $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
        $result_Emprestimos->bindParam(':nomeLivro', $valor_pesq, PDO::PARAM_STR);
        $result_Emprestimos->bindParam(':nomeAluno', $valor_pesq, PDO::PARAM_STR);
        $result_Emprestimos->bindParam(':idEmprestimo', $valor_pesq, PDO::PARAM_STR);
    }

    //Executar a QUERY
    $result_Emprestimos->execute();

    while($row_Emp = $result_Emprestimos->fetch(PDO::FETCH_ASSOC)) {
        extract($row_Emp);
        $registro = [];
        $registro[] = $TituloLivro;
        $registro[] = $NomeGenero;
        $registro[] = $idEmprestimo;
        $registro[] = $AnoTurma;
        $registro[] = $NomeTurma;
        $registro[] = $NomeAluno;
        $registro[] = date('d/m/Y', strtotime($DataEmprestimo)); //Formatando a Data de Empréstimo para d/m/Y
        $registro[] = date('d/m/Y', strtotime($DataDevolucao)); //Formatando a Data de Devolução para d/m/Y
        $registro[] = "<button type='button' class='btn btn-outline-primary' id='$idEmprestimo' onclick='visEmp($idEmprestimo)'><i class='fas fa-eye'></i></button>";
        $registro[] = "<button type='button' class='btn btn-outline-warning' id='$idEmprestimo' onclick='editEmp($idEmprestimo)'><i class='fas fa-pen'></i></button>";
        //$registro[] = "<a href='../Controller/CAlter_emprestimo.php?id={$row_Emp["idEmprestimo"]}'><button type='button' class='btn btn-outline-warning'' onclick='editEmp($idEmprestimo)'><i class='fas fa-pen'></i></button></a>";
        $registro[] = "<button type='button' class='btn btn-outline-info' id='$idEmprestimo' onclick='renovarEmp($idEmprestimo);renovar($idEmprestimo);'><i class='fas fa-clock'></i></button>";
        $registro[] = "<button type='button' class='btn btn-outline-danger' id='$idEmprestimo' onclick='excluirEmp($idEmprestimo)'><i class='fas fa-trash-alt'></i></button>";
        /*$registro[] = "<button type='button' id='$idEmprestimo' class='edit-button' onclick='editEmp($idEmprestimo)'><i class='fas fa-pencil-alt'></i></button>";*/

        $dados[] = $registro;
    }
    //var_dump($dados);

    //Criando array de informações a serem retornadas para o JavaScript
    $resultado =[
        "draw" => intval($dados_requisicao['draw']),
        "recordsTotal" => intval($row_Quantidade_Emp['Quantidade_emp']), //Quantidade de registros
        "recordsFiltered" => intval($row_Quantidade_Emp['Quantidade_emp']), //Total de registros quando tiver pesquisa
        "data" => $dados //Array de dados com o registro
    ];

    $var = json_encode($resultado);
    echo $var;
?>