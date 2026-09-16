@if(session('success'))
<div class="alert alert-success">
    <span>✓</span>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <span>✗</span>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert alert-error">
    <span>✗</span>
    <div>
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
</div>
@endif