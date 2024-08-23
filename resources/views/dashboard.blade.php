<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class = "pt-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Card 1 -->
            <div class="bg-base-200 p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold mb-1">Total Observations</h4>
                    <p class="text-lg text-gray-600">{{$totalObservations}}</p>
                </div>
                <i class="fas fa-binoculars text-green-500 text-2xl"></i>
            </div>

            <!-- Card 2 -->
            <div class="bg-base-200 p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold mb-1">Total Plants</h4>
                    <p class="text-lg text-gray-600">{{$totalPlants}}</p>
                </div>
                <i class="fas fa-leaf text-blue-500 text-2xl"></i>
            </div>

            <!-- Card 3 -->
            <div class="bg-base-200 p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold mb-1">Total Locations</h4>
                    <p class="text-lg text-gray-600">{{$totalLocations}}</p>
                </div>
                <i class="fas fa-map-marker-alt text-red-500 text-2xl"></i>
            </div>
        </div>
    </div>
</div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="activity-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User_ID</th>
                            <th>Event</th>
                            <th>Model</th>
                            <th>Model_ID</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>DateTime</th>
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
            var table = $('#activity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dashboard.data') }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                },
                columns: [
                    { data: 'ID', name: 'id' },
                    { data: 'User_ID', name: 'user_id' },
                    { data: 'Event', name: 'event' },
                    { data: 'Model', name: 'auditable_type' },
                    { data: 'Model_ID', name: 'auditable_id' },
                    { data: 'Old Values', name: 'old_values' },
                    { data: 'New Values', name: 'new_values' },
                    { data: 'DateTime', name: 'created_at' }
                ],
                scrollX: true,
            
            });
        });



    </script>
    @endpush


</x-app-layout>

