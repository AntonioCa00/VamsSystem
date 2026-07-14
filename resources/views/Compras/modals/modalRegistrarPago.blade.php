<div class="modal fade" id="modalRegistrarPago" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="formRegistrarPago"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Registrar pago de orden de compra
                    </h5>

                    <button class="close"
                        type="button"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Favor de cargar su comprobante de pago:
                        </label>

                        <input 
                            name="comprobante_pago"
                            type="file"
                            class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button 
                        class="btn btn-secondary"
                        type="button"
                        data-dismiss="modal">
                        Cancelar
                    </button>

                    <button 
                        type="submit"
                        class="btn btn-primary">
                        Registrar pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>