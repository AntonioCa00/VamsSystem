<div class="modal fade" id="modalDetalleProveedor" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Información detallada del proveedor
                </h5>

                <button class="close" type="button" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body modal-body-scrollable">

                <div class="form-group">
                    <label>Nombre y/o razón social de la empresa:</label>
                    <h6 id="detalleNombre"></h6>
                </div>

                <div class="form-group">
                    <label>Régimen fiscal:</label>
                    <h6 id="detalleRegimen"></h6>
                </div>

                <div class="form-group">
                    <label>Sobrenombre:</label>
                    <h6 id="detalleSobrenombre"></h6>
                </div>

                <div class="form-group">
                    <label>Teléfono:</label>
                    <h6 id="detalleTelefono"></h6>
                </div>

                <div class="form-group" id="divTelefono2">
                    <label>Teléfono secundario:</label>
                    <h6 id="detalleTelefono2"></h6>
                </div>

                <div class="form-group">
                    <label>Nombre del contacto:</label>
                    <h6 id="detalleContacto"></h6>
                </div>

                <div class="form-group">
                    <label>Dirección:</label>
                    <h6 id="detalleDireccion"></h6>
                </div>

                <div class="form-group">
                    <label>Domicilio fiscal:</label>
                    <h6 id="detalleDomicilio"></h6>
                </div>

                <div class="form-group">
                    <label>RFC:</label>
                    <h6 id="detalleRFC"></h6>
                </div>

                <div class="form-group">
                    <label>Correo:</label>
                    <h6 id="detalleCorreo"></h6>
                </div>

                <div class="form-group">

                    <label>CIF en formato PDF:</label>

                    <a id="linkCIF" target="_blank">
                        <img src="{{ asset('img/pdf.png') }}" alt="PDF">
                    </a>

                    <span id="sinCIF" class="text-danger">
                        Sin documento
                    </span>

                </div>

                <hr>

                <h4 class="text-center text-primary">
                    Datos bancarios
                </h4>

                <div id="datosBancarios">

                    <div class="form-group">
                        <label>Banco:</label>
                        <h6 id="detalleBanco"></h6>
                    </div>

                    <div class="form-group">
                        <label>Número de cuenta:</label>
                        <h6 id="detalleCuenta"></h6>
                    </div>

                    <div class="form-group">
                        <label>Cuenta CLABE:</label>
                        <h6 id="detalleClabe"></h6>
                    </div>

                    <div class="form-group">

                        <label>Carátula de estado de cuenta:</label>

                        <a id="linkEstadoCuenta" target="_blank">
                            <img src="{{ asset('img/pdf.png') }}" alt="PDF">
                        </a>

                        <span id="sinEstadoCuenta" class="text-danger">
                            Sin documento
                        </span>

                    </div>

                </div>

                <div id="sinDatosBancarios" class="text-center text-danger">

                    <strong>NO TIENE DATOS BANCARIOS REGISTRADOS</strong>

                </div>

            </div>

            <div class="modal-footer">

                <a id="btnEditarProveedor"
                    class="btn btn-success">
                    Actualizar información
                </a>

                <a id="btnEliminarProveedor"
                    class="btn btn-primary"
                    data-toggle="modal"
                    data-dismiss="modal">
                    Eliminar
                </a>

            </div>

        </div>

    </div>
</div>