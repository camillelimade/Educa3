<?php 
    //Conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    $idEmprestimo = filter_input(INPUT_GET, "idEmprestimo", FILTER_SANITIZE_NUMBER_INT);

    if(!empty($idEmprestimo)){
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
        INNER JOIN genero ON livro.Genero_idGenero = genero.idGenero
        WHERE idEmprestimo = :idEmprestimo LIMIT 1
        ";

        $result_Emprestimo = $conn->prepare($sqlAlunos);
        $result_Emprestimo->bindParam(':idEmprestimo', $idEmprestimo);
        $result_Emprestimo->execute();

        if ($result_Emprestimo and ($result_Emprestimo->rowCount() != 0)){
            $row_emprestimo = $result_Emprestimo->fetch(PDO::FETCH_ASSOC);
            $retorna = ['status' => true, 'dados' => $row_emprestimo]; 
        } else{
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum empréstimo encontrado 2 !</div>"];
        }

    } else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum empréstimo encontrado!</div>"];
    }

    $var = json_encode($retorna);
    echo $var;
?>