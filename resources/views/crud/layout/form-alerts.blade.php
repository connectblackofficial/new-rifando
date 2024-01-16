
@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@if (session()->has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{{ session('success') }}</li>
        </ul>
    </div>
@endif
@if (session()->has('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{{ session('error') }}</li>
        </ul>
    </div>
@endif