export function initViaCep() {

    document.querySelectorAll('.viacep').forEach(function (cepInput) {

        cepInput.addEventListener('blur', function () {

            const cep = this.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {

                    if (data.erro) {
                        return;
                    }

                    const logradouro = document.querySelector(this.dataset.logradouro);
                    const bairro = document.querySelector(this.dataset.bairro);
                    const cidade = document.querySelector(this.dataset.cidade);
                    const estado = document.querySelector(this.dataset.estado);

                    if (logradouro) logradouro.value = data.logradouro;
                    if (bairro) bairro.value = data.bairro;
                    if (cidade) cidade.value = data.localidade;
                    if (estado) estado.value = data.uf;

                });

        });

    });

}