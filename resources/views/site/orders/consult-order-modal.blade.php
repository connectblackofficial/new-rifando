<form action="{{ route('site.participant.process_check') }}" method="POST"
      onsubmit="return sendForm($(this))">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @include("site.customer.phone")
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-block btn-primary button-search-phone" type="submit">
                <strong>Consultar</strong>
            </button>
        </div>
    </div>


</form>
