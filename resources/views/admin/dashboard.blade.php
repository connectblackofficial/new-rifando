@extends('layouts.admin')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@section('content')
    <style>

        .dashboard-itens {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;

        }

        .dashboard-item {
            position: relative;
            flex: 1 1 0px;
            background-color: #E4E4E4;
            border-radius: 4px;
            cursor: pointer;
        }




        .dashboard-item.profit .dashboard-item-icon{
            background-color: #087e8b
            
        }

        .dashboard-item.request .dashboard-item-icon{
            background-color: #6f9ceb;
            
        }

        .dashboard-item.pending_request .dashboard-item-icon{
            background-color: #ff5666;
            
        }

        .dashboard-item.pending_entry .dashboard-item-icon{
            background-color: #fea82f;
            
        }

        .dashboard-item-body {
            padding: 10px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.5rem;
        }


        .dashboard-item-stats {
            position: relative;
            overflow: hidden;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #212529;
            cursor: auto;
        }

        .dashboard-item-stats i{
            position: absolute;
            height: 70px;
            width: 70px;
            font-size: 22px;
            padding: 15px;
            top: -25px;
            left: -25px;
            background-color: rgba(33, 37, 41, 0.2);
            line-height: 60px;
            text-align: right;
            border-radius: 50%;
            color: #F4F4F4;
        }

        .dashboard-item-divisor {
            width: 75%;
            border: 1px solid #212529;
            opacity: 0.1;
            margin: 1rem;
        }

        

        dashboard-item-info {
            display: flex;
            flex-direction: column;
        }

        .dashboard-item-body p,
        .dashboard-item-stats p {
            margin-bottom: 0;
            color: #707479;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;

        }

        .dashboard-item-value {
            font-size: 1.75rem;
            line-height: 1.75rem;
            font-weight: 500;

        }

        .dashboard-item-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: bold; 
        }

        .dashboard-item-lone-title {
            font-size: 1.5rem;
            text-transform: uppercase;
            font-weight: bold; 
        }

        .dashboard-item-stats > .dashboard-item-title{
            font-size: 1rem;
        }


        .dashboard-item-icon {
            width: 4rem;
            height: 4rem;
            min-height: 4rem;
            min-width: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .dashboard-item-body i {
            line-height: 0;
            font-size: 2rem;
            /* opacity: .2; */
            color: #F4F4F4;
        }

        .dashboard-header {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-btn {
            background-color: #9f1de2;
            box-shadow: 0px 5px 0px 0px #671392;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            margin: 20px;
            color: #f4f4f4;
            position: relative;
            display: inline-block;
        }
        .dashboard-btn:hover {
            background-color: #b450e8;
            color: #f4f4f4;
        }

        .dashboard-btn:active {
            transform: translate(0px, 5px);
            -webkit-transform: translate(0px, 5px);
            box-shadow: 0px 1px 0px 0px;
        }

        .blink {
            
            animation: animate 2.5s linear infinite;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
            }

            .dashboard-itens {
                flex-direction: column;
            }

            .dashboard-item {
                width: 100%;
                background-color: #E4E4E4;
            }
        }

        @keyframes animate {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
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
            
            <div class="dashboard-itens mb-3">
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
