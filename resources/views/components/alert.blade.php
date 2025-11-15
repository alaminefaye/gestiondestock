@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i data-feather="check-circle" class="inline-block mr-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i data-feather="x-circle" class="inline-block mr-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i data-feather="alert-triangle" class="inline-block mr-2"></i>
    <strong>Erreur!</strong> Veuillez corriger les erreurs suivantes:
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
