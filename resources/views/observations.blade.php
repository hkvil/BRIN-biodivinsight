<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Observation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="observations-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Plant Name') }}</th>
                            <th>{{ __('Location Name') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    @push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#observations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('observations.data') }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'plant_name', name: 'plant_name' },
                    { data: 'location_name', name: 'location_name' },
                    { data: 'observation_date', name: 'observation_date' },
                    { data: 'observation_time', name: 'observation_time' }
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
