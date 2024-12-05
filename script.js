// JavaScript para adicionar animação ou ações ao clicar nos botões, se necessário
document.querySelectorAll('.nav-button').forEach(button => {
    button.addEventListener('click', function() {
        console.log(`${this.textContent} foi clicado.`);
        // Adicione qualquer outra ação ou animação que desejar
    });
})