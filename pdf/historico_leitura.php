<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

$aluno_id = isset($_GET['aluno']) ? intval($_GET['aluno']) : 0;
$sql = "
SELECT
    a.NomeAluno AS Estudante,
    l.NomeLivro,
    l.EditoraLivro,
    l.IBSMLivro,
    g.NomeGenero AS GeneroLivro,
    i.Idioma AS IdiomaLivro,
    au.NomeAutor,
    DATE_FORMAT(e.DataEmprestimo, '%d/%m/%Y') AS DataEmprestimoFormatada,
    IFNULL(DATE_FORMAT(d.DataDevolvida, '%d/%m/%Y'), '--/--/----') AS DataDevolvidaFormatada,
    CASE
        WHEN e.StatusEmprestimo = 1 THEN 'A prazo'
        WHEN e.StatusEmprestimo = 2 THEN 'Devolvido'
        WHEN e.StatusEmprestimo = 3 THEN 'Pendente'
        ELSE 'Status não definido'
    END AS Estado
FROM emprestimo e
JOIN aluno a ON e.aluno_idAluno = a.idAluno
JOIN livro l ON e.livro_idLivro = l.idLivro
LEFT JOIN devolucao d ON e.idEmprestimo = d.emprestimo_idEmprestimo
LEFT JOIN genero g ON l.Genero_idGenero = g.idGenero
LEFT JOIN autor au ON l.autor_idAutor = au.idAutor
LEFT JOIN idioma i ON l.Idioma_idIdioma = i.idIdioma
WHERE a.idAluno = $aluno_id
ORDER BY e.DataEmprestimo DESC
";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {

    $dadosAluno = $res->fetch_object();
    $nomeAluno = $dadosAluno->Estudante;

    $res->data_seek(0);

    $html = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>EducaBiblio - Histórico de Leitura</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8f8f8;
            }
            h1 {
                text-align: center;
                color: #ffffff;
                background-color: #4CAF50;
                padding: 12px;
                margin-bottom: 10px;
            }
            #library-info {
                text-align: center;
                margin: 0 40px 20px 40px;
            }
            p {
                color: #333;
                font-size: 14px;
            }
            .aluno-info {
                text-align: center;
                background-color: #e9fbe9;
                padding: 10px;
                border-radius: 8px;
                width: 70%;
                margin: 0 auto 20px auto;
                font-weight: bold;
            }
            table {
                border-collapse: collapse;
                width: 95%;
                margin: 0 auto 50px auto;
                background-color: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-size: 13px;
            }
            th, td {
                border: 1px solid #444;
                padding: 8px;
                text-align: center;
            }
            th {
                background-color: #4CAF50;
                color: white;
                font-weight: bold;
            }
            tbody tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            tbody tr:hover {
                background-color: #d8ffd8;
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                background-color: #4CAF50;
                color: white;
                padding: 10px;
                font-size: 12px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <h1>Histórico de Leitura Individual</h1>
        <div id='library-info'>
            <p>Relatório de leitura do aluno gerado pelo sistema <strong>EducaBiblio</strong>.</p>
        </div>
        <div class='aluno-info'>
            Aluno: <span style='color:#2e7d32;'>$nomeAluno</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Livro</th>
                    <th>Editora</th>
                    <th>Gênero</th>
                    <th>Idioma</th>
                    <th>Data Empréstimo</th>
                    <th>Data Devolvida</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $res->fetch_object()) {
        $html .= "
            <tr>
                <td>{$row->NomeLivro}</td>
                <td>{$row->EditoraLivro}</td>
                <td>{$row->GeneroLivro}</td>
                <td>{$row->IdiomaLivro}</td>
                <td>{$row->DataEmprestimoFormatada}</td>
                <td>{$row->DataDevolvidaFormatada}</td>
                <td>{$row->Estado}</td>
            </tr>";
    }

    $html .= "
            </tbody>
        </table>
        <div class='footer'>
            Governo do Estado do Ceará - Secretaria da Educação | EducaBiblio
        </div>
    </body>
    </html>";

} else {
    $html = "
    <html>
    <body style='font-family:Arial;text-align:center;'>
        <h2>Não há histórico de leitura para este aluno.</h2>
    </body>
    </html>";
}

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->setPaper('A4', 'portrait'); // Retrato (ideal para relatórios individuais)
$dompdf->render();

// Exibir PDF no navegador
$dompdf->stream("Historico_de_Leitura_Aluno.pdf", ["Attachment" => false]);
?>
