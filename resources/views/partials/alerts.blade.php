@if(session('success'))
<div class="alert-modern alert-success animate-in">
    <i class="fas fa-check-circle"></i>
    <div>{{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div class="alert-modern alert-danger animate-in">
    <i class="fas fa-circle-exclamation"></i>
    <div>{{ session('error') }}</div>
</div>
@endif
@if(session('warning'))
<div class="alert-modern alert-warning animate-in">
    <i class="fas fa-triangle-exclamation"></i>
    <div>{{ session('warning') }}</div>
</div>
@endif
@if(session('info') || session('status'))
<div class="alert-modern alert-info animate-in">
    <i class="fas fa-info-circle"></i>
    <div>
        {{ session('info') ?? [
        'profile-updated' => 'Profile updated successfully.',
        'password-updated' => 'Password updated successfully.',
        'verification-link-sent' => 'Verification link has been sent.',
        ][session('status')] ?? session('status') }}
    </div>
</div>
@endif
@if($errors->any())
<div class="alert-modern alert-danger animate-in">
    <i class="fas fa-circle-exclamation"></i>
    <div>
        @if($errors->count() === 1)
        {{ $errors->first() }}
        @else
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endif