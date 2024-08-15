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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
                <div class="bg-base-200 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2">Soil Data</h4>
                    <p id="soil_pH" class="mb-2">pH: {{$soil->pH ?? 'No Data'}}</p>
                    <p id="soil_moisture" class="mb-2">moisture: {{$soil->moisture ?? 'No Data'}}</p>
                    <p id="soil_temperature" class="mb-2">temperature: {{$soil->temperature ?? 'No Data'}}</p>
                    <div class="mt-4 flex justify-end space-x-2">
                    
                    @if(isset($soil))
                    <button class="btn btn-outline btn-primary delete-btn"data-id="{{ $soil->id }}" data-type="soil">Delete</button>
                        <button class="btn btn-outline btn-secondary" 
                                onclick="openModal('Soil', {{ json_encode($soil) }}, 'edit')">
                            Edit
                        </button>
                    @else
                        <button class="btn btn-outline btn-secondary" 
                                onclick="openModal('Soil', null, 'add')">
                            Add
                        </button>
                    @endif
                    </div>

                </div>

                <div class="bg-base-200 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2">Microclimate Data</h4>
                    <p class="mb-2">Temperature: {{$microclimate->temperature ?? 'No Data'}}</p>
                    <p class="mb-2">Humidity: {{$microclimate->humidity ?? 'No Data'}}</p>
                    <p class="mb-2">Light Intensity: {{$microclimate->pressure ?? 'No Data'}}</p>
                    <div class="mt-4 flex justify-end space-x-2">
                    
                    @if(isset($microclimate))
                    <button class="btn btn-outline btn-primary delete-btn" data-id="{{ $microclimate->id }}" data-type="microclimate">Delete</button>
                        <button class="btn btn-outline btn-secondary" 
                                onclick="openModal('Microclimate', {{ json_encode($microclimate) }}, 'edit')">
                            Edit
                        </button>
                    @else
                        <button class="btn btn-outline btn-secondary" 
                                onclick="openModal('Microclimate', null, 'add')">
                            Add
                        </button>
                    @endif
                    </div>


                </div>
                
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="leafs-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Chlorophyll</th>
                            <th>Nitrogen</th>
                            <th>Leaf Moisture</th>
                            <th>Leaf Temperature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
        <!-- Add New Data Modal -->
    <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold" id="modal-title">Add New Data</h3>
            <form id="general-form" method="POST" action="">
                @csrf
                <input type="hidden" name="observation_id" value="{{ $observation->id }}">
                <div class="py-4" id="form-fields">
                    <!-- Dynamic form fields will be inserted here -->
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn" onclick="document.getElementById('my_modal_5').close()">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>

@push('scripts')
<script>
    $(document).ready(function() {
        // Log the leafPhy data to the console
        const leafPhy = @json($leafPhy);
        console.log('Leaf Physiology data:', leafPhy);

        // Initialize the DataTable
        const table = $('#leafs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('leafPhy.data') }}',
                type: 'GET',
                data: {
                    observation_id: '{{ $observation->id }}'
                },
                error: function (xhr, error, thrown) {
                    console.error(xhr.responseText); // log the error for debugging
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'chlorophyll', name: 'chlorophyll' },
                { data: 'nitrogen', name: 'nitrogen' },
                { data: 'leaf_moisture', name: 'leaf_moisture' },
                { data: 'leaf_temperature', name: 'leaf_temperature' },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    }
                }
            ],layout: {
                topStart: {
                    buttons: [
                        {
                            text: 'Add New Data',
                            action: function (e, dt, node, config) {
                                openModal('LeafPhy',null,'add');
                            }
                        }
                    ]
                }
            }
        });
        // Handle form submission
        $('#general-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                // console.log(response.data)
                location.reload();
                table.ajax.reload(); // Reload the DataTable
                document.getElementById('my_modal_5').close(); // Close the modal
                // document.getElementById('soil_pH').innerText = `pH: ${response.data.pH}`;
                // document.getElementById('soil_moisture').innerText = `moisture: ${response.data.moisture}`;
                // document.getElementById('soil_temperature').innerText = `temperature: ${response.data.temperature}`;
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText); // log the error for debugging
            }
            });
        });

        // Handle Edit button click
        $('#leafs-table').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            console.log('Edit button clicked for ID:', id);
            $.ajax({
            url: '{{ route('leafPhy.edit', ':id') }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                openModal('LeafPhy', response.data,'edit');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText); // log the error for debugging
            }
            });
        });

        // Handle Delete button click
        $('#leafs-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const url = '{{ route('leafPhy.destroy', ':id') }}'.replace(':id', id);
            const token = '{{ csrf_token() }}';
            console.log("Delete button clicked for ID:", id);
            if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                _token: token
                },
                success: function(response) {
                console.log('Delete successful for ID:', id);
                table.ajax.reload();
                },
                error: function(xhr) {
                console.error('Delete failed for ID:', xhr);
                alert('Failed to delete the leafPhy. Please try again.');
                }
            });
            }
        });

        // Handle Delete button click for soil and microclimate
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            let url = '';

            if (type === 'soil') {
                url = '{{ route('soil.destroy', ':id') }}'.replace(':id', id);
            } else if (type === 'microclimate') {
                url = '{{ route('microclimate.destroy', ':id') }}'.replace(':id', id);
            }

            const token = '{{ csrf_token() }}';

            if (confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        console.log('Delete successful for ID:', id);
                        // Remove the parent container (you can adjust the selector to target the correct element)
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error('Delete failed for ID:', xhr);
                        alert('Failed to delete the item. Please try again.');
                    }
                });
            }
        });


    function openModal(type, data = null,action = null) {
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('general-form');
        const formFields = document.getElementById('form-fields');

        // Clear existing form fields
        formFields.innerHTML = '';

        // Update modal title and form action based on type
        switch (type) {
            case 'LeafPhy':
                modalTitle.textContent = 'Add New Leaf Physiology';
                form.action = '{{ route('leafPhy.store') }}';
                formFields.innerHTML = `
                    <div class="mb-4">
                        <label for="chlorophyll" class="block text-sm font-medium text-gray-700">Chlorophyll</label>
                        <input type="text" name="chlorophyll" id="chlorophyll" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="nitrogen" class="block text-sm font-medium text-gray-700">Nitrogen</label>
                        <input type="text" name="nitrogen" id="nitrogen" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="leaf_moisture" class="block text-sm font-medium text-gray-700">Leaf Moisture</label>
                        <input type="text" name="leaf_moisture" id="leaf_moisture" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="leaf_temperature" class="block text-sm font-medium text-gray-700">Leaf Temperature</label>
                        <input type="text" name="leaf_temperature" id="leaf_temperature" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                `;
                // Assign data to input text
                if (action == 'edit') {
                    form.innerHTML += '<input type="hidden" name="_method" value="PUT">'; // Spoof PUT method
                    modalTitle.textContent = "Edit Leaf Physiology";
                    document.getElementById('chlorophyll').value = data.chlorophyll;
                    document.getElementById('nitrogen').value = data.nitrogen;
                    document.getElementById('leaf_moisture').value = data.leaf_moisture;
                    document.getElementById('leaf_temperature').value = data.leaf_temperature;
                    form.action = '{{ route('leafPhy.update', ':id') }}'.replace(':id', data.id);
                    
                }

                if (action == 'add') {
                    modalTitle.textContent = "Add New Leaf Physiology";
                    form.action = '{{ route('leafPhy.store'), ':id' }}'.replace(':id', 1);
                }
                
                break;
                
            case 'Soil':
                modalTitle.textContent = 'Add New Soil Data';
                form.action = '{{ route('soil.store') }}';
                formFields.innerHTML = `
                    <div class="mb-4">
                        <label for="pH" class="block text-sm font-medium text-gray-700">pH</label>
                        <input type="text" name="pH" id="pH" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="moisture" class="block text-sm font-medium text-gray-700">Moisture</label>
                        <input type="text" name="moisture" id="moisture" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature</label>
                        <input type="text" name="temperature" id="temperature" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                `;
                if (action == 'edit') {
                    form.innerHTML += '<input type="hidden" name="_method" value="PUT">'; // Spoof PUT method
                    modalTitle.textContent = "Edit Soil";
                    document.getElementById('pH').value = data.pH;
                    document.getElementById('moisture').value = data.moisture;
                    document.getElementById('temperature').value = data.temperature;
                    form.action = '{{ route('soil.update', ':id') }}'.replace(':id', data.id);
                }
                if (action == 'add') {
                    form.innerHTML += '<input type="hidden" name="observation_id" value="{{$observation->id}}">';
                    modalTitle.textContent = "Add New Soil Data";
                    form.action = '{{ route('soil.store')}}';
                }
                break;
            case 'Microclimate':
                modalTitle.textContent = 'Add New Microclimate Data';
                form.action = '{{ route('microclimate.store') }}';
                formFields.innerHTML = `
                    <div class="mb-4">
                        <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature</label>
                        <input type="text" name="temperature" id="temperature" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="humidity" class="block text-sm font-medium text-gray-700">Humidity</label>
                        <input type="text" name="humidity" id="humidity" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="pressure" class="block text-sm font-medium text-gray-700">Pressure</label>
                        <input type="text" name="pressure" id="pressure" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                `;
                if (action == 'edit') {
                    form.innerHTML += '<input type="hidden" name="_method" value="PUT">'; // Spoof PUT method
                    modalTitle.textContent = "Edit Microclimate";
                    document.getElementById('temperature').value = data.temperature;
                    document.getElementById('humidity').value = data.humidity;
                    document.getElementById('pressure').value = data.pressure;
                    form.action = '{{ route('microclimate.update', ':id') }}'.replace(':id', data.id);
                }
                if (action == 'add') {
                    form.innerHTML += '<input type="hidden" name="observation_id" value="{{$observation->id}}">'; 
                    modalTitle.textContent = "Add New Microclimate Data";
                    form.action = '{{ route('microclimate.store')}}';
                }
                break;
                // Herbarium Section
        }

        // Show the modal
        document.getElementById('my_modal_5').showModal();
    }

    window.openModal = openModal;
    });




</script>
@endpush


</x-app-layout>
