<div id="deleteEmployeeModal{{ $product->id }}" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('destroy') }}" method="POST" enctype="multipart/form-data">
                @method('DELETE')
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">Deletar Rifa</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir esse registros?</p>
                    <p class="text-warning"><small>Essa ação não pode ser desfeita...</small></p>
                    <input name="deleteId" type="hidden" id="deleteId"
                           value="{{ $product->id }}">
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal"
                           value="Cancelar">
                    <input type="submit" class="btn btn-danger" value="Deletar">
                </div>
            </form>
        </div>
    </div>
</div>