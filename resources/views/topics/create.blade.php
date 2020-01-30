@extends('layouts.app')


@section('axtra-js')
	 {!! NoCaptcha::renderJs() !!}
@stop

@section('content')
	
	<div class="container">
		
		<h1>Creer un topic</h1>

		<hr>

		<form action="{{ route('topics.store') }}" method="POST">
			@csrf

			<div class="form-group">
				<label for='title'>Titre du topic</label>
				<input type="text" class="form-control @error('title') is-invalid @enderror" id='title' name="title">

				@error('title')
					<div class="invalid-feedback">
						{{ $errors->first('title') }}
					</div>
				@enderror
			</div>

			<div class="form-group">
				<label for='content'>Votre topic</label>
				<textarea class="form-control @error('content') is-invalid @enderror" id='content' name="content" rows="8"></textarea>

				@error('content')
					<div class="invalid-feedback">
						{{ $errors->first('content') }}
					</div>
				@enderror

			</div>

			<div class="form-group">
				{!! NoCaptcha::display() !!}
			</div>
			
			<button type="submit" class="btn btn-primary"> Créer un topic</button>
		</form>
	</div>

@stop