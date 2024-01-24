<form action="{{ route('bookProductManualy') }}" id="form-checkout" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <label style="color: #000">
            <strong>Informe seu telefone</strong>
        </label>
        <input type="text" class="form-control numbermask"
               style="background-color: #fff;border: none;color: #333;" name="telephone" id="telephone1"
               placeholder="(00) 90000-0000" maxlength="15" required>
    </div>
    <div class="form-group">
        <button class="btn btn-block btn-primary"
                type="button">
            <strong >Continuar</strong>
        </button>
    </div>
</form>