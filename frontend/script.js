document.getElementById("enviar-resposta").addEventListener("click", function() {
        const perguntaId = document.getElementById("pergunta-id").value; // ID da pergunta
        const respostaSelecionada = document.querySelector('input[name="resposta"]:checked'); // Resposta selecionada

        if (respostaSelecionada) {
            // Envia os dados para o servidor via AJAX
            fetch("salvar_resposta.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `pergunta_id=${perguntaId}&resposta=${respostaSelecionada.value}`
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Exibe resposta do servidor
                // Exibir feedback de sucesso ou erro
            })
            .catch(error => console.error('Erro:', error));
        } else {
            alert("Por favor, selecione uma resposta!");
        }
    });

