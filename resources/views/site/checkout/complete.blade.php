<form action="{{ route('site.checkout.complete') }}" method="POST" onsubmit="return sendForm($(this))">
    {{ csrf_field() }}
        <input type="hidden" name="tokenAfiliado" value="{{ $tokenAfiliado }}">
        @if(isset($cart['uuid']))
            <input type="hidden" id="cart_uuid" name="cart_uuid" value="{{ $cart['uuid'] }}">
        @endif
    <div class="row checkout-step" id="checkout-step1">
        @include("site.checkout.step1")
    </div>
    <div class="row checkout-step" id="checkout-step2">
        @include("site.checkout.step2")
    </div>
    <div class="row checkout-step" id="checkout-step3">
        @include("site.checkout.step3")
    </div>

</form>
