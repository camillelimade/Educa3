<?php

//Incluindo arquivo de conexão ao banco de dados
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="../ArquivosExternos/icons.js"></script>
	<link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/style.css">
	<link rel="stylesheet" href="../lib/Bootstrap 2/bootstrap.min.css">
	<link rel="stylesheet" href="../lib/datatables/dataTables.dataTables.min.css">
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
			<li class="active">
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
		</head>

		<body>

			<section class="tabela">

				<div class="row">
					<form action="../Router/livro_rotas.php" method="post" enctype="multipart/form-data">
						<h3>Cadastro de Livros</h3>
						<input type="text" placeholder="ID" name="idLivro" id="idLivro" required  class="box3" autocomplete="off" readonly>
						<input type="text" placeholder="Nome" name="NomeLivro" id="NomeLivro" required maxlength="50" class="box" autocomplete="off" required>
						<input type="text" placeholder="Autor" name="NomeAutor" id="NomeAutor" maxlength="50" class="box" autocomplete="off" required>
						<input type="text" placeholder="Edição" name="EdicaoLivro" id="EdicaoLivro" maxlength="50" class="box" autocomplete="off">
						<input type="text" placeholder="Editora" name="EditoraLivro" id="EditoraLivro" maxlength="50" class="box" autocomplete="off">
						<input type="text" placeholder="Tombo" name="Tombo" id="Tombo" maxlength="50" class="box" autocomplete="off">

						<select name="Genero_idGenero" id="Genero_idGenero" class="box select-dark-mode" required>
							<option value="1">Autoajuda</option>
							<option value="2">Biografia</option>
							<option value="3">Clássico</option>
							<option value="4">Conto</option>
							<option value="5">Fantasia</option>
							<option value="6">Ficção</option>
							<option value="7">Poesia</option>
							<option value="8">Romance</option>
							<option value="9">Outro</option>
							<option value="10">Crônica</option>
							<option value="11">Quadrinhos</option>
							<option value="12">Didático</option>
							<option value="13">Mitologia</option>
							<option value="14">Teatro</option>
						</select>
						<select name="Idioma_idIdioma" id="Idioma_idIdioma" class="box select-dark-mode">
							<option value="1">Português</option>
							<option value="2">Inglês</option>
							<option value="3">Espanhol</option>
						</select>

						<input type="text" placeholder="Quantidade" name="QuantidadeLivros" id="QuantidadeLivros" class="box" autocomplete="off">

						<center><input type="submit" value="Cadastrar" id="cadastrar" class="inline-btn" name="action"></center>
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
				}
			</style>
			<main>
				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Tabela de Cadastro Livros</h3>

							<script>
								function abrirAluno() {
									var urlDoPDF = "../pdf/livroPdf.php";
									window.open(urlDoPDF, '_blank');
								}
							</script>

						</div>

						<span id="msgAlerta"></span>

						<table id="TableLivros" style="width:100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>Nome</th>
										<th>Editora</th>
										<th>Tombo</th>
										<th>Quantidade</th>
										<th>Gênero</th>
										<th>Idioma</th>
										<th>Visualizar</th>
										<th>Editar</th>
										<th>Excluir</th>
									</tr>
								</thead>
							</table>	
						</table>
					</div>
				</div>

				<!-- Criando Modal Visualizar -->
				<div class="modal fade" id="visLivrosModal" tabindex="-1" arial-labelledby="visLivrosModalLabel" arial-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="visLivrosModalLabel">Detalhes do Livro</h5>
							</div>
							<div class="modal-body">
								<dl class="row">
									<dt class="col-sm-3">ID</dt>
									<dd class="col-sm-9"><span id="idLiv"></span></dd>

									<dt class="col-sm-3">Nome</dt>
									<dd class="col-sm-9"><span id="nomeLivro"></span></dd>

									<dt class="col-sm-3">Editora</dt>
									<dd class="col-sm-9"><span id="editoraLivro"></span></dd>

									<dt class="col-sm-3">Tombo</dt>
									<dd class="col-sm-9"><span id="tomboLivro"></span></dd>

									<dt class="col-sm-3">Quantidade</dt>
									<dd class="col-sm-9"><span id="quantLivro"></span></dd>

									<dt class="col-sm-3">Gênero</dt>
									<dd class="col-sm-9"><span id="geLivro"></span></dd>

									<dt class="col-sm-3">Idioma</dt>
									<dd class="col-sm-9"><span id="idiomaLivro"></span></dd>

								</dl>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Criando Modal Editar -->
				<div class="modal fade" id="editLivrosModal" tabindex="-1" arial-labelledby="editLivrosModalLabel" arial-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editLivrosModalLabel">Editar Livros</h5>
							</div>
							<div class="modal-body">
								<span id="msgAlertErroEdit"></span>
								<form method="POST" id="form-edit-livros" action="../Modais/editarLivro.php">
									<div class="mb-3">
										<input type="text" class="form-control" name="idLivro" id="editId" required autocomplete="off" readonly>
									</div>
									<div class="mb-3">
										<label for="form-control">Nome do Livro:</label>
										<input type="text" class="form-control" name="NomeLivro" id="editNome" required autocomplete="off" >
									</div>
									<div class="mb-3">
										<label for="form-control">Editora:</label>
										<input type="text" class="form-control" name="EditoraLivro" id="editEditora" required autocomplete="off" >
									</div>
									<div class="mb-3">
										<label for="form-control">Tombo:</label>
										<input type="text" class="form-control" name="TomboLivro" id="editTombo" required autocomplete="off" >
									</div>
									<div class="mb-3">
										<label for="form-control">Selecione um Gênero:</label>
										<select name="generoLivro" id="editGenero" class="form-control" required>
											
											<option value="">Selecione um Gênero</option>
											<option value="1">Autoajuda</option>
											<option value="2">Biografia</option>
											<option value="3">Clássico</option>
											<option value="4">Conto</option>
											<option value="5">Fantasia</option>
											<option value="6">Ficção</option>
											<option value="7">Poesia</option>
											<option value="8">Romance</option>
											<option value="9">Outro</option>
											<option value="10">Crônica</option>
											<option value="11">Quadrinhos</option>
											<option value="12">Didático</option>
											<option value="13">Mitologia</option>
											<option value="14">Teatro</option>
										</select>
									</div>
									<div class="mb-3">
										<label for="form-control">Selecione um Idioma:</label>
										<select name="idiomaLivro" id="Idioma_idIdioma" class="form-control">
											<option value="1">Português</option>
											<option value="2">Inglês</option>
											<option value="3">Espanhol</option>
										</select>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
								<button type="submit" class="btn btn-warning" form="form-edit-livros" value="Salvar">Salvar Alterações</button>
							</div>
						</div>
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
<script src="../lib/jquery/jquery-3.7.1.min.js"></script>
<script src="../lib/datatables/dataTables.js"></script>
<script src="../lib/Bootstrap 2/bootstrap.bundle.min.js"></script>


<!-- Função do Modal Visualizar -->
<script>
	async function visLivro(idLivro) {
		//console.log(idEmprestimo);
		const dados = await fetch('../Modais/visualizarLivros.php?idLivro=' + idLivro);
		const resposta = await dados.json();
		//console.log(resposta);

		if(resposta['status']){
			const viewModal = new bootstrap.Modal(document.getElementById('visLivrosModal'));
			viewModal.show();

			document.getElementById("idLiv").innerHTML = resposta['dados'].idLivro;
			document.getElementById("nomeLivro").innerHTML = resposta['dados'].NomeLivro;
			document.getElementById("editoraLivro").innerHTML = resposta['dados'].EditoraLivro;
			document.getElementById("tomboLivro").innerHTML = resposta['dados'].Tombo;
			document.getElementById("quantLivro").innerHTML = resposta['dados'].QuantidadeLivros;
			document.getElementById("geLivro").innerHTML = resposta['dados'].GeneroLivro;
			document.getElementById("idiomaLivro").innerHTML = resposta['dados'].IdiomaLivro;
			
		}else{
			alert(resposta['msg']);
		}
	}
</script>

<!-- Função do Modal Editar -->
<script>
	const editModal = new bootstrap.Modal(document.getElementById('editLivrosModal'));
	async function editLivro(idLivro) {
		//console.log(idEmprestimo);
		const dados = await fetch('../Modais/visualizarLivros.php?idLivro=' + idLivro);
		const resposta = await dados.json();
		//console.log(resposta);

		if(resposta['status']){
			document.getElementById("msgAlerta").innerHTML = "";
			editModal.show();

			document.getElementById("editId").value = resposta['dados'].idLivro;
			document.getElementById("editNome").value = resposta['dados'].NomeLivro;
			document.getElementById("editEditora").value = resposta['dados'].EditoraLivro;
			document.getElementById("editTombo").value = resposta['dados'].Tombo;
		}else{
			alert(resposta['msg']);
		}
	}
</script>

<!-- Função do Modal Excluir -->
<script>
	async function excluirLivro(idLivro) {
		//console.log(idLivro);

		const dados = await fetch("../Controller/CExcluir_livros.php?id=" + idLivro);
		var confirmar = confirm("Você realmente quer apagar o registro selecionado?");

		//console.log(resposta);
		if(confirmar){
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-success' role='alert'>Registro apagado com sucesso!</div>";

			listarDataTables = $('#TableLivros').DataTable();
			listarDataTables.draw();
		}else{
			document.getElementById("msgAlerta").innerHTML = "<div class='alert alert-danger' role='alert'> Erro: Registro não apagado</div>";
		}
	}
</script>

<!-- Salvando informações no banco de dados -->
<script>
	const formEditLivros = document.getElementById("form-edit-livros");
	if(formEditLivros){
		formEditLivros.addEventListener("submit", async(e) => {
			e.preventDefault();
			const dadosForm = new FormData(formEditLivros);

			const dados = await fetch("../Modais/editarLivro.php", {
				method: "POST",
				body: dadosForm
			});

			const resposta = await dados.json();

			if (resposta['status']){
				//document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];

				document.getElementById("msgAlerta").innerHTML = resposta['msg'];
				document.getElementById("msgAlertErroEdit").innerHTML = "";
				formEditLivros.reset();	
				editModal.hide();
				listarDataTables = $('#TableLivros').DataTable();
				listarDataTables.draw();
			}else{
				document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
			}

		});
	}
</script>


<script>
	new DataTable('#TableLivros', {	
		ajax: {
			"url": '../View/listarLivros.php',
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