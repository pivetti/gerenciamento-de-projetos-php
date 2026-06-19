document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation-js');
    var telefones = document.querySelectorAll('input[name="telefone"]');

    telefones.forEach(function (campo) {
        campo.value = aplicarMascaraTelefone(campo.value);

        campo.addEventListener('input', function () {
            campo.value = aplicarMascaraTelefone(campo.value);
        });
    });

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            limparValidacao(form);

            var valido = validarObrigatorios(form);
            valido = validarNumeros(form) && valido;
            valido = validarEmails(form) && valido;
            valido = validarTamanhos(form) && valido;
            valido = validarDatasProjeto(form) && valido;

            if (!valido) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});

function aplicarMascaraTelefone(valor) {
    var numeros = valor.replace(/\D/g, '').slice(0, 11);

    if (numeros.length <= 2) {
        return numeros;
    }

    if (numeros.length <= 6) {
        return '(' + numeros.slice(0, 2) + ') ' + numeros.slice(2);
    }

    if (numeros.length <= 10) {
        return '(' + numeros.slice(0, 2) + ') ' + numeros.slice(2, 6) + '-' + numeros.slice(6);
    }

    return '(' + numeros.slice(0, 2) + ') ' + numeros.slice(2, 7) + '-' + numeros.slice(7);
}

function limparValidacao(form) {
    form.querySelectorAll('.is-invalid').forEach(function (campo) {
        campo.classList.remove('is-invalid');
    });

    form.querySelectorAll('.invalid-feedback.js-feedback').forEach(function (feedback) {
        feedback.remove();
    });
}

function validarObrigatorios(form) {
    var valido = true;
    var campos = form.querySelectorAll('[required]');

    campos.forEach(function (campo) {
        if (!campo.value || campo.value.trim() === '') {
            marcarInvalido(campo, 'Campo obrigatorio.');
            valido = false;
        }
    });

    return valido;
}

function validarNumeros(form) {
    var valido = true;
    var campos = form.querySelectorAll('input[type="number"]');

    campos.forEach(function (campo) {
        if (campo.value === '') {
            return;
        }

        var valor = Number(campo.value);
        var minimo = campo.getAttribute('min');
        var maximo = campo.getAttribute('max');

        if (Number.isNaN(valor)) {
            marcarInvalido(campo, 'Informe um numero valido.');
            valido = false;
            return;
        }

        if (minimo !== null && valor < Number(minimo)) {
            marcarInvalido(campo, 'Valor abaixo do minimo permitido.');
            valido = false;
        }

        if (maximo !== null && valor > Number(maximo)) {
            marcarInvalido(campo, 'Valor acima do maximo permitido.');
            valido = false;
        }
    });

    return valido;
}

function validarEmails(form) {
    var valido = true;
    var campos = form.querySelectorAll('input[type="email"]');

    campos.forEach(function (campo) {
        if (campo.value === '') {
            return;
        }

        if (!campo.checkValidity()) {
            marcarInvalido(campo, 'Informe um email valido.');
            valido = false;
        }
    });

    return valido;
}

function validarTamanhos(form) {
    var valido = true;
    var campos = form.querySelectorAll('[minlength]');

    campos.forEach(function (campo) {
        var minimo = Number(campo.getAttribute('minlength'));

        if (campo.value && campo.value.length < minimo) {
            marcarInvalido(campo, 'Informe pelo menos ' + minimo + ' caracteres.');
            valido = false;
        }
    });

    return valido;
}

function validarDatasProjeto(form) {
    var inicio = form.querySelector('[name="dataInicio"]');
    var fim = form.querySelector('[name="dataFim"]');

    if (!inicio || !fim || !inicio.value || !fim.value) {
        return true;
    }

    if (fim.value < inicio.value) {
        marcarInvalido(fim, 'A data fim nao pode ser anterior a data inicio.');
        return false;
    }

    return true;
}

function marcarInvalido(campo, mensagem) {
    campo.classList.add('is-invalid');

    var feedback = document.createElement('div');
    feedback.className = 'invalid-feedback js-feedback';
    feedback.textContent = mensagem;

    campo.insertAdjacentElement('afterend', feedback);
}
