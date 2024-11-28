@extends('default')

@section('content')

@include('prob-notice')

	@if($errors->any())
		<div class="text-base mb-3 p-4 w-full bg-red-200 text-red-800">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	<div class="p-4">

		{{-- Toast --}}            
		@if(session('toast'))
		  @php
			  $toast = session('toast');
			  $bgColor = $toast['success'] == 1 ? 'bg-green-500' : 'bg-red-500';
		  @endphp
		  <div id="toast-default" class="flex items-center top-5 right-5 fixed w-full max-w-xs p-4 {{ $bgColor }} rounded-lg shadow" role="alert">
			  <div class="text-sm font-normal text-gray-100">{{ $toast['message'] }}</div>
			  <button type="button" 
					  class="ms-auto -mx-1.5 -my-1.5 bg-transparent text-gray-100 hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-600 inline-flex items-center justify-center h-8 w-8" 
					  onclick="document.getElementById('toast-default').remove()"
					  aria-label="Close">
				  <span class="sr-only">Close</span>
				  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
					  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
				  </svg>
			  </button>
		  </div>             
	  	@endif
		
		<div class="flex justify-between items-center mb-4">
			<p class="text-2xl mb-3 font-bold">
				{{ isset($prize) ? 'Edit Prize' : 'Add Prize' }}
			</p>
			<a href="{{ route('prizes.index') }}">
				<button type="submit" id='submitBtn' class="text-white flex bg-gradient-to-r from-gray-400 via-gray-500 to-gray-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-700 shadow-lg font-medium rounded-lg text-sm px-5 py-2 text-center">
					Back
				</button>  
			</a>  
		</div>
		
		<form action="{{ route('prizes.store') }}" class="col-span-1 p-4 border border-1 rounded-lg shadow-2" method="POST">
			@csrf

			<input type="hidden" id="id" name="id" value="{{ isset($prize) ? $prize->id : '' }}"/>

			<div class="grid md:grid-cols-2 mt-4 gap-2 ">
				<div class="mb-2">
					<label for="title" class="block mb-1 text-sm font-medium text-gray-900">Title</label>
					<input 
						type="text" 
						id="title" 
						name='title' 
						class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
						placeholder="Enter Prize Title" 
						required 
						value="{{ isset($prize) ? $prize['title'] : old('title') }}"
					/>
					@error('title')
						<span class="text-red-500 text-sm">{{$message}}</span>
					@enderror
				</div>

				<div class="">
					<label for="probability" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-800 ">Probability</label>
					<input 
						type="number" 
						id="probability" 
						name='probability' 
						step="0.01" 
						data-input-counter 	data-input-counter-min="1" data-input-counter-max="100" 
						aria-describedby="helper-text-explanation" 
						class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " 
						placeholder="0-100" 
						required 
						value="{{ isset($prize) ? $prize['probability'] : old('probability') }}"					
					/>
					@error('probability')
						<span class="text-red-500 text-sm">{{$message}}</span>
					@enderror
				</div>					
			</div>

			<div class="Buttons mt-4">		                    
					                                                                         
				<button type="submit" id='submitBtn' class="text-white flex bg-gradient-to-r from-green-400 via-green-500 to-green-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-700 shadow-lg font-medium rounded-lg text-sm px-5 py-2 text-center">
					{{ isset($prize) ? 'Update' : 'Create' }}
				</button>                         
			
			</div> 

		</form>
	</div>


@stop
