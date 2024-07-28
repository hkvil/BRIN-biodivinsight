<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Locations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="locations-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Dusun') }}</th>
                            <th>{{ __('Desa') }}</th>
                            <th>{{ __('Kelurahan') }}</th>
                            <th>{{ __('Kecamatan') }}</th>
                            <th>{{ __('Kabupaten') }}</th>
                            <th>{{ __('Altitude') }}</th>
                            <th>{{ __('Longitude') }}</th>
                            <th>{{ __('Latitude') }}</th>
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
            var table = $('#locations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('locations.data') }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'dusun', name: 'dusun' },
                    { data: 'desa', name: 'desa' },
                    { data: 'kelurahan', name: 'kelurahan' },
                    { data: 'kecamatan', name: 'kecamatan' },
                    { data: 'kabupaten', name: 'kabupaten' },
                    { data: 'altitude', name: 'altitude' },
                    { data: 'longitude', name: 'longitude' },
                    { data: 'latitude', name: 'latitude' },
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>