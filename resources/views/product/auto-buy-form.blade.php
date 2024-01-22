<div class="row mt-4">
    <input type="hidden" name="popularCheck"
           id="popularCheck-{{ $product->id }}"
           value="{{ $product->getCompraMaisPopularFromCache() }}">

    @foreach ($product->comprasAutoFromCache() as $compra)
        @if($compra->qty>0)
            <div class="col-md-6 mt-2">
                <div class="input-group">
                    <div class="input-group-prepend" style="height: 37px;">
                    <span class="input-group-text">
                        <input type="radio" class="mr-2" data-id="{{ $compra->id }}" data-rifa="{{ $product->id }}"
                               id="popular-{{ $compra->id }}" onchange="changePopular(this)"
                               name="popular"
                               {{ $compra->popular ? 'checked' : '' }}>
                        <label for="popular-{{ $compra->id }}" style="cursor: pointer">+ POPULAR</label>
                    </span>
                    </div>
                    <input type="number"
                           class="form-control"
                           name="compra[{{ $compra->id }}]"
                           value="{{ $compra->qtd }}">
                </div>
            </div>
        @endif
    @endforeach
</div>
