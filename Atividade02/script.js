document.addEventListener("DOMContentLoaded", () => {
  let formulario = document.getElementById("f");
  formulario.addEventListener("submit", (e) => {
    let nome = document.getElementById("nome");
    let tipo = document.getElementsByName("tipo");
    let cpf_cnpj = document.getElementById("cpf_cnpj");

    let nomeError = document.getElementById("nomeError");
    nomeError.textContent = "";
    if (nome.value.length < 3) {
      nomeError.textContent = "O nome não pode estar vazio.";
      e.preventDefault();
    }

    let tipoError = document.getElementById("tipoError");

    let cpf_cnpjError = document.getElementById("cpf_cnpjError");
    cpf_cnpjError.textContent = "";
    tipoError.textContent = "";

    if (tipo[0].checked) {
      let status = valida_cpf(cpf_cnpj.value);
      if (!status) {
        cpf_cnpjError.textContent = "Você precisa inserir um CPF válido.";
        e.preventDefault();
      }
      return true;
    } else if (tipo[1].checked) {
      let status = valida_cnpj(cpf_cnpj.value);
      if (!status) {
        cpf_cnpjError.textContent = "Você precisa inserir um CNPJ válido.";
        e.preventDefault();
      }
    } else {
      tipoError.textContent = "Selecione um tipo de pessoa.";
      e.preventDefault();
    }
  });
});

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

    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    if (resultado != digitos.charAt(0)) return false;
    numeros = cpf.substring(0, 10);
    soma = 0;
    for (i = 11; i > 1; i--) soma += numeros.charAt(11 - i) * i;
    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
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
    tamanho = cnpj.length - 2;
    numeros = cnpj.substring(0, tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    if (resultado != digitos.charAt(0)) return false;
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    if (resultado != digitos.charAt(1)) return false;
    return true;
  } else return false;
}
