<?php
require_once 'CConexao.php';
class CEmprestimoController
{
    public function emprestarLivro()
    {
        if (isset($_POST['emprestar'])) {
            // Obtenha os dados do formulário
            $dataEmprestimo = $_POST['DataEmprestimo'];
            $dataDevolucao = $_POST['DataDevolucao'];
            $livroId = $_POST['livro_idLivro'];
            $usuarioId = $_POST['usuario_idUsuario'];
            $alunoId = $_POST['aluno_idAluno'];
            $quantidade = $_POST['quantidade'];

            // Conecte-se ao banco de dados
            $conexao = new CConexao();
            $conn = $conexao->getConnection();

            // Verifique se já existe um empréstimo para o mesmo livro, aluno e data
            $queryCheck = "SELECT COUNT(*) as count_emprestimo FROM emprestimo WHERE DataEmprestimo = :dataEmprestimo AND livro_idLivro = :livroId AND aluno_idAluno = :alunoId";
            $stmtCheck = $conn->prepare($queryCheck);
            $stmtCheck->bindParam(':dataEmprestimo', $dataEmprestimo);
            $stmtCheck->bindParam(':livroId', $livroId);
            $stmtCheck->bindParam(':alunoId', $alunoId);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['count_emprestimo'] > 0) {
                echo "Empréstimo já existe para este livro, aluno e data.";
            } else {
                // Execute a inserção no banco de dados
                $queryInsert = "INSERT INTO emprestimo (DataEmprestimo, livro_idLivro, usuario_idUsuario, aluno_idAluno, Quantidade_emp) VALUES (:dataEmprestimo, :livroId, :usuarioId, :alunoId, :quantidade)";
                $stmtInsert = $conn->prepare($queryInsert);
                $stmtInsert->bindParam(':dataEmprestimo', $dataEmprestimo);
                $stmtInsert->bindParam(':livroId', $livroId);
                $stmtInsert->bindParam(':usuarioId', $usuarioId);
                $stmtInsert->bindParam(':alunoId', $alunoId);
                $stmtInsert->bindParam(':quantidade', $quantidade);

                if ($stmtInsert->execute()) {
                    echo "Empréstimo cadastrado com sucesso!";

                    // Insira lógica para adicionar uma entrada de devolução pendente na tabela 'devolucao'
                    $emprestimoId = $conn->lastInsertId(); // Obtém o ID do empréstimo recém-inserido

                    $queryInsertDevolucao = "INSERT INTO devolucao (DataDevolucao, emprestimo_idEmprestimo) VALUES (:dataDevolucao,:emprestimoId)";
                    $stmtInsertDevolucao = $conn->prepare($queryInsertDevolucao);
                    $stmtInsertDevolucao->bindParam(':dataDevolucao', $dataDevolucao);
                    $stmtInsertDevolucao->bindParam(':emprestimoId', $emprestimoId);

                    if ($stmtInsertDevolucao->execute()) {
                        echo "Registro de devolução pendente adicionado com sucesso!";
                    } else {
                        echo "Erro ao adicionar registro de devolução pendente.";
                    }
                } else {
                    echo "Erro ao cadastrar o empréstimo.";
                }
            }
        }
    }
}
