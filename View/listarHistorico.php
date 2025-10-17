<?php
    //Incluindo conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    //Recebendo os dados da requisição
    $dados_requisicao = $_REQUEST;

    //Obtendo registros
    $query_Quantidade_emprestimo = "SELECT COUNT(idEmprestimo) AS Quantidade_emp FROM emprestimo";
    
    $result_Quantidade_emprestimo = $conn->prepare($query_Quantidade_emprestimo);

    $result_Quantidade_emprestimo->execute();
    $row_Quantidade_emprestimo = $result_Quantidade_emprestimo->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT
    livro.NomeLivro AS TituloLivro,
    emprestimo.idEmprestimo,
    aluno.NomeAluno AS NomeAluno,
    prof.NomeProf AS NomeProfessor,
    DATE_FORMAT(emprestimo.DataEmprestimo, '%d/%m/%Y') AS DataEmprestimoFormatada,
    IFNULL(DATE_FORMAT(devolucao.DataDevolucao, '%d/%m/%Y'), '--/--/----') AS DataDevolucaoFormatada,
    IFNULL(DATE_FORMAT(devolucao.DataDevolvida, '%d/%m/%Y'), '--/--/----') AS DataDevolvidaFormatada,
    CASE
        WHEN emprestimo.StatusEmprestimo = 1 THEN 'Ativo'
        WHEN emprestimo.StatusEmprestimo = 2 THEN 'Devolvido'
    END AS Estado
FROM emprestimo
LEFT JOIN aluno ON emprestimo.aluno_idAluno = aluno.idAluno
INNER JOIN livro ON emprestimo.livro_idLivro = livro.idLivro
LEFT JOIN prof ON emprestimo.prof_idProf = prof.idProf
LEFT JOIN devolucao ON emprestimo.idEmprestimo = devolucao.emprestimo_idEmprestimo";

    if(!empty($dados_requisicao['search']['value'])){
        $sql .= " WHERE aluno.NomeAluno LIKE :nomeA ";
        $sql .= " OR livro.NomeLivro LIKE :nomeL ";
    }
    
    $sql .= " LIMIT :inicio , :quantidade";
        
    $result_emprestimo = $conn->prepare($sql);

    $result_emprestimo->bindParam(':inicio',$dados_requisicao['start'],PDO::PARAM_INT);
    $result_emprestimo->bindParam(':quantidade',$dados_requisicao['length'],PDO::PARAM_INT);

    if(!empty($dados_requisicao['search']['value'])){
        $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
        $result_emprestimo->bindParam(':nomeA', $valor_pesq, PDO::PARAM_STR);
        $result_emprestimo->bindParam(':nomeL', $valor_pesq, PDO::PARAM_STR);
    }

    //Executar a Query
    $result_emprestimo->execute();

    while($row_emp = $result_emprestimo->fetch(PDO::FETCH_ASSOC)) {
        //var_dump($row_Emp);
        extract($row_emp);
        $registro = [];
        $registro[] = $idEmprestimo;
        $registro[] = $TituloLivro;
        $registro[] = $NomeAluno;
        $registro[] = $DataEmprestimoFormatada;
        $registro[] = $DataDevolvidaFormatada;
        $registro[] = $Estado;

        $dados[] = $registro;
    }
    //var_dump($dados);

    //Criando array de informações a serem retornadas para o JavaScript
    $resultado =[
        "draw" => intval($dados_requisicao['draw']),
        "recordsTotal" => intval($row_Quantidade_emprestimo['Quantidade_emp']), //Quantidade de registros
        "recordsFiltered" => intval($row_Quantidade_emprestimo['Quantidade_emp']), //Total de registros quando tiver pesquisa
        "data" => $dados //Array de dados com o registro
    ];
    //var_dump($resultado);

    $var = json_encode($resultado);
    echo $var;
?>