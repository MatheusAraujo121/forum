@extends('layouts.liop')

@section('title', 'Cadastro')

@section('FormTitle', 'Cadastre-se')

@section('content')
<form class="formd" action="{{route('register')}}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="input-group">
    <label for="name">Nome</label>
    <input type="name" id="name" name="name" value="{{old('name')}}" required>
    @error('name') <span>{{ $message }}</span> @enderror
  </div>
  <div class="input-group">
    <label for="Email">Email</label>
    <input type="Email" id="email" name="email" value="{{old('email')}}" required>
    @error('email') <span>{{ $message }}</span> @enderror
  </div>
  <div class="input-group">
    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required>
    @error('password') <span>{{ $message }}</span> @enderror
  </div>
  <div class="input-group">
    <label for="password_confirmation">Confirmar senha</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>
    <div class="forgot">
      <a rel="noopener noreferrer" href="#"></a>
    </div>
  </div>
  <div class="input-group">
    <label for="image" class="form-label">Foto</label>
    <div class="image-post" id="imagePreview" style="display: none;">
      <img id="profileImg" src="" alt="profileImg">
    </div>
    <br>
    <input type="file" name="photo" accept="image/*" id="photoUpload">
    @error('photo') <span>{{ $message }}</span> @enderror
  </div>
  <br>
  <button type="submit" value="Registrar" class="signs">Cadastrar</button>
</form>
<br>
<p class="signup">Você já tem uma conta?
  <a rel="noopener noreferrer" href="{{ url('/login') }}" class="">Faça login</a>
</p>
@endsection