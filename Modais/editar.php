<?php

include_once '../Controller/CConexao.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty($dados['idEmprestimo'])){
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Tente mais tarde!</div>"]; 
}else{
    $retorna = ['status' => true, 'msg' => "<div class='alert alert-sucess' role='alert'>Continuar: " . $dados['idEmprestimo'] . "!</div>"];
}



$var = json_encode($retorna);
echo $var;

?>