function mudaCorBotao(valor, botao) {
    // Define as cores que serão usadas
    const cores = {
      'amarelo': 'yellow',
      'verde': 'green',
      'azul': 'blue',
      // Adicione mais cores aqui se necessário
    };
  
    switch (valor) {
      case 1:
        valor = "Ativo";
        botao.style.backgroundColor = cores['azul'];
        break;
      case 2:
        valor = "Devolvido";
        botao.style.backgroundColor = cores['verde'];
        break;
      case 3:
        valor = "Devolvido com pendência";
        botao.style.backgroundColor = cores['amarelo'];
        break;
      default:
        console.log('Valor inválido');
    }
  }