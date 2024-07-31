<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Observation Detail
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                    Observation ID: {{$observation->id}}
                </h3>
                <p class="mb-4">
                    Location: {{$observation->location->kabupaten}}
                </p>
                <p class="mb-4">
                    Time and Date: {{$observation->observation_time}}, {{$observation->observation_date}}
                </p>
                <p class="mb-4">
                    Plants Name: {{$observation->plant->species_name}}, {{$observation->plant->common_name}}
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-gray-200 p-4">Box Tanah</div>
                <div class="bg-gray-200 p-4">Box Microclimate</div>
                <div class="bg-gray-200 p-4">Box Herbarium</div>
            </div>
        </div>
    </div>

    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="observations-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Plant Name</th>
                            <th>Location Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        Data will be populated by DataTables
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

</x-app-layout>
