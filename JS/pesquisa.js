let select = document.querySelector('#aluno_idAluno');
select.addEventListener('change', () => {
    console.log(select.value);
    $.ajax({
        "url": "fetch.php",
        "type": "POST",
        "data": "request=" + select,
        success:function(data){
            $(".container").html(data);
        }
    })
})