<div class="table-title">
    <div class="row mb-3">
        <div class="col d-flex justify-content-center">
            <h2><?= $pgTitle ?></h2>
        </div>

        <div class="row-12 mb-3 d-flex" style="justify-content: space-between;">

            <form method="GET" class="form-inline my-2 my-lg-0">
                <div class="modal fade" id="advancedFiltersModal2" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-capitalize"
                                    id="exampleModalLabel"><?= htmlLabel("advanced filters") ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="advanced-search-modal">
                                <div class="row">

                                    @foreach($advancedFields as $aName=>$aData)
                                            <?php
                                            echo '<div class="col-md-6 mt-2">';
                                            if ($aData['type'] == 'select') {
                                                echo selectField($aName, $aData['options'], $getDataFromRequest);
                                            } else {
                                                echo inputField($aName, $aData['type'], $getDataFromRequest);
                                            }
                                            echo '</div>';
                                            ?>
                                    @endforeach


                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{route($routeIndex)}}">
                                    <button type="button" class="btn btn-secondary">
                                        <?= htmlLabel("clear filters") ?>
                                    </button>
                                </a>

                                <button type="submit" class="btn btn-primary"><?= htmlLabel("apply filters") ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <input class="form-control mr-sm-2" type="search" name="search"
                       placeholder="<?=htmlLabel("search")?>" aria-label="Search">
                <button class="btn btn-outline-secondary my-2 my-sm-0 border border-secondary text-dark text-capitalize"
                        type="submit"><?= htmlLabel("search") ?>
                </button>

                <button data-toggle="modal" data-target="#advancedFiltersModal2"
                        class="btn ml-2 btn-outline-secondary my-2 my-sm-0 border border-secondary text-dark text-capitalize"
                        type="button"><?= htmlLabel("advanced filters") ?>
                </button>

            </form>

            @if(isset($permissions['create']) && $permissions['create']===true)
                @if(view()->exists($viewCreateBtn))
                    @include($viewCreateBtn)
                @else
                    <a href="{{route($routeCreate)}}" class="btn btn-success d-flex align-items-center"
                       style="font-size:30px;width: 100px;justify-content: center;height: 50px;margin-left: 5px;">
                        <i class="bi bi-plus-square "></i>
                    </a>
                @endif

            @endif

        </div>
    </div>
</div>