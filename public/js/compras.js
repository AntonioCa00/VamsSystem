
// Modal para editar los comprobantes de las ordenes de compra
document.querySelectorAll('.btnEditarComprobante').forEach(button => {

    button.addEventListener('click', function () {

        let url = this.dataset.url;
        let comprobante = this.dataset.comprobante;
        document.querySelector('#formEditarComprobante')
            .setAttribute('action', url);
        if (comprobante) {
            document.querySelector('#divComprobanteActual')
                .style.display = "block";
            document.querySelector('#linkComprobante')
                .setAttribute('href', comprobante);
        } else {
            document.querySelector('#divComprobanteActual')
                .style.display = "none";
        }
    });
});

// Modal para cargar los comprobantes de pago de las ordenes de compra realizadas con tarjeta
document.querySelectorAll('.btnRegistrarPago').forEach(button => {

    button.addEventListener('click', function () {

        let url = this.dataset.url;

        document.querySelector('#formRegistrarPago')
            .setAttribute('action', url);

    });

});

// Modal para confirmar la eliminación de una orden de compra
document.querySelectorAll('.btnEliminarOrden').forEach(button => {

    button.addEventListener('click', function() {

        let url = this.dataset.url;

        let form = document.querySelector('#formEliminarOrden');

        form.setAttribute('action', url);

    });
});