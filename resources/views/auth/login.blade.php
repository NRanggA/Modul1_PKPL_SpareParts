@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-4">
    <h3>Login</h3>
    <form method="POST" action="{{ route('login.post') }}">
      @csrf

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required placeholder="contoh@domain.com">
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="login_password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('login_password', this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    const icon = btn.querySelector('i');

    if (field.type === "password") {
        field.type = "text";
        if (icon) { icon.classList.remove("bi-eye"); icon.classList.add("bi-eye-slash"); }
    } else {
        field.type = "password";
        if (icon) { icon.classList.remove("bi-eye-slash"); icon.classList.add("bi-eye"); }
    }
}
</script>
@endpush
