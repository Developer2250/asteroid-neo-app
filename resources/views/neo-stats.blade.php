@extends('layouts.main')

@section('title', 'Neo Stats')

@section('content')
	<h1 class="text-2xl font-bold mb-4">Neo Stats</h1>

	<form method="POST" action="/fetch-neo-stats" class="mb-6" id="datePickerForm">
		@csrf
		<div class="container px-4">
			<div class="grid grid-cols-3 gap-4 mb-4">
				<div class="w-full">
					<label for="start_date" class="block font-medium">Start Date</label>
					<input type="date" id="start_date" name="start_date" class="w-full p-2 border rounded"
						value="{{ old('start_date', '') }}">
					@if ($errors->has('start_date'))
						<p class="text-red-500 text-sm">{{ $errors->first('start_date') }}</p>
					@endif
				</div>

				<div class="w-full">
					<label for="end_date" class="block font-medium">End Date</label>
					<input type="date" id="end_date" name="end_date" class="w-full p-2 border rounded"
						value="{{ old('end_date', '') }}">
					@if ($errors->has('end_date'))
						<p class="text-red-500 text-sm">{{ $errors->first('end_date') }}</p>
					@endif
				</div>

				<div class="w-full flex items-end gap-4">
					<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Submit</button>
					<button type="button" class="bg-red-500 text-white px-4 py-2 rounded w-full"
						onclick="clearFilters()">Clear</button>
				</div>
			</div>
		</div>
	</form>

	@isset($fastestAsteroid)
		<div class="grid grid-cols-3 gap-4 mb-4">
			<!-- Fastest Asteroid -->
			<div class="p-4 border rounded shadow">
				<h2 class="text-lg font-semibold">Fastest Asteroid</h2>
				<p>ID: {{ $fastestAsteroid['id'] }}</p>
				<p>Speed: {{ $fastestAsteroid['speed'] }} km/h</p>
			</div>

			<!-- Closest Asteroid -->
			<div class="p-4 border rounded shadow">
				<h2 class="text-lg font-semibold">Closest Asteroid</h2>
				<p>ID: {{ $closestAsteroid['id'] }}</p>
				<p>Distance: {{ $closestAsteroid['distance'] }} km</p>
			</div>

			<!-- Average Size -->
			<div class="p-4 border rounded shadow">
				<h2 class="text-lg font-semibold">Average Size</h2>
				<p>{{ $averageSize }} km</p>
			</div>
		</div>

		<!-- Chart -->
		<canvas id="dailyAsteroidsChart" width="400" height="200"></canvas>
	@endisset

	<script>
		function clearFilters() {
			document.getElementById('start_date').value = '';
			document.getElementById('end_date').value = '';
			window.location.href = '/';
		}

		@if (isset($dailyCounts))
			const ctx = document.getElementById('dailyAsteroidsChart').getContext('2d');
			new Chart(ctx, {
				type: 'line',
				data: {
					labels: {!! json_encode(array_keys($dailyCounts)) !!},
					datasets: [{
						label: 'Number of Asteroids',
						data: {!! json_encode(array_values($dailyCounts)) !!},
						backgroundColor: 'rgba(54, 162, 235, 0.5)',
						borderColor: 'rgba(54, 162, 235, 1)',
						borderWidth: 2,
						fill: false,
						tension: 0.1,
					}]
				},
				options: {
					responsive: true,
					plugins: {
						legend: {
							display: true,
							position: 'top',
						}
					},
					scales: {
						x: {
							title: {
								display: true,
								text: 'Date'
							}
						},
						y: {
							title: {
								display: true,
								text: 'Number of Asteroids'
							},
							beginAtZero: true
						}
					}
				}
			});
		@endif
	</script>
@endsection
