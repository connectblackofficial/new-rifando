@if (!$product->processado)
    <span class="badge bg-warning">Processando...</span>
@else
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false">
            Ações
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#" style="cursor: pointer" onclick="return productEdit(<?=$product->id ?>) ">
                <i class="bi bi-pencil-square"></i>&nbsp;Editar
            </a>
            <a class="dropdown-item" style="cursor: pointer" data-id="{{ $product->id }}"
               onclick="return deleteProduct({{ $product->id }})">
                <i class="bi bi-trash3"></i>&nbsp;Excluir
            </a>
            <a class="dropdown-item" href="{{ route('rifa.compras', $product->id) }}">
                <i class="fas fa-shopping-bag"></i></i>
                Compras
            </a>
            <a class="dropdown-item" href="{{ route('resumoRifa', $product->id) }}" target="_blank">
                <i class="fas fa-list-ol"></i>&nbsp;Ver Resumo
            </a>
            <a class="dropdown-item" href="#" onclick="copyResumoLink('{{ route('resumoRifa', $product->id) }}')">
                <i class="fas fa-link"></i>&nbsp;Copiar Link Resumo
            </a>
            <a class="dropdown-item" href="javascript:void(0)" title="Ranking"
               onclick="openRanking('{{ $product->id }}')">
                <i class="fas fa-award"></i>&nbsp;Ranking
            </a>
            <a class="dropdown-item" style="color: green" href="javascript:void(0)" title="Ranking"
               onclick="definirGanhador('{{ $product->id }}')">
                <i class="fas fa-check"></i>&nbsp;Definir Ganhador
            </a>
            <a class="dropdown-item" href="javascript:void(0)" title="Ranking"
               onclick="verGanhadores('{{ $product->id }}')">
                <i class="fas fa-users"></i>&nbsp;Visualizar Ganhadores
            </a>
        </div>
    </div>
@endif