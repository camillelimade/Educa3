<?php 
    //Conexao com banco de dados
    include_once '../Controller/CConexao.php';
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    $idLivro = filter_input(INPUT_GET, "idLivro", FILTER_SANITIZE_NUMBER_INT);

    if(!empty($idLivro)){
        $sqlLivros = "SELECT
        livro.idLivro AS idLivro,
        livro.NomeLivro,
        livro.EditoraLivro,
        livro.Tombo,
        livro.IBSMLivro,
        livro.QuantidadeLivros,
        genero.NomeGenero AS GeneroLivro,
        idioma.Idioma AS IdiomaLivro,
        autor.NomeAutor
    FROM
        livro
    LEFT JOIN
         genero ON livro.Genero_idGenero = genero.idGenero
    LEFT JOIN
        autor ON livro.Autor_idAutor = autor.idAutor
    LEFT JOIN
        idioma ON livro.Idioma_idIdioma = idioma.idIdioma
        WHERE idLivro = :idLivro";

        $result_Livro = $conn->prepare($sqlLivros);
        $result_Livro->bindParam(':idLivro', $idLivro);
        $result_Livro->execute();

        if ($result_Livro and ($result_Livro->rowCount() != 0)){
            $row_livro = $result_Livro->fetch(PDO::FETCH_ASSOC);
            $retorna = ['status' => true, 'dados' => $row_livro];
        } else{
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum livro encontrado 2 !</div>"];
        }

    } else{
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum livro encontrado!</div>"];
    }

    $var = json_encode($retorna);
    echo $var;
?>