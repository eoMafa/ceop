// Alerta de sucesso/erro vindo do Laravel (session)
document.addEventListener('DOMContentLoaded', function () {

    if (window.sessionSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: window.sessionSuccess,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
        });
    }

    if (window.sessionError) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: window.sessionError,
            confirmButtonText: 'OK',
        });
    }

    // Confirmação de exclusão/inativação
    document.querySelectorAll('[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;

            Swal.fire({
                icon: 'warning',
                title: 'Tem certeza?',
                text: form.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: 'Sim, confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    self.submit();
                }
            });
        });
    });

});