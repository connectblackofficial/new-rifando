<div class="row advanced-tab-container">

    <nav style="margin-top: 20px">
        <ul class="nav nav-tabs" id="<?=$defaultTabId?>" role="tablist"
            style="font-size: 12px;">
            @foreach($tabs as $tabData)
                    <?php
                    if ($loop->iteration == 1) {
                        $cls = "active";
                    } else {
                        $cls = "";

                    }
                    $tabId = $tabData['name'];
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?=$cls?>" id="<?=$tabId?>-tab"
                       data-toggle="tab"
                       href="#{{ $tabId }}-content"
                       role="tab" aria-controls="{{ $tabId }}-content"
                       aria-selected="true">{{$tabData['title']}}</a>
                </li>
            @endforeach
        </ul>
    </nav>
    <div class="tab-content" id="<?=$defaultTabId?>-content" style="padding: 20px">

        @foreach($tabs as $tabData)
                <?php
                if ($loop->iteration == 1) {
                    $cls = "show active";
                } else {
                    $cls = "";
                }
                $tabId = $tabData['name'];
                ?>
            <div class="tab-pane fade <?=$cls?>" id="{{ $tabId }}-content" role="tabpanel"
                 aria-labelledby="<?=$tabId?>-tab">
                @include($tabData['inc'])
            </div>

        @endforeach


    </div>
    <div class="form-group">
        <input class="btn btn-primary text-capitalize" type="submit" value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
    </div>

</div>