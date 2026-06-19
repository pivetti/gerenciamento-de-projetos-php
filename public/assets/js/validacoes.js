document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation-js');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            limparValidacao(form);

            var valido = validarObrigatorios(form);
            valido = validarNumeros(form) && valido;
            valido = validarDatasProjeto(form) && valido;

            if (!valido) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});

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
