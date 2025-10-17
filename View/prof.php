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
	<script src="../ArquivosExternos/icons.js"></script>
	<link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/style.css">
	<link rel="stylesheet" href="../lib/Bootstrap 2/bootstrap.min.css">
	<link rel="stylesheet" href="../lib/datatables/dataTables.dataTables.min.css">
	<link rel="stylesheet" href="../CSS/popup6.css">
	<link rel="stylesheet" href="../CSS/darkPaginacao.css">
	<script src="../JS/alunos_prof.js"></script>
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
			<li>
				<a href="aluno.php">
					<i class="fas fa-graduation-cap"></i>
					<span class="text">Alunos</span>
				</a>
			</li>
			<li class="active">
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
					<form action="../Router/prof_rotas.php" method="post">
						<h3>Cadastro de Professores</h3>
						<input type="text" placeholder="ID" name="idProf" id="idProf" class="boxSecundario" autocomplete= "off" readonly>
						<input type="text" placeholder="Nome" name="NomeProf" id="NomeProf" class="boxSecundario2" autocomplete="off">
						<input type="email" placeholder="E-mail" name="EmailProf" id="EmailProf" class="boxSecundario2" autocomplete="off">
						<input type="text" placeholder="Disciplina" name="MateriaProf" id="MateriaProf"  class="boxSecundario2" autocomplete="off">
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
							<h3>Tabela de Cadastro de Professores</h3>

						</div>
						<script>
							function abrirAluno2() {
								var urlDoPDF = "../pdf/alunoPdf.php";
								window.open(urlDoPDF, '_blank');
							}
						</script>
						<span id="msgAlerta"></span>
						<table id="TableProf" style="width:100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>Nome</th>
										<th>Email</th>
										<th>Disciplina</th>
										<th>Editar</th>
										<th>Excluir</th>
									</tr>
								</thead>
							</table>

						</table>
						
					</div>
				</div>

				<!-- Criando Modal Editar -->
				<div class="modal fade" id="editProfModal" tabindex="-1" arial-labelledby="editProfModalLabel" arial-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editProfModalLabel">Editar Professores</h5>
							</div>
							<div class="modal-body">
								<span id="msgAlertErroEdit"></span>
								<form method="POST" id="form-edit-prof" action="../Modais/editarProf.php">
									<div class="mb-3">
										<input type="text" class="form-control" name="idProf" id="editIdProf" required autocomplete="off" readonly>
									</div>
									<div class="mb-3">
										<label for="form-control">Nome do Professor:</label>
										<input type="text" class="form-control" name="NomeProf" id="editNomeProf" required autocomplete="off" >
									</div>
									<div class="mb-3">
										<label for="form-control"> Email: </label>
										<input type="text" class="form-control" name="EmailProf" id="editEmailProf"  autocomplete="off" >
									</div>	
									<div class="mb-3">
										<label for="form-control"> Disciplina: </label>
										<input type="text" class="form-control" name="MateriaProf" id="editMateriaProf" required autocomplete="off" >
									</div>
									
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
								<button type="submit" class="btn btn-warning" form="form-edit-prof" value="Salvar">Salvar Alterações</button>
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
<script src="../lib/jquery/jquery-3.7.1.min.js"></script>
<script src="../lib/datatables/dataTables.js"></script>
<script src="../lib/Bootstrap 2/bootstrap.bundle.min.js"></script>

<!-- Função do Modal Editar -->
<script>
	const editModal = new bootstrap.Modal(document.getElementById('editProfModal'));
	async function editProf(idProf) {
		//console.log(idEmprestimo);
		const dados = await fetch('../Modais/visualizarProf.php?idProf=' + idProf);
		const resposta = await dados.json();
		//console.log(resposta);

		if(resposta['status']){
			document.getElementById("msgAlerta").innerHTML = "";
			editModal.show();

			document.getElementById("editIdProf").value = resposta['dados'].idProf;
			document.getElementById("editNomeProf").value = resposta['dados'].NomeProf;
			document.getElementById("editEmailProf").value = resposta['dados'].EmailProf;
			document.getElementById("editMateriaProf").value = resposta['dados'].MateriaProf;
		}else{
			alert(resposta['msg']);
		}
	}
</script>

<!-- Salvando informações no banco de dados -->
<script>
	const formEditProf = document.getElementById("form-edit-prof");
	if(formEditProf){
		formEditProf.addEventListener("submit", async(e) => {
			e.preventDefault();
			const dadosForm = new FormData(formEditProf);

			const dados = await fetch("../Modais/editarProf.php", {
				method: "POST",
				body: dadosForm
			});

			const resposta = await dados.json();

			if (resposta['status']){
				//document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];

				document.getElementById("msgAlerta").innerHTML = resposta['msg'];
				document.getElementById("msgAlertErroEdit").innerHTML = "";
				formEditProf.reset();	
				editModal.hide();
				listarDataTables = $('#TableProf').DataTable();
				listarDataTables.draw();
			}else{
				document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
			}

		});
	}
</script>

<script>
	$(document).ready(function() {
		$('#turmaTable').DataTable(); // Inicializa o DataTables para a tabela de turma
	});
</script>

<script>
	$(document).ready(function() {
		// Capturar clique no botão de exclusão
		$('.delete-button').click(function() {
			// Obter o ID do item a ser excluído
			var id = $(this).closest('tr').find('td:eq(0)').text(); // Considerando que o ID está na segunda coluna

			// Mostrar o popup de confirmação
			handlePopup(true);

			// Preencher o link de exclusão com o ID correto
			var linkExclusao = '../Controller/CExcluir_prof.php?id=' + id;
			$('#popup a').attr('href', linkExclusao);
		});
	});
</script>

<script>
	$(document).ready(function() {
		// Capturar clique no botão de edição
		$('.edit-button').click(function() {
			// Obter o ID do item a ser editado
			var id = $(this).data('id');

			// Encontrar os dados correspondentes na tabela de usuários e preencher o formulário
			$('table tbody tr').each(function() {
				var rowId = $(this).find('td:eq(0)').text(); // Considerando que o ID está na segunda coluna
				if (rowId == id) {
					var nome = $(this).find('td:eq(1)').text();
					var email = $(this).find('td:eq(2)').text();
					var materia = $(this).find('td:eq(3)').text();

					// Preencher os campos do formulário com os dados obtidos
					$('#idProf').val(id);
					$('#NomeProf').val(nome);
					$('#EmailProf').val(email);
					$('#MateriaProf').val(materia);

					// Alterar o modo de ação para editar
					$('#modoAcao').val('editar');
					// Alterar o valor do botão para refletir a ação de edição
					$('input[type="submit"]').val('Editar');
					// Alterar a rota do formulário para a rota de atualização de usuários
					$('form').attr('action', '../Router/profedit_rotas.php'); // Alterar a action do formulário para a rota correta
					// Alterar o nome do botão para identificar a ação como atualização
					$('input[type="submit"]').attr('name', 'Editar');
				}
			});
		});
	});
</script>
<script>
	function abrirAluno() {
		var urlDoPDF = "../pdf/registrosAluPdf.php";
		window.open(urlDoPDF, '_blank');
	}
</script>

<script>
	async function excluirProf(idProf) {

		const dados = await fetch("../Controller/CExcluir_prof.php?id=" + idProf);
		var confirmar = confirm("Você realmente quer apagar o registro selecionado?");

		//console.log(resposta);
		if(confirmar){
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-success' role='alert'>Registro apagado com sucesso!</div>";

			listarDataTables = $('#TableProf').DataTable();
			listarDataTables.draw();
		}else{
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-danger' role='alert'> Erro: Registro não apagado</div>";
		}
	}
</script>

<script>
	new DataTable('#TableProf', {
		ajax: {
			"url": '../View/listarProf.php',
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