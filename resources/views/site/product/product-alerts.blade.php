@if ($errors->any())
    @foreach ($errors->all() as $error)
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border: none;">
                    <div class="modal-header" style="background-color: #020f1e;color: #fff;">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-info-circle"></i> Aviso
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="color: #fff;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #020f1e;color: #fff;">
                        <div style="text-align: center;">{{ $error }}</div>
                    </div>
                    <div class="modal-footer" style="background-color: #020f1e;color: #fff;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#exampleModal').modal({
                show: true
            });
        </script>
    @endforeach
@endif
