<div class="modal fade" id="modalEliminarOrden" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    ¿Ha tomado una decisión?
                </h5>

                <button class="close" type="button" data-dismiss="modal">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                Selecciona confirmar para eliminar esta orden de compra
            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Cancelar
                </button>

                <form id="formEliminarOrden" method="POST">

                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-primary">
                        Confirmar
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>