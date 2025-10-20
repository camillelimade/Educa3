<?php

    //Inclui os valores presentes no arquivo CConexao.php
    include_once "../Controller/CConexao.php";
    $conexao = new CConexao();
    $conn = $conexao->getConnection();

    function todos_os_livros(){
        global $conn;
        //Requisição (query)
        $sql = "SELECT
        livro.idLivro,
        livro.NomeLivro,
        livro.EditoraLivro,
        livro.Tombo,
        livro.IBSMLivro,
        livro.QuantidadeLivros,
        genero.NomeGenero AS GeneroLivro,
        idioma.Idioma AS IdiomaLivro,
        livro.LocalLivro,
        livro.PrateleiraLivro,
        livro.ColunaLivro,
        autor.NomeAutor
        FROM
            livro
        LEFT JOIN
            genero ON livro.Genero_idGenero = genero.idGenero
        LEFT JOIN
            autor ON livro.Autor_idAutor = autor.idAutor
        LEFT JOIN
            idioma ON livro.Idioma_idIdioma = idioma.idIdioma";

        //Recebe a query
        $stmt = $conn->prepare($sql);

        //Executa a query
        $stmt->execute();

        //retorna um array multidimensional
        $resultado = $stmt->fetchAll();

        return $stmt->rowCount();
    }



?>