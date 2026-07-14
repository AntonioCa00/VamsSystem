<div class="modal fade" id="modalEditarComprobante" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarComprobante"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        Editar comprobante
                    </h5>
                    <button class="close"
                        type="button"
                        data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="divComprobanteActual">
                        <label>Comprobante actual</label>
                        <br>

                        <a id="linkComprobante"
                            target="_blank">
                            Ver comprobante
                        </a>
                    </div>
                    <br>

                    <div class="form-group">
                        <label>Nuevo comprobante</label>
                        <input
                            type="file"
                            name="comprobante_pago"
                            class="form-control">
                    </div>
                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Cancelar
                    </button>

                    <button
                        class="btn btn-primary">
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>