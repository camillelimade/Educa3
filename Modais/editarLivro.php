<?php

include_once '../Controller/CConexao.php';

$conexao = new CConexao();
$conn = $conexao->getConnection();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['idLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Tente mais tarde!'];
} elseif (empty($dados['NomeLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo nome!'];
} elseif (empty($dados['EditoraLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo editora!'];
} elseif (empty($dados['TomboLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário preencher o campo tombo!'];
} elseif (empty($dados['generoLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário selecionar um gênero!'];
} elseif (empty($dados['idiomaLivro'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário selecionar um idioma!'];
} else {
    $query_Livros = "UPDATE livro 
                SET NomeLivro=:NomeLivro, 
                EditoraLivro=:EditoraLivro, 
                Tombo=:Tombo,
                Genero_idGenero=:GeneroLivro,
                Idioma_idIdioma=:IdiomaLivro
                WHERE idLivro=:idLivro";
    
    
    $edit_Livros = $conn->prepare($query_Livros);
    $edit_Livros->bindParam(':idLivro', $dados['idLivro']);
    $edit_Livros->bindParam(':NomeLivro', $dados['NomeLivro']);
    $edit_Livros->bindParam(':EditoraLivro', $dados['EditoraLivro']);
    $edit_Livros->bindParam(':Tombo', $dados['TomboLivro']);
    $edit_Livros->bindParam(':GeneroLivro', $dados['generoLivro']);
    $edit_Livros->bindParam(':IdiomaLivro', $dados['idiomaLivro']);

    if ($edit_Livros->execute()) {
        $retorna = ['status' => true, 'msg' => '<div class="alert alert-success" role="alert">Livro editado com sucesso</div>'];
    } else {
        $retorna = ['status' => false, 'msg' =>'<div class="alert alert-danger" role="alert">Erro: Livro não editado com sucesso</div>'];
    }
}

$var = json_encode($retorna);
echo $var;

?>