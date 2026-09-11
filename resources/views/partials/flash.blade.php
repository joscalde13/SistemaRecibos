@if(session('status'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
        <ul class="list-disc ps-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
