
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

document.querySelectorAll('.btnDetalleProveedor').forEach(button => {

    button.addEventListener('click', function () {

        let datos = this.dataset;

        //=========================
        // DATOS GENERALES
        //=========================

        document.querySelector('#detalleNombre').textContent = datos.nombre;
        document.querySelector('#detalleRegimen').textContent = datos.regimen;
        document.querySelector('#detalleSobrenombre').textContent = datos.sobrenombre;
        document.querySelector('#detalleTelefono').textContent = datos.telefono;
        document.querySelector('#detalleContacto').textContent = datos.contacto;
        document.querySelector('#detalleDireccion').textContent = datos.direccion;
        document.querySelector('#detalleDomicilio').textContent = datos.domicilio;
        document.querySelector('#detalleRFC').textContent = datos.rfc;
        document.querySelector('#detalleCorreo').textContent = datos.correo;

        //=========================
        // TELEFONO SECUNDARIO
        //=========================

        if (datos.telefono2) {

            document.querySelector('#divTelefono2').style.display = "block";
            document.querySelector('#detalleTelefono2').textContent = datos.telefono2;

        } else {

            document.querySelector('#divTelefono2').style.display = "none";

        }

        //=========================
        // CIF
        //=========================

        if (datos.cif) {

            document.querySelector('#linkCIF').style.display = "inline";
            document.querySelector('#sinCIF').style.display = "none";

            document.querySelector('#linkCIF').href = datos.cif;

        } else {

            document.querySelector('#linkCIF').style.display = "none";
            document.querySelector('#sinCIF').style.display = "inline";

        }

        //=========================
        // DATOS BANCARIOS
        //=========================

        if (datos.banco || datos.cuenta || datos.clabe) {

            document.querySelector('#datosBancarios').style.display = "block";
            document.querySelector('#sinDatosBancarios').style.display = "none";

            document.querySelector('#detalleBanco').textContent = datos.banco;
            document.querySelector('#detalleCuenta').textContent = datos.cuenta;
            document.querySelector('#detalleClabe').textContent = datos.clabe;

        } else {

            document.querySelector('#datosBancarios').style.display = "none";
            document.querySelector('#sinDatosBancarios').style.display = "block";

        }

        //=========================
        // ESTADO DE CUENTA
        //=========================

        if (datos.estadocuenta) {

            document.querySelector('#linkEstadoCuenta').style.display = "inline";
            document.querySelector('#sinEstadoCuenta').style.display = "none";

            document.querySelector('#linkEstadoCuenta').href = datos.estadocuenta;

        } else {

            document.querySelector('#linkEstadoCuenta').style.display = "none";
            document.querySelector('#sinEstadoCuenta').style.display = "inline";

        }

        //=========================
        // BOTONES
        //=========================

        document.querySelector('#btnEditarProveedor')
            .href = datos.editar;

        document.querySelector('#btnEliminarProveedor')
            .dataset.url = datos.eliminar;

    });

});