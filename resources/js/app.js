import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

import * as tabler from '@tabler/core';

import './alerts.js';

import {
    applyCleave,
    applyCpfCnpjMask
} from './helpers/masks';

import {
    initViaCep
} from './helpers/viacep';

window.bootstrap = tabler;
window.Tabler = tabler;

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.interactionPlugin = interactionPlugin;
window.listPlugin = listPlugin;

window.Swal = Swal;

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    applyCpfCnpjMask('.cpf-cnpj');

    applyCleave('.cep', {
        delimiters: ['-'],
        blocks: [5, 3],
        numericOnly: true
    });

    applyCleave('.telefone', {
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 5, 4],
        numericOnly: true
    });

    initViaCep();

});