Register New User:
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullname" class="form-control" placeholder="Full Name" value="{{ old('fullname') }}" >
        @error('fullname')
        <p class="text-red-500">
            {{ $message }}
        </p>
        @enderror
    </div>
    <div class="form-group col-lg-2">
        <label class="bold">Enter Username (for login):</label>
        <input type="text" id="username" class="form-control"  placeholder="Enter Username" name="username" value="{{ old('username') }}">
        @error('username')
        <p class="text-red-500">
            {{ $message }}
        </p>
        @enderror
    </div>
    <div class="form-group">
        <label>Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" >
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
        @error('password')
        <p class="text-red-500">
            {{ $message }}
        </p>
        @enderror
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Password">
        @error('password_comfirmation')
        <p class="text-red-500">
            {{ $message }}
        </p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
</form>
