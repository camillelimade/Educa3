<?php
var_dump($_POST);
// Verifica se houve um envio de dados pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Editar'])) {
    // Importe a classe responsável pela atualização do empréstimo
    require_once('../Controller/CAlter_emprestimo.php'); // Verifique o nome correto do arquivo

    // Verifica se os campos necessários foram enviados via POST
    if (
        isset($_POST['idEmprestimo']) &&
        isset($_POST['Genero_idGenero']) &&
        isset($_POST['livro_idLivro']) &&
        isset($_POST['Turma_idTurma']) &&
        isset($_POST['aluno_idAluno']) &&
        isset($_POST['prof_idProf']) &&
        isset($_POST['quantidade']) &&
        isset($_POST['data']) &&
        isset($_POST['usuario_idUsuario'])
    ) {
        // Atribui os valores enviados via POST a variáveis
        $idEmprestimo = $_POST['idEmprestimo'];
        $Genero_idGenero = $_POST['Genero_idGenero'];
        $livro_idLivro = $_POST['livro_idLivro'];
        $Turma_idTurma = $_POST['Turma_idTurma'];
        $aluno_idAluno = $_POST['aluno_idAluno'];
        $prof_idProf = $_POST['prof_idProf'];
        $quantidade = $_POST['quantidade'];
        $data = $_POST['data'];
        $usuario_idUsuario = $_POST['usuario_idUsuario'];

        // Cria uma instância da classe responsável pelas operações com alunos
        $alteracaoEmprestimo = new CAlter_emprestimo();

        // Chama o método para atualizar o aluno pelo ID
        $resultado = $alteracaoEmprestimo->atualizarEmprestimo($idEmprestimo, $Genero_idGenero, $livro_idLivro, $Turma_idTurma, $aluno_idAluno, $prof_idProf, $quantidade, $data,  $usuario_idUsuario);

        if ($resultado) {
            echo "Emprestimo atualizado com sucesso!";
            header("Location: ../View/emprestimos.php"); // Redireciona para a página correta após a atualização do aluno
            exit(); // Encerra o script após o redirecionamento
        } else {
            echo "Falha ao atualizar o emprestimo.";
        }
    } else {
        echo "Por favor, preencha todos os campos necessários.";
        header("Location: ../View/emprestimos.php"); // Redireciona para a página correta após a atualização do aluno
    }
} else {
    echo "O envio do formulário não foi detectado.";
}
?>


    <!--
    // Recupere os dados do formulário
    $idEmprestimo = $_POST['idEmprestimo'];
    $Genero_idGenero = $_POST['Genero_idGenero'];
    $livro_idLivro = $_POST['livro_idLivro'];
    $Turma_idTurma = $_POST['Turma_idTurma'];
    $aluno_idAluno = $_POST['aluno_idAluno'];
    $prof_idProf = $_POST['prof_idProf'];
    $DataEmprestimo = $_POST['DataEmprestimo'];
    $quantidade = $_POST['quantidade'];
    $data = $_POST['data'];
    $usuario_idUsuario = $_POST['usuario_idUsuario'];

    // Crie uma instância da classe para atualizar o empréstimo
    $atualizacaoEmprestimo = new CAlter_emprestimo();

        // Chama o método para atualizar o empréstimo
        $resultadoAtualizacao = $atualizacaoEmprestimo->atualizarEmprestimo(
            $idEmprestimo,
            $Genero_idGenero,
            $livro_idLivro,
            $Turma_idTurma,
            $aluno_idAluno,
            $prof_idProf,
            $DataEmprestimo,
            $quantidade,
            $data,
            $usuario_idUsuario
        );

    if ($resultadoAtualizacao) {
        // Redirecione para alguma página de sucesso após a atualização
        header("Location: ../View/emprestimos.php");
        exit();
    } else {
        // Se houver um erro na atualização, redirecione para uma página de erro ou faça alguma outra gestão
        header("Location: ../View/emprestimos.php");
        exit();
    }
} else {
    // Redirecione para alguma página de erro, pois parece que o envio do formulário não foi corretamente tratado
    header("Location: ../View/emprestimos.php");
}
?>
-->