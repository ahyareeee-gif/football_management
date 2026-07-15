@if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 text-sm text-red-700">
        <div class="font-medium">Ada data yang perlu diperbaiki.</div>
        <ul class="mt-2 list-disc space-y-1 ps-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
