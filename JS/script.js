// Seleciona todos os elementos âncora (links) dentro de <li> elementos dentro do elemento com ID 'sidebar'
const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

// Para cada link no menu lateral, adiciona um ouvinte de eventos de clique
allSideMenu.forEach((item) => {
    const li = item.parentElement;

    item.addEventListener("click", function() {
        // Remove a classe 'active' de todos os itens do menu lateral
        allSideMenu.forEach((i) => {
            i.parentElement.classList.remove("active");
        });
        // Adiciona a classe 'active' ao elemento <li> que foi clicado
        li.classList.add("active");
    });
});

// HIDE MENU LATERAL
// Seleciona o ícone de barra de menu no conteúdo e o elemento sidebar
const menuBar = document.querySelector("#content nav .fas.fa-bars");
const sidebar = document.getElementById("sidebar");

// Função para alternar a classe 'hide' no elemento sidebar
function toggleSidebar() {
    sidebar.classList.toggle("hide");

    // Obtém o estado atual da classe 'hide'
    const isSidebarHidden = sidebar.classList.contains("hide");

    // Armazena o estado no localStorage
    localStorage.setItem("sidebarHidden", JSON.stringify(isSidebarHidden));
}

// Adiciona um ouvinte de evento de clique ao ícone de barra de menu
menuBar.addEventListener("click", toggleSidebar);

// Recupera o estado armazenado no localStorage ao carregar a página
document.addEventListener("DOMContentLoaded", function() {
    const storedSidebarHidden = localStorage.getItem("sidebarHidden");

    // Se houver um estado armazenado, aplica-o à classe 'hide'
    if (storedSidebarHidden !== null) {
        const isSidebarHidden = JSON.parse(storedSidebarHidden);
        if (isSidebarHidden) {
            sidebar.classList.add("hide");
        }
    }
});

// DAKMODE
// Seleciona o elemento de comutação de modo
const switchMode = document.getElementById("switch-mode");
const body = document.body;

// Define uma função para habilitar o modo escuro
function enableDarkMode() {
    body.classList.add("dark");
    localStorage.setItem("darkMode", "enabled");
}

// Define uma função para desabilitar o modo escuro
function disableDarkMode() {
    body.classList.remove("dark");
    localStorage.setItem("darkMode", "disabled");
}

// Obtém o valor do modo escuro salvo no armazenamento local (localStorage)
const savedMode = localStorage.getItem("darkMode");

// Se o modo escuro estiver habilitado, ative-o e marque o interruptor
if (savedMode === "enabled") {
    enableDarkMode();
    switchMode.checked = true;
} else {
    // Caso contrário, desabilite o modo escuro e desmarque o interruptor
    disableDarkMode();
    switchMode.checked = false;
}

// Adiciona um ouvinte de evento de mudança ao interruptor de modo
switchMode.addEventListener("change", function() {
    if (this.checked) {
        // Se o interruptor estiver marcado, habilita o modo escuro
        enableDarkMode();
    } else {
        // Caso contrário, desabilita o modo escuro
        disableDarkMode();
    }
});