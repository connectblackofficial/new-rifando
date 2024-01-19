<center>
    <button type="button"
            class="btn btn-sm btn-info"
            data-id="{{ $product->id }}"
            onclick="addFoto(this)">+ Foto(s)
    </button>
</center>
<div class="row d-flex justify-content-center mt-4">
    <?php
    $fotos = $product->fotos()
    ?>

    @foreach ($fotos as $key => $foto)
        <div class="col-md-4 text-center"
             id="foto-{{ $foto->id }}">
            <img src="{{ imageAsset($foto->name) }}"
                 width="200"
                 style="border-radius: 10px;">
            @if($key != 0)
                <a data-qtd="{{ count($fotos)}}"
                   href="javascript:void(0)"
                   class="delete btn btn-danger"
                   onclick="excluirFoto(this)"
                   data-id="{{ $foto->id }}"><i
                            class="bi bi-trash3"></i></a>
            @endif

        </div>
    @endforeach

</div>