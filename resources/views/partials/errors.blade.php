@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Periksa kembali data Anda:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
