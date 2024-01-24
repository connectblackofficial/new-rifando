<?php

return [
    'product_uuid_rule' => "required|exists:products,uuid",
    'cart_uuid' => 'required|exists:carts,uuid',
    'phone_rule' => 'required|min:10|max:15'
];
