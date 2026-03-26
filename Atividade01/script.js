document.addEventListener("DOMContentLoaded", function () {

    var form = document.getElementById("f");

    form.addEventListener("submit", function (e) {
        // Evita o envio padrão do formulário para validar primeiro
        e.preventDefault();

        // Limpa as mensagens de erro anteriores
        document.getElementById("msg-nome").innerHTML = "";
        document.getElementById("msg-tipo").innerHTML = "";
        document.getElementById("msg-cpf_cnpj").innerHTML = "";

        // Captura os elementos
        var nome = document.getElementById("nome");
        var pfisica = document.getElementById("pfisica");
        var pjuridica = document.getElementById("pjuridica");
        var cpfCnpj = document.getElementById("cpf_cnpj");

        // 1. Validação do Nome
        if (nome.value.trim() === "") {
            document.getElementById("msg-nome").innerHTML = "O nome não pode estar vazio.";
            nome.focus();
            return;
        }

        // 2. Validação do Tipo de Pessoa
        if (!pfisica.checked && !pjuridica.checked) {
            document.getElementById("msg-tipo").innerHTML = "Selecione o tipo de pessoa.";
            // Dá o foco no primeiro radio button
            pfisica.focus();
            return;
        }

        // 3. Validação do CPF ou CNPJ
        var docValor = cpfCnpj.value.trim();

        if (pfisica.checked) {
            if (!valida_cpf(docValor)) {
                document.getElementById("msg-cpf_cnpj").innerHTML = "CPF inválido.";
                cpfCnpj.focus();
                return;
            }
        } else if (pjuridica.checked) {
            if (!valida_cnpj(docValor)) {
                document.getElementById("msg-cpf_cnpj").innerHTML = "CNPJ inválido.";
                cpfCnpj.focus();
                return;
            }
        }

        // Se todas as validações passarem, envia o formulário
        form.submit();
    });
});

// === FUNÇÕES DE VALIDAÇÃO FORNECIDAS PELO PROFESSOR ===

function valida_cpf(cpf) {
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11) return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }

    if (!digitos_iguais) {
        numeros = cpf.substring(0, 9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--) soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;
        numeros = cpf.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--) soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) return false;
        return true;
    } else return false;
}

function valida_cnpj(cnpj) {
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15) return false;
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0, tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) return false;
        return true;
    } else return false;
}