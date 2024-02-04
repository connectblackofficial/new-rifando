<?php
$formClass = "col-md-6";
$siteId = $site->id;
$defaultTabId = "site-config";

$tabs = [];
$tabs[] = tabConfig('geral', 'super-admin.sites.geral-tab');
$tabs[] = tabConfig('ajustes', 'super-admin.sites.ajustes-tab');
$tabs[] = tabConfig('legal', 'super-admin.sites.legal-tab');
$tabs[] = tabConfig('advanced', 'super-admin.sites.advanced-tab');
?>
@include("layouts.components.tabs")

@section("scripts-footer")
    @include("crud.cke-editor")

@endsection