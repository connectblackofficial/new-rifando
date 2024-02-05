@extends('layouts.admin')



    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@section('content')
    <style>

       
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="dashboard-header">
                <div class="col-sm-6">
                    <h1>Home</h1>
                    <h6>Clique no card para mais informações!</h6>
                </div>
                <a href="{{ route('rifaPremiada') }}" class="dashboard-btn">COTA PREMIADA</a>
            </div>
        </div><!-- /.container-fluid -->
    </section>



    <section class="content">
        <div class="container-fluid">
            
            <div class="dashboard-itens info">
                <div class="dashboard-item profit block-copy">
                    <div class="dashboard-item-body">
                        <div class="dashboard-item-icon"><i class="fas fa-people-arrows blink"></i></div>
                        <div class="dashboard-item-info">
                            <p class="dashboard-item-value">0</p>
                            <p class="dashboard-item-title">Afiliados</p>
                        </div>
                        
                    </div>
                </div>

                <div class="dashboard-item request block-copy" onclick="link('{{ route('resumo.rifasAtivas') }}')">
                    <div class="dashboard-item-body">
                        <div class="dashboard-item-icon"><i class="fa-solid fa-receipt blink"></i></div>
                        <div class="dashboard-item-info">
                            <p class="dashboard-item-value">{{ $rifas->count() }}</p>
                            <p class="dashboard-item-title">Rifas Ativas</p>
                        </div>
                        
                        
                    </div>
                </div>

                <div class="dashboard-item pending_request block-copy" onclick="link('{{ route('resumo.pendentes') }}')">
                    <div class="dashboard-item-body">
                        <div class="dashboard-item-icon"><i class="fa-solid fa-hourglass blink"></i></div>
                        <div class="dahsboard-item-info">
                            <p class="dashboard-item-value">{{ $participantes->where('reservados', '>', 0)->count() }}</p>
                            <p class="dashboard-item-title">Pedidos Pendentes</p>
                        </div>
                        
                    </div>
                </div>

                <div class="dashboard-item pending_entry block-copy" onclick="link('{{ route('resumo.ranking') }}')">
                    <div class="dashboard-item-body">
                        <div class="dashboard-item-icon"><i class="fas fa-medal blink"></i></div>
                        <div class="dashboard-item-info">
                            {{-- <p>R$ {{ number_format($participantes->where('reservados', '>', 0)->sum('valor'), 2, ",", ".") }}</p> --}}
                            <p class="dashboard-item-lone-title">Ranking</p>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
            <div class="dashboard-itens stats">
                
                <div class="dashboard-item block-copy">
                    <div class="dashboard-item-stats">
                        <i class="fa-solid fa-eye"></i>
                        <p class="dashboard-item-value">5,154</p>
                        <div class="dashboard-item-divisor"></div>
                        <p class="dashboard-item-title">Vizualizações nas rifas</p>
                      </div>
                </div>
                <div class="dashboard-item block-copy">
                    <div class="dashboard-item-stats">
                        <i class="fa-solid fa-right-left rotate"></i>
                        <p class="dashboard-item-value">245</p>
                        <div class="dashboard-item-divisor"></div>
                        <p class="dashboard-item-title">Transações</p>
                    </div>
                </div>
                <div class="dashboard-item block-copy">
                    <div class="dashboard-item-stats">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <p class="dashboard-item-value">5,154</p>
                        <div class="dashboard-item-divisor"></div>
                        <p class="dashboard-item-title">Rifas vendidas</p>
                    </div>
                  </div>
                  


          
        

                
            </div>
        </div>
        </div>
    @endsection

    <script>
        function link(url) {
            window.location.href = url;
        }
    </script>
