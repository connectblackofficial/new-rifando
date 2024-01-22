
    <div class="row justify-content-center">
        <div class="col-md-12 col-9" style="background-color: #fff; color: #000; border-radius: 10px;">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center" style="width: 100%">
                        <span id="numberSelected" class="scrollmenu">
                          @foreach($numbers as $number)
                                <div class="number-selected" id="selected-{{$number}}">{{$number}}</div>
                            @endforeach
                        </span>
                    </div>
                </div>
                <div class="row"
                     style="text-align: center;background-color: #fff; margin-top: 5px; justify-content-center; margin-bottom: 10px;">
                    <div class="col-12 d-flex justify-content-center">
                        <center style="width: 400px;">
                            <button type="button" class="btn btn-danger reservation blob"
                                    style="border: none;color: #fff;font-weight: bold;width: 100%;background-color: green"
                                    onclick="openModalCheckout()"><i
                                        class="far fa-check-circle"></i>&nbsp;Participar do
                                sorteio
                                <span style="font-size: 14px; float:right">
                                    <span id="numberSelectedTotal">
<?=$formated_total?>
                                    </span>
                                </span>
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
