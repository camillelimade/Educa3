<?php
require_once 'CConexao.php';
class CEmprestimoController
{
    public function emprestarLivro()
    {
        if (isset($_POST['emprestar'])) {
            $dataEmprestimo = $_POST['DataEmprestimo'];
            $dataDevolucao = $_POST['DataDevolucao'];
            $livroId = (int) $_POST['livro_idLivro'];
            $usuarioId = (int) $_POST['usuario_idUsuario'];
            $alunoId = (int) $_POST['aluno_idAluno'];
            $quantidade = (int) $_POST['quantidade'];

            // Validação simples
            if ($quantidade <= 0) {
                header("Location: ../View/emprestimos.php?msg=quantidade_invalida");
                exit;
            }

            try {
                $conexao = new CConexao();
                $conn = $conexao->getConnection();

                // 📌 Verificar se aluno já tem empréstimo ativo
                $sqlVerificaAtivo = "
                SELECT COUNT(*) as ativos 
                FROM emprestimo 
                WHERE aluno_idAluno = :alunoId 
                AND (StatusEmprestimo = 0 OR StatusEmprestimo = 1)";
                $stmtVerifica = $conn->prepare($sqlVerificaAtivo);
                $stmtVerifica->bindParam(':alunoId', $alunoId, PDO::PARAM_INT);
                $stmtVerifica->execute();
                $ativo = $stmtVerifica->fetchColumn();

                if ($ativo > 0) {
                    // Bloqueia novo empréstimo
                    header("Location: ../View/emprestimos.php?msg=emprestimo_ativo_bloqueado");
                    exit;
                }

                // 📌 Verificar se já existe empréstimo igual no mesmo dia
                $queryCheck = "SELECT COUNT(*) as count_emprestimo 
                           FROM emprestimo 
                           WHERE DataEmprestimo = :dataEmprestimo 
                           AND livro_idLivro = :livroId 
                           AND aluno_idAluno = :alunoId";
                $stmtCheck = $conn->prepare($queryCheck);
                $stmtCheck->bindParam(':dataEmprestimo', $dataEmprestimo);
                $stmtCheck->bindParam(':livroId', $livroId);
                $stmtCheck->bindParam(':alunoId', $alunoId);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($result['count_emprestimo'] > 0) {
                    header("Location: ../View/emprestimos.php?msg=emprestimo_duplicado");
                    exit;
                }

                // ✅ Se passou pelas verificações, realiza o empréstimo
                $conn->beginTransaction();

                $queryInsert = "INSERT INTO emprestimo 
                (DataEmprestimo, livro_idLivro, usuario_idUsuario, aluno_idAluno, Quantidade_emp, StatusEmprestimo)
                VALUES (:dataEmprestimo, :livroId, :usuarioId, :alunoId, :quantidade, 0)";
                $stmtInsert = $conn->prepare($queryInsert);
                $stmtInsert->bindParam(':dataEmprestimo', $dataEmprestimo);
                $stmtInsert->bindParam(':livroId', $livroId);
                $stmtInsert->bindParam(':usuarioId', $usuarioId);
                $stmtInsert->bindParam(':alunoId', $alunoId);
                $stmtInsert->bindParam(':quantidade', $quantidade);
                $stmtInsert->execute();

                $emprestimoId = $conn->lastInsertId();

                $queryInsertDevolucao = "INSERT INTO devolucao (DataDevolucao, StatusDevolucao, emprestimo_idEmprestimo)
                                     VALUES (:dataDevolucao, 0, :emprestimoId)";
                $stmtInsertDevolucao = $conn->prepare($queryInsertDevolucao);
                $stmtInsertDevolucao->bindParam(':dataDevolucao', $dataDevolucao);
                $stmtInsertDevolucao->bindParam(':emprestimoId', $emprestimoId);
                $stmtInsertDevolucao->execute();

                $conn->commit();

                header("Location: ../View/emprestimos.php?msg=sucesso");
                exit;
            } catch (Exception $e) {
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                error_log("Erro no empréstimo: " . $e->getMessage());
                header("Location: ../View/emprestimos.php?msg=erro");
                exit;
            }
        }
    }
}
