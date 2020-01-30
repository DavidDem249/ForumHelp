@extends('layouts.app')

@section('content')
	
	<div class="container">
		
		<h1>{{ $topic->title }}</h1>

		<hr>

		<form action="{{ route('topics.update', $topic) }}" method="POST">
			@csrf

			@method('PUT') {{--  ou PATCH --}}

			<div class="form-group">
				<label for='title'>Titre du topic</label>
				<input type="text" class="form-control @error('title') is-invalid @enderror" id='title' value="{{ $topic->title }}" name="title">

				@error('title')
					<div class="invalid-feedback">
						{{ $errors->first('title') }}
					</div>
				@enderror
			</div>

			<div class="form-group">
				<label for='content'>Votre topic</label>
				<textarea class="form-control @error('content') is-invalid @enderror" id='content' name="content" rows="8"> {{ $topic->content }} </textarea>

				@error('content')
					<div class="invalid-feedback">
						{{ $errors->first('content') }}
					</div>
				@enderror

			</div>
			
			<button type="submit" class="btn btn-primary"> Modifier </button>
		</form>
	</div>

@stop