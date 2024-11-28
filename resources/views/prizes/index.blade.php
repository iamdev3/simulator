@extends('default')

@section('content')

    @include('prob-notice')

    <div class="">

        <div class="p-4">

            {{--  header  --}}
            <div class="card-header flex justify-between items-center mb-4">
                <h3 class="text-3xl font-bold">
                    Prizes
                </h3>                 
                <div class="text-center">
                    <a href="{{route('prizes.create')}}">
                        <button                             
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 " 
                            type="button" 
                        >
                            Add Prices
                        </button>
                    </a>
                </div>
            </div>

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
        
            {{--  table  --}}
            <div class="">
           
                <div class="relative overflow-x-auto shadow-md rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-300">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Id
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Probability
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Awarded
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prizes as $prize)                  
                            
                                <tr class="bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium dark:bg-gray-800">
                                        {{ $prize->id }}
                                    </th>
                                    <td class="py-4 px-4">
                                        {{ $prize->title }}
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $prize->probability }}
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $prize->awarded }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex gap-1">

                                            <a href="{{ route('prizes.edit', [$prize->id]) }}">
                                                <button type="button" class="editBtn  text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-600 shadow-lg dark:shadow-lg dark:shadow-green-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex"
                                                >
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                                    </svg>                                               
                                                    
                                                    <span class="sr-only">Icon description</span>
                                                    Edit
                                                </button>
                                            </a>
                
                                            <form id="deleteForm" action="{{route('prizes.destroy', $prize->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')                                    
                                                <button type="submit" id='deleteBtn' class="text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700 shadow-lg dark:shadow-lg dark:shadow-red-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center">
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                    </svg>     
                                                    Delete
                                                </button>
                                            </form>   
                                        
                                        </div>    
                                    </td>
                                </tr>
            
                            @endforeach
                
                        </tbody>
                    </table>
                </div>
             
            </div>

        </div>

        <hr>

        {{--  simulation  --}}
        <div class="grid md:grid-cols-3 mt-4 ">

            <div></div>

            <form action="{{ route('simulate') }}" class="col-span-1 p-4 border border-1 rounded-lg shadow-2" method="POST">
                @csrf

                <p class="text-2xl mb-3 font-bold">
                    Simulate
                </p>
                <div class="">
                    <label for="number-input" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-800 ">Number of Prizes/Participants</label>
                    <input type="number" id="number_of_prizes" name='number_of_prizes' aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " placeholder="Enter the no of Prizes" required />
                    @error('number_of_prizes')
						<span class="text-red-500 text-sm">{{$message}}</span>
					@enderror
                </div>

                <div class="Buttons">

                    <div class="flex justify-left items-center mt-3 gap-1">
                        <div>                              
                            <a href="{{route('reset')}}" id='resetBtn' class="text-white flex bg-gradient-to-r from-gray-400 via-gray-500 to-gray-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-700 shadow-lg dark:shadow-lg font-medium rounded-lg text-sm px-5 py-2 text-center">
                                Reset
                            </a>
                        </div>                                                                           
                            <button type="submit" id='simulateBtn' class="text-white flex bg-gradient-to-r from-green-400 via-green-500 to-green-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-700 shadow-lg dark:shadow-lg  font-medium rounded-lg text-sm px-5 py-2 text-center">
                                Simulate 
                            </button>                      
                        <div>                    
                    </div>

                </div> 
            
            </form>
            
        </div>

    </div>

    {{--  results  --}}
    <div class="grid md:grid-cols-2 mb-4 mt-6 p-6 gap-4 ">
        
        {{-- probability set chart --}}
        <div class="">
            <h2 class="text-center text-2xl">Probability Settings</h2>         
            <div class="mt-6 dark:bg-gray-100 rounded-lg shadow-2">
                <div id="chart" style="max-width: 600px; margin: auto;"></div>
            </div>
        </div>

        {{-- simulation resultant chart --}}
        <div class="">
            <h2 class="text-center text-2xl">Actual Rewards</h2>
            <div class="mt-6  dark:bg-gray-100 rounded-lg shadow-2">
                <div id="resultChart" style="max-width: 600px; margin: auto;"></div>
            </div>            
        </div>
       
    </div>

@stop


@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
    
        @if(isset($graphProb) && count($graphProb) > 0)
            const graphProb = @json($graphProb);
            const probTitles = Object.keys(graphProb);
            const probValues = probTitles.map(title => parseFloat(graphProb[title]));
            console.log(graphProb);
            var options = {
                series: probValues,
                chart: {
                width: 600,
                type: 'donut',
              },
              labels: probTitles,
              plotOptions: {
                pie: {
                  startAngle: -90,
                  endAngle: 270
                }
              },
              dataLabels: {
                enabled: true      
              },
              fill: {
                type: 'gradient',
              },
              legend: {
                formatter: function(val, opts) {
                  return val + " - " + opts.w.globals.series[opts.seriesIndex]
                }
              },
              title: {
                text: "Prize Probability Weightage (%)"
              },
              responsive: [{
                breakpoint: 480,
                options: {
                  chart: {
                    width: 200
                  },
                  legend: {
                    position: 'bottom'
                  }
                }
              }]
            };
      

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        @endif 

        @if(isset($resultsPercentage))
            const results = @json($resultsPercentage);
            var options = {
            series: Object.values(results),
            chart: {
            width: 600,
            type: 'donut',
          },
          labels:Object.keys(results),
          plotOptions: {
            pie: {
              startAngle: -90,
              endAngle: 270
            }
          },
          dataLabels: {
            enabled: true
          },
          fill: {
            type: 'gradient',
          },
          legend: {
            formatter: function(val, opts) {
              return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
          },
          title: {
            text: "Result Prize Distribution Weightage (%)"
          },
          responsive: [{
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: 'bottom'
              }
            }
          }]
          };
  
          var chart = new ApexCharts(document.querySelector("#resultChart"), options);
          chart.render();
        @endif

    </script>

@endpush
