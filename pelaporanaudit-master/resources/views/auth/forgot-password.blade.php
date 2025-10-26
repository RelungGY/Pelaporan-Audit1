@extends('admin.layouts.app')

@section('content')
<style>
    <style>
    body {
        background-color: #5DB5E6;
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
    }
    .header {
        background-color: #5DB5E6; 
        color: white;
        padding: 20px 0;
        width: 100%; 
        text-align: center;
        margin: 0; 
    }
    .container {
        margin-top: 50px; 
        margin-bottom: 50px; 
        text-align: center;
        justify-content: center;
        align-items: center;
        
    }
    .form-control {
        width: 300px; 
        height: 40px; 
        margin-top: 15px;
        margin-bottom: 15px; 
        text-align: center;
        align-items: center;
        border-color: #5DB5E6;
        margin-left: 200px;
    }
    .btn-primary {
        width: 200px; 
        height: 35px; 
        margin-left: 20px;
        background-color: #5DB5E6;
        border-color: #5DB5E6;
    }
    .btn-primary:hover {
        background-color: #4aa3d4;
        border-color: #4aa3d4;
    }
</style>

</style>
<div class="header">
    <h1>sewa.in</h1>
</div>
<div class="container">
    <h2>Lupa Password</h2>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Link Reset Password</button>
    </form>
</div>
@endsection
