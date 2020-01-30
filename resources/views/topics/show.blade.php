@extends('layouts.app')

@section('extra-css')
	
	<style type="text/css">
		.bordure{
			border: 1px solid green;
		}
	</style>
	
@endsection

@section('axtra-js')
	
	<script>
		function toggleReplyComment(id){
			let element = document.getElementById('replyComment-' + id);
			element.classList.toggle('d-none');
		}
	</script>

@stop

@section('content')
	
	<div class="container">
		
		<div class="card">
			<div class="card-body">
				<h3 class="card-title" style="font-weight: bold;">{{ $topic->title }}</h3>
				<p>{{ $topic->content }}</p>

				<div class="d-flex justify-content-between align-items-center">
					<small>
						Poster le {{ $topic->created_at->format('d/m/Y à H:m') }}
					</small>

					<span class="badge badge-primary">
						{{ $topic->user->name }}
					</span>
				</div>

				<div class="d-flex justify-content-between align-items-center mt-3">
					@can('update', $topic)
						<a href="{{ route('topics.edit', $topic) }}" class="btn btn-warning"> Editer ce topic </a>
					@endcan

					@can('delete', $topic)
						<form action="{{ route('topics.destroy', $topic) }}" method="POST">
							
							@csrf
							@method('DELETE')

							<button type="submit" class="btn btn-danger">Supprimer</button>

						</form>
					@endcan
				</div>

			</div>
		</div>

		<hr>
		<h4>Commentaires</h4>

		@forelse($topic->comments as $comment)
			<div class="card mb-2">
				<div class="card-body d-flex justify-content-between {{ $topic->solution == $comment->id ? 'bordure' : '' }} ">
					<div>
						{{ $comment->content }}

						<div class="d-flex justify-content-between align-items-center">
							<small>
								Poster le {{ $comment->created_at->format('d/m/Y à H:m') }}
							</small>
							&nbsp;&nbsp; 
							<span class="badge badge-primary">
								{{ $comment->user->name }}
							</span>
						</div>
					</div>

					<div>
						@if(!$topic->solution && auth()->user()->id == $topic->user_id)

							<solution-button topic-id ="{{ $topic->id }}" comment-id="{{ $comment->id }}"></solution-button>
						@else
							@if($topic->solution == $comment->id)
								<h4>
									<span class="badge badge-success">Déja marqué comme solution</span>
								</h4>
							@endif
						@endif
					</div>

				</div>
			</div>

			@foreach($comment->comments as $replyComment)
				<div class="card mb-2 ml-5">
					<div class="card-body">
						{{ $replyComment->content }}

						<div class="d-flex justify-content-between align-items-center">
							<small>
								Poster le {{ $replyComment->created_at->format('d/m/Y à H:m') }}
							</small>

							<span class="badge badge-primary">
								{{ $replyComment->user->name }}
							</span>
						</div>

					</div>
				</div>
			@endforeach
			@auth
				<button class="btn btn-info mb-3" onclick="toggleReplyComment({{ $comment->id }})">
					Répondre
				</button>
				<form action="{{ route('comments.storeReply', $comment) }}" method="POST" class="mb-3 ml-5 d-none" id='replyComment-{{ $comment->id }}'>
					@csrf
					<div class="form-group">
						<label for='replyComment'>Ma réponse</label>
						<textarea class="form-control @error('replyComment') is-invalid @enderror" name="replyComment" id='replyComment' rows="3"></textarea>

						@error('replyComment')
							<div class="invalid-feedback">
								{{ $errors->first('replyComment') }}
							</div>
						@enderror
					</div>
					<button class="btn btn-primary" type="submit">Répondre à ce commentaire</button>
				</form>
			@endauth
		@empty
			<div class="alert alert-info">Aucun commentaire pour ce topic</div>

		@endforelse

		<form method="POST" action="{{ route('comments.store',$topic) }}" class="mt-3">

			@csrf

			<div class="form-group">
				<label for='content'>Votre commentaire</label>
				<textarea class="form-control @error('content') is-invalid @enderror" id='content' rows="5" name="content">
					
				</textarea>

				@error('content')

					<div class="invalid-feedback">
						{{ $errors->first('content') }}
					</div>

				@enderror

				<button type="submit" class="btn btn-primary mt-2">Soumettre mon commentaire</button>
			</div>
		</form>
	</div>

@stop