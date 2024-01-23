<span id="numberSelected" class="scrollmenu  qty-numbers-zeros-<?=$qtd_zeros?>">
    @foreach($numbers as $number)
        <div class="number-selected" @isset($hasClickBtn)
            onclick="selectRaffles('{{$number}}', '{{$number}}')"
             @endif
             id="selected-{{$number}}">
            {{$number}}
        </div>
    @endforeach
</span>