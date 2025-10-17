<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escolha = $_POST["escolha"];

    if ($escolha === "Aluno") {
        // Lógica de cadastro de alunos
        require_once "../Controller/CCad_alunos.php";
        $cadAluno = new CCad_aluno();
        $cadAluno->cadastrarAluno($_POST["NomeAluno"], $_POST["EmailAluno"], $_POST["Turma_idTurma"]);
        header("Location: ../View/aluno.php");
    } elseif ($escolha === "Professor") {
        // Lógica de cadastro de professores
        require_once "../Controller/CCad_prof.php";
        $cadProfessor = new CCad_prof();
        $cadProfessor->cadastrarProfessor($_POST["NomeProf"], $_POST["EmailProf"], $_POST["MateriaProf"]);
        header("Location: ../View/aluno.php");
    }
}
