@if(isset($product['id']))
    @section("scripts-top")

        <script>
                <?php
                /** @var \App\Models\Product $product * */
                ?>
            var productData = <?= json_encode($product->getBasicInfo()) ?>;
            @if(isset($cart['uuid']))
                var cartUuid = "<?=$cart['uuid']?>";
            @endif
        </script>

    @endsection
@endif