<div>
    <img id="customer-photo" src="{{ cdnImageAsset('default-user.jpg') }}">
</div>
<div class="ml-2">
    <h4 id="customer-name"></h4>
    <h5 id="customer-phone"></h5>
</div>
<?= submitBtn('Concluir cadastro e pagar <i class="bi bi-arrow-right-circle"></i>', 'btn-checkout-complete2') ?>
<a href="#" onclick="return resetCheckoutModal();">Alterar telefone</a>
