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
    
    
    $sqlAlunos = "SELECT e.idEmprestimo AS ID, 
    COALESCE(a.NomeAluno, p.Nomeprof) AS Leitor, 
    COALESCE(a.idAluno, p.idprof) AS idLeitor,
    COALESCE(ta.NomeTurma, tp.NomeTurma) AS Turma, 
    ta.AnoTurma AS Ano,
    l.NomeLivro AS Livro, 
    l.Tombo as Tombo,
    DATE_FORMAT(e.DataEmprestimo, '%d/%m/%Y') AS DataEmprestimoFormatada,
    IFNULL(DATE_FORMAT(d.DataDevolucao, '%d/%m/%Y'), '--/--/----') AS DataDevolucaoFormatada,
    IFNULL(DATE_FORMAT(d.DataDevolvida, '%d/%m/%Y'), '--/--/----') AS DataDevolvidaFormatada,
    e.StatusEmprestimo as Estado
    FROM emprestimo e
    LEFT JOIN aluno a ON e.aluno_idAluno = a.idAluno
    LEFT JOIN prof p ON e.prof_idprof = p.idprof
    LEFT JOIN livro l ON e.livro_idLivro = l.idLivro
    LEFT JOIN turma ta ON Turma_idTurma = ta.IdTurma
    LEFT JOIN turma tp ON Turma_idTurma = tp.IdTurma
    LEFT JOIN devolucao d ON e.idEmprestimo = d.emprestimo_idEmprestimo
    WHERE e.StatusEmprestimo = 2
    ";

    if(!empty($dados_requisicao['search']['value'])){
        $sqlAlunos .= " AND l.NomeLivro like :livro ";
    }

    $sqlAlunos .= " ORDER BY ID desc LIMIT :inicio , :quantidade";


    $result_Emprestimos = $conn->prepare($sqlAlunos);

    $result_Emprestimos->bindParam(':inicio',$dados_requisicao['start'],PDO::PARAM_INT);
    $result_Emprestimos->bindParam(':quantidade',$dados_requisicao['length'],PDO::PARAM_INT);

    if(!empty($dados_requisicao['search']['value'])){
        $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
        $result_Emprestimos->bindParam(':livro', $valor_pesq, PDO::PARAM_STR);
    }

    $result_Emprestimos->execute();

    while($row_Emp = $result_Emprestimos->fetch(PDO::FETCH_ASSOC)) {
        $estado = "";
        $classeCSS = "";

        // Lógica para definir o estado e a classe CSS
        switch ($row_Emp['Estado']) {
            case 1:
                $estado = "Ativo";
                $classeCSS = "status process";
                break;
            case 3:
                $estado = "Pendente";
                $classeCSS = "status pending";
                break;
            case 2:
                $estado = "Devolvido";
                $classeCSS = "status completed";
                break;
        }

        //var_dump($row_Emp);
        extract($row_Emp);
        $registro = [];
        $registro[] = $ID;
        $registro[] = $Tombo;
        $registro[] = $Leitor;
        $registro[] = $Ano;
        $registro[] = $Turma;
        $registro[] = $Livro;
        $registro[] = $DataDevolucaoFormatada;
        $registro[] = $DataDevolvidaFormatada;
        $registro[] = "<span class='$classeCSS'> $estado </span>";

        
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
    //var_dump($resultado);

    $var = json_encode($resultado);
    echo $var;
?>