<?php
include('../Controller/CConexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
	header("Location: ../View/login.php"); // Redirecionar para a página de login se não estiver logado
	exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta name="description" content="Página de cadastro de leitores">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/style.css">
	<link rel="stylesheet" href="../lib/Bootstrap 2/bootstrap.min.css">
	<link rel="stylesheet" href="../lib/datatables/dataTables.dataTables.min.css">
	<link rel="stylesheet" href="../CSS/popup.css">
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
			<li>
				<a href="devolucao.php">
					<i class="fas fa-arrow-left"></i>
					<span class="text">Devoluções</span>
				</a>
			</li>
			<li class="active">
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
					<form action="../Router/alunos_rotas.php" method="post">
						<span id="msgAlerta"></span>
						<h3>Cadastro de alunos</h3>
						<input type="text" placeholder="ID" name="idAluno" id="idAluno" class="boxSecundario" autocomplete="off" readonly>
						<input type="hidden" value="Aluno" id="escolha" name="escolha">
						<input type="text" placeholder="Nome" name="NomeAluno" id="NomeAluno" class="boxSecundario2" autocomplete="off">

						<input type="text" id="EmailAluno" name="EmailAluno" placeholder="Contato" class="boxSecundario2" autocomplete="off">

						<select id="Turma_idTurma" name="Turma_idTurma" class="boxSecundario2 select-dark-mode required">
							<option value="0">Turma</option>

							<?php

							include('../Controller/CGet_turma.php');
							$turma = getTurmasFromDB(); // Chama a função para obter as turmas do banco

							foreach ($turma as $idTurma => $nomeTurma) {
								echo "<option value=\"$idTurma\">$nomeTurma</option>";
							}


							?>

						</select>

						<center><input type="submit" value="Enviar" class="inline-btn" name="action"></center>
					</form>
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
			</style>
			<main>
				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Tabela de Cadastro de Alunos</h3>

							<button class="pdf-button" id="pdf-button" aria-label="botão pdf" onclick="abrirAluno2()">
								<i class="fas fa-file-pdf"></i></button>

						</div>
						<select id="filtroTurma" class="form-control" aria-label="Filtro por turma">
							<option value="" selected>Selecione uma Turma</option>
							<?php foreach ($turma as $idTurma => $nomeTurma) { ?>
								<option value="<?php echo $idTurma; ?>"><?php echo $nomeTurma; ?></option>
								<?php } ?>
						</select>
						<script>
							function abrirAluno2() {
								var urlDoPDF = "../pdf/alunoPdf.php";
								window.open(urlDoPDF, '_blank');
							}
						</script>

						<table id="TableAlunos" style="width:100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nome</th>
									<th>Ano</th>
									<th>Turma</th>
									<th>Contato</th>
									<th>Editar</th>
									<th>Excluir</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>

				<!-- Criando Modal Editar -->
				<div class="modal fade" id="editAlunosModal" tabindex="-1" arial-labelledby="editAlunosModalLabel" arial-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editAlunosModalLabel">Editar Alunos</h5>
							</div>
							<div class="modal-body">
								<span id="msgAlertErroEdit"></span>
								<form method="POST" id="form-edit-alunos" action="../Modais/editarAluno.php">
									<div class="mb-3">
										<input type="text" class="form-control" name="idAluno" id="editIdAluno" required autocomplete="off" readonly>
									</div>
									<div class="mb-3">
										<label for="form-control">Nome do Aluno:</label>
										<input type="text" class="form-control" name="NomeAluno" id="editNomeAluno" required autocomplete="off">
									</div>
									<div class="mb-3">
										<label for="form-control">Ano:</label>
										<input type="text" class="form-control" name="AnoTurma" id="editAnoTurma" required autocomplete="off">
									</div>
									<div class="mb-3">
										<label for="form-control">Turma:</label>
										<input type="text" class="form-control" name="nomeTurma" id="editNomeTurma" required autocomplete="off">
									</div>

								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
								<button type="submit" class="btn btn-warning" form="form-edit-alunos" value="Salvar">Salvar Alterações</button>
							</div>
						</div>
					</div>
				</div>
				<footer class="footer">
					© Copyright 2023 por <span>EducaBiblio</span> | Todos os direitos reservados
				</footer>
				<style>
					#button-link {
						color: inherit;
						/* Use a cor padrão do texto do pai */
						text-decoration: none;
						/* Remover sublinhado padrão */
					}
				</style>
			</main>
	</section>
</body>

</html>

<script src="../JS/script.js"></script>
<script src="../JS/popup.js"></script>
<script src="../ArquivosExternos/icons.js"></script>
<script src="../JS/alunos_prof.js"></script>
<script src="../lib/jquery/jquery-3.7.1.min.js"></script>
<script src="../lib/datatables/dataTables.js"></script>
<script src="../lib/Bootstrap 2/bootstrap.bundle.min.js"></script>

<script>
	function abrirAluno() {
		var urlDoPDF = "../pdf/registrosAluPdf.php";
		window.open(urlDoPDF, '_blank');
	}
</script>

<!-- Função do Modal Editar -->
<script>
	const editModal = new bootstrap.Modal(document.getElementById('editAlunosModal'));
	async function editAluno(idAluno) {
		//console.log(idEmprestimo);
		const dados = await fetch('../Modais/visualizarAlunos.php?idAluno=' + idAluno);
		const resposta = await dados.json();
		//console.log(resposta);

		if (resposta['status']) {
			document.getElementById("msgAlerta").innerHTML = "";
			editModal.show();

			document.getElementById("editIdAluno").value = resposta['dados'].idAluno;
			document.getElementById("editNomeAluno").value = resposta['dados'].NomeAluno;
			document.getElementById("editAnoTurma").value = resposta['dados'].AnoTurma;
			document.getElementById("editNomeTurma").value = resposta['dados'].nomeTurma;
		} else {
			alert(resposta['msg']);
		}
	}
</script>

<!-- Salvando informações no banco de dados -->
<script>
	const formEditAlunos = document.getElementById("form-edit-alunos");
	if (formEditAlunos) {
		formEditAlunos.addEventListener("submit", async (e) => {
			e.preventDefault();
			const dadosForm = new FormData(formEditAlunos);

			const dados = await fetch("../Modais/editarAluno.php", {
				method: "POST",
				body: dadosForm
			});

			const resposta = await dados.json();

			if (resposta['status']) {
				//document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];

				document.getElementById("msgAlerta").innerHTML = resposta['msg'];
				document.getElementById("msgAlertErroEdit").innerHTML = "";
				formEditAlunos.reset();
				editModal.hide();
				listarDataTables = $('#TableAlunos').DataTable();
				listarDataTables.draw();
			} else {
				document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
			}

		});
	}
</script>

<script>
	async function excluirAluno(idAluno) {
		//console.log(idLivro);

		const dados = await fetch("../Controller/CExcluir_aluno.php?id=" + idAluno);
		var confirmar = confirm("Você realmente quer apagar o registro selecionado?");

		//console.log(resposta);
		if (confirmar) {
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-success' role='alert'>Registro apagado com sucesso!</div>";

			listarDataTables = $('#TableAlunos').DataTable();
			listarDataTables.draw();
		} else {
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-danger' role='alert'> Erro: Registro não apagado</div>";
		}
	}
</script>

<script>
	var tabelaAlunos = new DataTable('#TableAlunos', {
		ajax: {
			url: '../View/listarAlunos.php',
			type: 'GET',
			data: function(d) {
				d.turma = $('#filtroTurma').val(); // envia a turma selecionada
			},
			dataType: 'json',
			error: function() {
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
		"lengthMenu": [
			[10, 15, 25, 50, 100, -1],
			[10, 15, 25, 50, 100, "Tudo"]
		]
	});
	$('#filtroTurma').on('change', function() {
		tabelaAlunos.ajax.reload(); // recarrega os dados conforme a turma
	});
</script>

</body>

</html>