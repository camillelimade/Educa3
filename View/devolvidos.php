<?php
// Inclua o arquivo de conexão ao banco de dados.
include '../Controller/CConexao.php';
include '../Controller/CPendencias.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header("Location: ../View/login.php"); // Redirecionar para a página de login se não estiver logado
    exit();
}


// Inicialize a instância da classe de conexão.
$conexao = new CConexao();
$conn = $conexao->getConnection();
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="description" content="Página de devolução de livros.">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../ArquivosExternos/icons.js"></script>
    <link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../lib/Bootstrap 2/bootstrap.min.css">
	<link rel="stylesheet" href="../lib/datatables/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="../CSS/popup3.css">
    <link rel="stylesheet" href="../CSS/darkPaginacao.css">

    <title>EducaBiblio</title>
</head>

<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class="fas fa-book"></i>
            <span class="text">EducaBiblio</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="inicio.php">
                    <i class='fas fa-home'></i>
                    <span class="text">Início</span>
                </a>
            </li>
            <li>
                <a href="livros.php">
                    <i class="fas fa-book"></i>
                    <span class="text">Livros</span>
                </a>
            </li>
            <li>
                <a href="emprestimos.php">
                    <i class="fas fa-undo"></i>
                    <span class="text">Empréstimos</span>
                </a>
            </li>
            <li class="active">
                <a href="devolucao.php">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text">Devoluções</span>
                </a>
            </li>
            <li>
                <a href="aluno.php">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="text">Alunos</span>
                </a>
            </li>
            <li>
                <a href="prof.php">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="text">Professores</span>
                </a>
            </li>
            <li>
                <a href="turma.php">
                    <i class="fas fa-users"></i>
                    <span class="text">Turma</span>
                </a>
            </li>
            <li>
                <a href="recomendacoes.php">
                    <i class="fas fa-download"></i>
                    <span class="text">Recomendações</span>
                </a>
            </li>
            <li>
                <a href="usuarios.php">
                    <i class="fas fa-cogs"></i>
                    <span class="text">Usuários</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="../Controller/CLogout.php" class="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text">Deslogar</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='fas fa-bars'></i>
            <form action="#"></form>

            <div class="icons">
                <div id="menu-btn" class="fas fa-question" onclick="abrirPDFEmNovaAba()"></div>
            </div>

            <script>
                function abrirPDFEmNovaAba() {
                    var urlDoPDF = "../ArquivosExternos/Manual.pdf";
                    window.open(urlDoPDF, '_blank');
                }
            </script>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="profile">
                <?php
                require('../Controller/Utils.php');

                $conexao = new CConexao();
                $conn = $conexao->getConnection();

                ?>
            </a>
        </nav>
        </head>

        <body>
            <section class="tabela">

                <div class="row">
                </div>
            </section>
            <main>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Devolvidos</h3>
                            <button class="pdf-button" id="pdf-button" aria-label="botão pdf" onclick="abrirAluno()">
                                <i class="fas fa-file-pdf"></i></button>

                            <script>
                                function abrirAluno() {
                                    var urlDoPDF = "../pdf/devolvidosPdf.php";
                                    window.open(urlDoPDF, '_blank');
                                }
                            </script>

                        </div>
                        <span id="msgAlerta"></span>
                        <table id="TableDev" style="width:100%">
								<thead>
									<tr>
										<th>ID</th>
                                        <th>Tombo</th>
										<th>Leitor</th>
                                        <th>Ano</th>
										<th>Turma</th>
										<th>Livro</th>
										<th>Data para devolução</th>
                                        <th>Data devolvido</th>
										<th>Estado</th>
									</tr>
								</thead>
							</table>

						</table>
                        
                    </div>

                </div>

                <footer class="footer">
                    © Copyright 2023 por <span>EducaBiblio</span> | Todos os direitos reservados
                </footer>
            </main>
    </section>
</body>

</html>

<script src="../JS/script.js"></script>
<script scr="../ArquivosExternos/ajax.js"></script>
<script src="../lib/jquery/jquery-3.7.1.min.js"></script>
<script src="../lib/datatables/dataTables.js"></script>
<script src="../lib/bootstrap/bootstrap.bundle.min.js"></script>

<script>
    $('#aluno_idAluno').on('change', function() {
        const selectedAlunoId = $(this).val().toLowerCase();

        $('table tbody tr').filter(function() {
            const alunoId = $(this).find('td:nth-child(3)').text().trim().toLowerCase(); // Supondo que o ID do aluno esteja na terceira coluna

            if (selectedAlunoId !== '' && alunoId !== selectedAlunoId) {
                $(this).hide(); // Esconde as linhas que não correspondem ao aluno selecionado
            } else {
                $(this).show(); // Exibe as linhas que correspondem ao aluno selecionado ou mostra todas se nenhum estiver selecionado
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Turma_idTurma").change(function() {
            var turmaId = $(this).val();
            var alunoSelect = $("#aluno_idAluno");

            if (turmaId) {
                $.ajax({
                    type: "GET",
                    url: "../Controller/CBusca_alunos.php",
                    data: {
                        turmaId: turmaId
                    },
                    success: function(data) {
                        alunoSelect.html(data);
                    },
                    error: function() {
                        alunoSelect.html("<option value=''>Erro ao carregar alunos</option>");
                    }
                });
            } else {
                alunoSelect.html("<option value=''>Selecione um aluno</option>");
            }
        });
    });
</script>

<script>
    new DataTable('#TableDev', {
        ajax: {
            "url": '../View/listarDevolvidos.php',
            "dataType": 'JSON',
            "error": function () {
                alert('Não há registros para sua pesquisa');
            }
        },
        processing: true,
        serverSide: true,
        language: {
            url: "../lib/pt_br.json"
        },
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all",
            "className": "dt-body-center"
        }],
        "lengthMenu": [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Tudo"]],
    });
</script>
</body>

</html>