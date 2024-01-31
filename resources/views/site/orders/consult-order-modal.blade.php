<form action="{{ route('bookProductManualy') }}" id="form-checkout" method="POST">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @include("site.customer.phone")
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-block btn-primary button-search-phone" type="button">
                <strong>Consultar</strong>
            </button>
        </div>
    </div>


</form>
