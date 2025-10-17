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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="../ArquivosExternos/icons.js"></script>
	<link rel="shortcut icon" href="../img/icon1.png" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/style.css">
	<link rel="stylesheet" href="../CSS/popup.css">
	<link rel="stylesheet" href="../lib/Bootstrap 2/bootstrap.min.css">
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
			<li class="active">
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
					<form action="../Router/cad_rotas" method="post" enctype="multipart/form-data">
						<h3>Cadastro de Usuários</h3>
						<input type="text" placeholder="ID" name="idUsuario" id="idUsuario" class="box3" autocomplete="off">
						<input type="text" placeholder="Nome" name="NomeUsuario" id="NomeUsuario" required class="box" autocomplete="off" required>
						<input type="text" placeholder="Usuário" name="UserUsuario" id="UserUsuario" required  class="box" autocomplete="off" required>
						<input type="password" placeholder="Senha" name="SenhaUsuario" id="SenhaUsuario" class="box" autocomplete="off" required>
						<input type="email" placeholder="E-mail" name="EmailUsuario" id="EmailUsuario" class="box" autocomplete="off" required>

						<input type="file" name="FotoUsuario" id="FotoUsuario" class="box">

						<center><input type="submit" value="Cadastrar" class="inline-btn" name="action"></center>
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



				.searchInput {
					width: 20% !important;
					height: 30px;
					background-color: #f2f2f2;
					border: 1px solid #ccc;
					border-radius: 5px;
					padding: 5px;
				}
			</style>
			<main>
				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Tabela de Cadastro de Usuários</h3>
							<input type="text" id="searchInput" class="searchInput" placeholder="Pesquisar...">
							<button class="pdf-button" id="pdf-button" aria-label="botão pdf" onclick="abrirAluno()">
								<i class="fas fa-file-pdf"></i></button>

							<script>
								function abrirAluno() {
									var urlDoPDF = "../pdf/usuarioPdf.php";
									window.open(urlDoPDF, '_blank');
								}
							</script>

						</div>
						<table>

							<?php
							$conexao = new CConexao();
							$conn = $conexao->getConnection();

							// Consulta para obter os dados da tabela de usuários
							$sql = "SELECT
					usuario.NomeUsuario,
					usuario.idUsuario,
					usuario.UserUsuario,
					usuario.EmailUsuario,
					usuario.camfoto
				FROM usuario";

							$result = $conn->query($sql);

							if ($result === false) {
								// Use errorInfo para obter informações sobre o erro
								$errorInfo = $conn->errorInfo();
								echo "Erro na consulta SQL: " . $errorInfo[2];
							} else {
								if ($result->rowCount() > 0) {
									$user = $result->fetchAll(PDO::FETCH_ASSOC);
									$UsuarioPorPagina = 5;
									$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
									$indiceInicial = ($paginaAtual - 1) * $UsuarioPorPagina;
									$UsuarioExibidos = array_slice($user, $indiceInicial, $UsuarioPorPagina);

									// Exibir a tabela de usuários
									echo "<table>";
									echo "<thead>";
									echo "<tr>";
									echo "<th><center>Nome</center></th>";
									echo "<th><center>ID</center></th>";
									echo "<th><center>Usuario</center></th>";
									echo "<th><center>Email</center></th>";
									echo "<th><center>Perfil</center></th>";
									echo "<th><center>Editar</center></th>";
									echo "<th><center>Excluir</center></th>";
									echo "</tr>";
									echo "</thead>";
									echo "<tbody>";

									foreach ($UsuarioExibidos as $row) {
										echo "<tr>";
										echo "<td><center>" . $row["NomeUsuario"] . "</center></td>";
										echo "<td><center>" . $row["idUsuario"] . "</center></td>";
										echo "<td><center>" . $row["UserUsuario"] . "</center></td>";
										echo "<td><center>" . $row["EmailUsuario"] . "</center></td>";
										echo "<td><center><img src='" . $row["camfoto"] . "' alt='Imagem do usuário' /></center></td>";
										echo "<td><center><button class='edit-button' data-id='$row[idUsuario]'><i class='fas fa-pencil-alt'></i></button></center></td>";

										echo "<td><div class='container'><center><button class='delete-button' type='button' onclick='handlePopup(true)' aria-label='botão excluir'><i class='fas fa-trash-alt'></i></button></center><div class='popup' id='popup'><img src='../img/decisao.png' aria-label='popup decisão'><h2 class='title'>Aviso!</h2><p class='desc'>Deseja mesmo excluir?</p><button class='close-popup-button' type='button' onclick='handlePopup(false)'>Fechar</button><a href='../Controller/CExcluir_usuario.php?id={$row["idUsuario"]}'><button class='close-popup-button'>Excluir</button></a></div></div></div></td>";
										echo "</tr>";
									}

									echo "</tbody>";
									echo "</table>";

									// Adiciona links de paginação
									echo "<div class='pagination'>";
									$totalUser = count($user);
									$totalPaginas = ceil($totalUser / $UsuarioPorPagina);
									for ($i = 1; $i <= $totalPaginas; $i++) {
										$classeAtiva = ($i === $paginaAtual) ? "active" : "";
										echo "<a class='page-link $classeAtiva' href='usuarios.php?pagina=$i'>$i</a>";
									}
									echo "</div>";

									// Botão Fechar do popup fora da tabela

								} else {
									echo "<p><center>enhum usuário encontrado.</center></p>";
								}
							}

							$conn = null; // Fecha a conexão
							?>

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
<script src="../JS/popup.js"></script>
<script src="../ArquivosExternos/Jquery.js"></script>



<script>
	$(document).ready(function() {
		// Capturar clique no botão de exclusão
		$('.delete-button').click(function() {
			// Obter o ID do item a ser excluído
			var id = $(this).closest('tr').find('td:eq(1)').text(); // Considerando que o ID está na segunda coluna

			// Mostrar o popup de confirmação
			handlePopup(true);

			// Preencher o link de exclusão com o ID correto
			var linkExclusao = '../Controller/CExcluir_usuario.php?id=' + id;
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
				var rowId = $(this).find('td:eq(1)').text(); // Considerando que o ID está na segunda coluna
				if (rowId == id) {
					var nome = $(this).find('td:eq(0)').text();
					var usuario = $(this).find('td:eq(2)').text();
					var email = $(this).find('td:eq(3)').text();

					// Preencher os campos do formulário com os dados obtidos
					$('#idUsuario').val(id);
					$('#NomeUsuario').val(nome);
					$('#UserUsuario').val(usuario);
					$('#EmailUsuario').val(email);

					// Alterar o modo de ação para editar
					$('#modoAcao').val('editar');
					// Alterar o valor do botão para refletir a ação de edição
					$('input[type="submit"]').val('Editar');
					// Alterar a rota do formulário para a rota de atualização de usuários
					$('form').attr('action', '../Router/usu_rotas.php'); // Alterar a action do formulário para a rota correta
					// Alterar o nome do botão para identificar a ação como atualização
					$('input[type="submit"]').attr('name', 'Editar');
				}
			});
		});
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