import Cleave from 'cleave.js';

export function applyCleave(selector, options) {

    document.querySelectorAll(selector).forEach(function (element) {
        new Cleave(element, options);
    });

}

export function applyCpfCnpjMask(selector) {

    document.querySelectorAll(selector).forEach(function (element) {

        let cleave = new Cleave(element, cpfConfig());

        element.addEventListener('input', function () {

            const value = element.value.replace(/\D/g, '');

            cleave.destroy();

            cleave = new Cleave(
                element,
                value.length > 11 ? cnpjConfig() : cpfConfig()
            );

            cleave.setRawValue(value);

        });

    });

}

function cpfConfig() {
    return {
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        numericOnly: true
    };
}

function cnpjConfig() {
    return {
        delimiters: ['.', '.', '/', '-'],
        blocks: [2, 3, 3, 4, 2],
        numericOnly: true
    };
}