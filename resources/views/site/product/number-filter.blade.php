@foreach($numbers as $number)
    @php
        $ex = explode("-", $number);
            $number = $ex[0];
            $status = 'disponivel';
            if (isset($ex[1])) {
                $status = $ex[1];
                $nome = $ex[2];
            }
    @endphp
    @if ($status == 'disponivel')
        <a href="javascript:void(0);" class="number filter {{ $status }} product-number-free"
           onclick="selectRaffles('{{ $number }}', '{{ $number }}')" id="{{ $number }}">{{ $number }}</a>
    @elseif ($status == 'reservado')
        @php $nome = 'Reservado por ' . $nome; @endphp
        <a href="javascript:void(0);" class="number filter {{ $status }} product-number-reserved"
           onclick="infoParticipante('{{ $nome }}')" style="background-color: rgb(13,202,240);color: #000;"
           id="{{ $number }}">{{ $number }}</a>
    @elseif ($status == 'pago')
        @php $nome = 'Pago por ' . $nome; @endphp
        <a href="javascript:void(0);" class="number filter {{ $status }} product-number-paid"
           onclick="infoParticipante('{{ $nome }}')" style="background-color: #28a745;color: #000;"
           id="{{ $number }}">{{ $number }}</a>
    @endif

@endforeach
