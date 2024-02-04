<div class="row">
    <div class="<?=$formClass?>">
        <?php
        $baseDomain = "." . env("BASE_DOMAIN");
        echo inputGroup('subdomain', $baseDomain, $site);
        ?>
    </div>

    <div class="<?=$formClass?>">
        <?= inputText('name', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= imageInput('logo', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= imageInput('banner', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= inputText('email', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= inputText('whatsapp', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= textarea('description', $site) ?>
    </div>
    <div class="<?=$formClass?>">
        <?= textarea('footer', $site) ?>
    </div>
</div>