import './bootstrap';
import '@tabler/core/dist/js/tabler.min.js';
import './alerts.js';
import Alpine from 'alpinejs';
import Cleave from 'cleave.js';
import Swal from 'sweetalert2';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.interactionPlugin = interactionPlugin;
window.listPlugin = listPlugin;

window.Swal = Swal;

window.Alpine = Alpine;
Alpine.start();

// Máscaras globais — inicializa quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function () {

    if (document.getElementById('cpf')) {
        new Cleave('#cpf', {
            delimiters: ['.', '.', '-'],
            blocks: [3, 3, 3, 2],
            numericOnly: true,
        });
    }

    if (document.getElementById('cep')) {
        new Cleave('#cep', {
            delimiters: ['-'],
            blocks: [5, 3],
            numericOnly: true,
        });
    }

    if (document.getElementById('telefone')) {
        new Cleave('#telefone', {
            delimiters: ['(', ') ', '-'],
            blocks: [0, 2, 5, 4],
            numericOnly: true,
        });
    }

});