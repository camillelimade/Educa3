<?php
include_once '../Controller/CConexao.php';
$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados_requisicao = $_REQUEST;

// Filtro por turma (recebido via GET do DataTables)
$where = "";
if (!empty($_GET['turma']) && $_GET['turma'] != "   ") {
    $turma = intval($_GET['turma']);
    $where = "WHERE aluno.Turma_idTurma = :turma";
}

// Contar total de alunos
$query_Quantidade_Alunos = "SELECT COUNT(idAluno) AS Quantidade_al FROM aluno";
$result_Quantidade_Alunos = $conn->prepare($query_Quantidade_Alunos);
$result_Quantidade_Alunos->execute();
$row_Quantidade_Alunos = $result_Quantidade_Alunos->fetch(PDO::FETCH_ASSOC);

// Consulta principal
$sql = "SELECT 
            aluno.NomeAluno,
            aluno.idAluno,
            aluno.Turma_idTurma,
            aluno.EmailAluno,
            turma.nomeTurma,
            turma.AnoTurma
        FROM aluno
        LEFT JOIN turma ON aluno.Turma_idTurma = turma.idTurma
        $where";

// Se houver pesquisa, adiciona filtro
if (!empty($dados_requisicao['search']['value'])) {
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    if ($where == "") {
        $sql .= " WHERE aluno.NomeAluno LIKE :NomeAluno OR aluno.idAluno LIKE :idAluno";
    } else {
        $sql .= " AND (aluno.NomeAluno LIKE :NomeAluno OR aluno.idAluno LIKE :idAluno)";
    }
}

// Ordenação e paginação
$sql .= " ORDER BY aluno.NomeAluno LIMIT :inicio , :quantidade";
$result_Alunos = $conn->prepare($sql);

$result_Alunos->bindParam(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_Alunos->bindParam(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

// Liga o parâmetro da turma, se existir
if (!empty($_GET['turma']) && $_GET['turma'] != "0") {
    $result_Alunos->bindParam(':turma', $turma, PDO::PARAM_INT);
}

// Liga os parâmetros da pesquisa, se existirem
if (!empty($dados_requisicao['search']['value'])) {
    $result_Alunos->bindParam(':NomeAluno', $valor_pesq, PDO::PARAM_STR);
    $result_Alunos->bindParam(':idAluno', $valor_pesq, PDO::PARAM_STR);
}

// Executar consulta
$result_Alunos->execute();

$dados = [];
while ($row_al = $result_Alunos->fetch(PDO::FETCH_ASSOC)) {
    extract($row_al);
    $registro = [];
    $registro[] = $idAluno;
    $registro[] = $NomeAluno;
    $registro[] = $AnoTurma;
    $registro[] = $nomeTurma;
    $registro[] = $EmailAluno;
    $registro[] = "<button type='button' class='btn btn-outline-warning' id='$idAluno' onclick='editAluno($idAluno)'><i class='fas fa-pen'></i></button>";
    $registro[] = "<button type='button' class='btn btn-outline-danger' id='$idAluno' onclick='excluirAluno($idAluno)'><i class='fas fa-trash-alt'></i></button>";
    $registro[] = "<button type='button' class='btn btn-outline-primary' id='$idAluno' onclick='pdfHistoricoAluno($idAluno)'><i class='fas fa-file-pdf'></i></button>";
    $dados[] = $registro;
}

$resultado = [
    "draw" => intval($dados_requisicao['draw']),
    "recordsTotal" => intval($row_Quantidade_Alunos['Quantidade_al']),
    "recordsFiltered" => intval($row_Quantidade_Alunos['Quantidade_al']),
    "data" => $dados
];

echo json_encode($resultado);
?>