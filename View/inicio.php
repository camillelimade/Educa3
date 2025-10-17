<?php
include('../Controller/CConexao.php');
include('../Controller/CLog_usu.php');
require '../Controller/CGet_rec.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
	header("Location: ../login.php"); // Redirecionar para a página de login se não estiver logado
	exit();
}


$conexao = new CConexao();
$conn = $conexao->getConnection();
$getlivro = new GetLivro($conn);
$livrosRecomendados = $getlivro->obterLivrosRecomendados();
include('../Controller/CPendencias.php'); // Inclua o arquivo CPendencias.php aqui
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>EducaBiblio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="../ArquivosExternos/icons.js"></script>
	<link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/style.css">
	<link rel="stylesheet" href="../lib/datatables/dataTables.dataTables.min.css">
	<link rel="stylesheet" href="../CSS/darkPaginacao.css">
</head>

<body>
	<section id="sidebar" class="page-transition">
		<a href="#" class="brand">
			<i class="fas fa-book"></i>
			<span class="text">EducaBiblio</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
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
			<li>
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
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Olá, <b><?php
								if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true) {
									$nomeDoUsuario = $_SESSION['nomeDoUsuario'];
									echo $nomeDoUsuario; // Exibe o nome do usuário
								} else {
									// Usuário não está logado, redireciona ou exibe uma mensagem de erro
								}
								?> 👋</b>!</h1>
					<style type="text/css">
						b {
							color: #32CD32;
						}
					</style>
					<p>Seja bem-vindo(a) ao EducaBiblio!</p>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class="fas fa-graduation-cap"></i>
					<span class="text">
						<h3><?php

							try {
								// Consulta SQL para contar o número de alunos
								$sql = "SELECT COUNT(*) as total FROM aluno";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->fetch(PDO::FETCH_ASSOC);

								if ($result) {
									$totalAlunos = $result['total'];
								} else {
									$totalAlunos = 0;
								}

								echo $totalAlunos;
							} catch (PDOException $e) {
								// Erro na conexão ou consulta SQL
								echo "Erro: " . $e->getMessage();
							}

							?></h3>
						<p>Alunos cadastrados</p>
					</span>
				</li>
				<li>
					<i class="fas fa-address-book"></i>
					<span class="text">
						<h3><?php

							try {
								// Consulta SQL para contar o número de alunos
								$sql = "SELECT COUNT(*) as total FROM emprestimo";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->fetch(PDO::FETCH_ASSOC);

								if ($result) {
									$totalAlunos = $result['total'];
								} else {
									$totalAlunos = 0;
								}

								echo $totalAlunos;
							} catch (PDOException $e) {
								// Erro na conexão ou consulta SQL
								echo "Erro: " . $e->getMessage();
							}

							?></h3>
						<p>Empréstimos</p>
					</span>
				</li>
				<li>
					<i class="fas fa-users"></i>
					<span class="text">
						<h3><?php
							try {
								$sql = "SELECT COUNT(*) as total FROM emprestimo WHERE StatusEmprestimo = 3";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->fetch(PDO::FETCH_ASSOC);

								if ($result) {
									$totalComPendencia = $result['total'];
								} else {
									$totalComPendencia = 0;
								}

								echo $totalComPendencia;
							} catch (PDOException $e) {
								// Erro na conexão ou consulta SQL
								echo "Erro: " . $e->getMessage();
							}

							?></h3>
						<p>Pendências</p>
					</span>
				</li>
				<li>
					<i class="fas fa-book"></i>
					<span class="text">
						<h3><?php

							try {
								// Consulta SQL para contar o número de alunos
								$sql = "SELECT COUNT(*) as total FROM livro";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->fetch(PDO::FETCH_ASSOC);

								if ($result) {
									$totalAlunos = $result['total'];
								} else {
									$totalAlunos = 0;
								}

								echo $totalAlunos;
							} catch (PDOException $e) {
								// Erro na conexão ou consulta SQL
								echo "Erro: " . $e->getMessage();
							}

							?></h3>
						<p>Livros cadastrados</p>
					</span>
				</li>
			</ul>

			<section class="container-livros">
				<h1 class="heading">Recomendações <span>semanais</span> </h1>
				<div class="container-card">
					<ul>
						<?php
						// Loop para exibir as recomendações da tabela 'recomendacao'
						foreach ($livrosRecomendados as $livroRecomendado) {
							echo '<div class="card">
        <li>
            <a class="no-click" href="' . $livroRecomendado["CamRec"] . '">
                <img src="' . $livroRecomendado["CamRec"] . '" alt="">
                <div class="card-content">
                    <div class="nome">
                        <section class="container-livros">
                            <h3 class="heading">' . $livroRecomendado["LivroRec"] . '</h3>
                            <p><b>Autor: </b>' . $livroRecomendado["AutorRec"] . '</p>
                            <p><b>Categoria: </b>' . $livroRecomendado["CatRec"] . '</p>
                        </section>
                    </div>
                </div>
            </a>
        </li>
    </div>';
						}
						?>

					</ul>
				</div>
			</section>

			<style>
				/* Esconde as setas para campos de entrada numérica */
				input[type=number]::-webkit-inner-spin-button,
				input[type=number]::-webkit-outer-spin-button {
					-webkit-appearance: none;
					margin: 0;
				}

				input[type=number] {
					-moz-appearance: textfield;
					/* Firefox */
				}



				.searchInput {
					width: 20% !important;
					height: 30px;
					background-color: #f2f2f2;
					border: 1px solid #ccc;
					border-radius: 5px;
					padding: 5px;
				}
			</style>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Histórico de Empréstimos</h3>

						<button class="pdf-button" id="pdf-button" aria-label="botão pdf" onclick="abrirAluno()">
							<i class="fas fa-file-pdf"></i></button>

						<script>
							function abrirAluno() {
								var urlDoPDF = "../pdf/historicoPdf.php";
								window.open(urlDoPDF, '_blank');
							}
						</script>

					</div>
					<table id="TableHistorico" class="table" style="width:100%">
								<thead>
									<tr>
										<th>Id</th>
										<th>Livro</th>
										<th>Nome</th>
										<th>Data de empréstimo</th>
										<th>Data de devolução</th>
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

	<script src="../JS/script.js"></script>
	<script src="../lib/jquery/jquery-3.7.1.min.js"></script>
	<script src="../lib/datatables/dataTables.js"></script>
	<script src="../lib/Bootstrap 2/bootstrap.bundle.min.js"></script>


	<script>
		new DataTable('#TableHistorico', {
			ajax: {
				"url": '../View/listarHistorico.php',
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
	<script>
		$('#searchInput').on('keyup', function() {
			const value = $(this).val().toLowerCase();

			$('table tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		});
	</script>
</body>

</html>