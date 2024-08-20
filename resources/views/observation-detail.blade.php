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

    @if($observation->observation_type == 'Field Observation')
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
    @endif

    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="general-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            @if($observation->observation_type == 'Field Observation')
                            <th>ID</th>
                            <th>Chlorophyll</th>
                            <th>Nitrogen</th>
                            <th>Leaf Moisture</th>
                            <th>Leaf Temperature</th>
                            @elseif($observation->observation_type == 'Lab Observation')
                            <th>No</th>
                            <th>Kode</th>
                            <th>Minggu Panen</th>
                            <th>Perlakuan Penyiraman</th>
                            <th>Tinggi Tanaman</th>
                            <th>Mean Tinggi Tanaman</th>
                            <th>Stddev Tinggi Tanaman</th>
                            <th>Panjang Akar</th>
                            <th>Mean Panjang Akar</th>
                            <th>Stddev Panjang Akar</th>
                            <th>BB Tunas</th>
                            <th>Mean BB Tunas</th>
                            <th>Stddev BB Tunas</th>
                            <th>BK Tunas</th>
                            <th>Mean BK Tunas</th>
                            <th>Stddev BK Tunas</th>
                            <th>BB Akar</th>
                            <th>Mean BB Akar</th>
                            <th>Stddev BB Akar</th>
                            <th>BK Akar</th>
                            <th>Mean BK Akar</th>
                            <th>Stddev BK Akar</th>
                            @endif
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
            let table;
            function initializeDataTable(tableId, ajaxUrl, columns){
            table = $(tableId).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    type: 'GET',
                    data: {
                        observation_id: '{{ $observation->id }}'
                    },
                    error: function (xhr, error, thrown) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                },
                columns: columns,
                layout: {
                    topStart: {
                        buttons: [
                            {
                                text: 'Add New Data',
                                action: function (e, dt, node, config) {
                                    // openModal('LeafPhy',null,'add');
                                    if ('{{ $observation->observation_type }}' == 'Field Observation') {
                                        openModal('LeafPhy',null,'add');
                                    } else if ('{{ $observation->observation_type }}' == 'Lab Observation') {
                                        openModal('GH',null,'add');}
                                }
                            }
                        ]
                    }
                },
                scrollX: true
            });
        }
        
    $(document).ready(function() {
        // Initialize the DataTable
        const leafsColumns = [
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
        ];
        const greenHouseColumns = [
            { data: 'no', name: 'no' },
            { data: 'kode', name: 'kode' },
            { data: 'minggu_panen', name: 'minggu_panen' },
            { data: 'perlakuan_penyiraman', name: 'perlakuan_penyiraman' },
            { data: 'tinggi_tanaman', name: 'tinggi_tanaman' },
            { data: 'mean_tinggi_tanaman', name: 'mean_tinggi_tanaman' },
            { data: 'stddev_tinggi_tanaman', name: 'stddev_tinggi_tanaman' },
            { data: 'panjang_akar', name: 'panjang_akar' },
            { data: 'mean_panjang_akar', name: 'mean_panjang_akar' },
            { data: 'stddev_panjang_akar', name: 'stddev_panjang_akar' },
            { data: 'bb_tunas', name: 'bb_tunas' },
            { data: 'mean_bb_tunas', name: 'mean_bb_tunas' },
            { data: 'stddev_bb_tunas', name: 'stddev_bb_tunas' },
            { data: 'bk_tunas', name: 'bk_tunas' },
            { data: 'mean_bk_tunas', name: 'mean_bk_tunas' },
            { data: 'stddev_bk_tunas', name: 'stddev_bk_tunas' },
            { data: 'bb_akar', name: 'bb_akar' },
            { data: 'mean_bb_akar', name: 'mean_bb_akar' },
            { data: 'stddev_bb_akar', name: 'stddev_bb_akar' },
            { data: 'bk_akar', name: 'bk_akar' },
            { data: 'mean_bk_akar', name: 'mean_bk_akar' },
            { data: 'stddev_bk_akar', name: 'stddev_bk_akar' },
            
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
        ];

        if ('{{ $observation->observation_type }}' == 'Field Observation') {
            initializeDataTable('#general-table', '{{ route('leafPhy.data') }}', leafsColumns);
        } else if ('{{ $observation->observation_type }}' == 'Lab Observation') {
            initializeDataTable('#general-table', '{{ route('gh.data') }}', greenHouseColumns);
        }
        
        
        // Handle form submission
        $('#general-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                location.reload();
                table.ajax.reload(); 
                document.getElementById('my_modal_5').close();
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
            });
        });

        // Handle Edit button click
        $('#general-table').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            let value;
            let url;
            if ('{{ $observation->observation_type }}' == 'Field Observation') {
                value = 'LeafPhy';
                url = '{{ route('leafPhy.edit', ':id') }}'.replace(':id', id);
            } else if ('{{ $observation->observation_type }}' == 'Lab Observation') {
                value = 'GH';
                url = '{{ route('gh.edit', ':id') }}'.replace(':id', id);}            
            console.log('Edit button clicked for ID:', id);
            $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                openModal(value, response.data,'edit');
            }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
            });
        });

        // Handle Delete button click
        $('#general-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const token = '{{ csrf_token() }}';
            let url;
            if ('{{ $observation->observation_type }}' == 'Field Observation') {
                url = '{{ route('leafPhy.destroy', ':id') }}'.replace(':id', id);
            } else if ('{{ $observation->observation_type }}' == 'Lab Observation') {
                url = '{{ route('gh.destroy', ':id') }}'.replace(':id', id);}
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
                alert('Failed to delete. Please try again.');
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
                case 'GH':
                    modalTitle.textContent = 'Add New Green House Data';
                    form.action = '{{ route('gh.store') }}';
                    formFields.innerHTML = `
                        <div class="mb-4">
                            <label for="no" class="block text-sm font-medium text-gray-700">No</label>
                            <input type="number" name="no" id="no" class="mt-1 block w-full" autocomplete="off" required min="1">
                        </div>
                        <div class="mb-4">
                            <label for="kode" class="block text-sm font-medium text-gray-700">Kode</label>
                            <input type="text" name="kode" id="kode" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="minggu_panen" class="block text-sm font-medium text-gray-700">Minggu Panen</label>
                            <input type="number" name="minggu_panen" id="minggu_panen" class="mt-1 block w-full" autocomplete="off" required min=1>
                        </div>
                        <div class="mb-4">
                            <label for="perlakuan_penyiraman" class="block text-sm font-medium text-gray-700">Perlakuan Penyiraman</label>
                            <input type="text" name="perlakuan_penyiraman" id="perlakuan_penyiraman" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="tinggi_tanaman" class="block text-sm font-medium text-gray-700">Tinggi Tanaman</label>
                            <input type="text" name="tinggi_tanaman" id="tinggi_tanaman" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_tinggi_tanaman" class="block text-sm font-medium text-gray-700">Mean Tinggi Tanaman</label>
                            <input type="text" name="mean_tinggi_tanaman" id="mean_tinggi_tanaman" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_tinggi_tanaman" class="block text-sm font-medium text-gray-700">Stddev Tinggi Tanaman</label>
                            <input type="text" name="stddev_tinggi_tanaman" id="stddev_tinggi_tanaman" class="mt-1 block w-full" autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label for="panjang_akar" class="block text-sm font-medium text-gray-700">Panjang Akar</label>
                            <input type="text" name="panjang_akar" id="panjang_akar" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_panjang_akar" class="block text-sm font-medium text-gray-700">Mean Panjang Akar</label>
                            <input type="text" name="mean_panjang_akar" id="mean_panjang_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_panjang_akar" class="block text-sm font-medium text-gray-700">Stddev Panjang Akar</label>
                            <input type="text" name="stddev_panjang_akar" id="stddev_panjang_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="bb_tunas" class="block text-sm font-medium text-gray-700">BB Tunas</label>
                            <input type="text" name="bb_tunas" id="bb_tunas" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_bb_tunas" class="block text-sm font-medium text-gray-700">Mean BB Tunas</label>
                            <input type="text" name="mean_bb_tunas" id="mean_bb_tunas" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_bb_tunas" class="block text-sm font-medium text-gray-700">Stddev BB Tunas</label>
                            <input type="text" name="stddev_bb_tunas" id="stddev_bb_tunas" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="bk_tunas" class="block text-sm font-medium text-gray-700">BK Tunas</label>
                            <input type="text" name="bk_tunas" id="bk_tunas" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_bk_tunas" class="block text-sm font-medium text-gray-700">Mean BK Tunas</label>
                            <input type="text" name="mean_bk_tunas" id="mean_bk_tunas" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_bk_tunas" class="block text-sm font-medium text-gray-700">Stddev BK Tunas</label>
                            <input type="text" name="stddev_bk_tunas" id="stddev_bk_tunas" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="bb_akar" class="block text-sm font-medium text-gray-700">BB Akar</label>
                            <input type="text" name="bb_akar" id="bb_akar" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_bb_akar" class="block text-sm font-medium text-gray-700">Mean BB Akar</label>
                            <input type="text" name="mean_bb_akar" id="mean_bb_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_bb_akar" class="block text-sm font-medium text-gray-700">Stddev BB Akar</label>
                            <input type="text" name="stddev_bb_akar" id="stddev_bb_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="bk_akar" class="block text-sm font-medium text-gray-700">BK Akar</label>
                            <input type="text" name="bk_akar" id="bk_akar" class="mt-1 block w-full" autocomplete="off" required>
                        </div>
                        <div class="mb-4">
                            <label for="mean_bk_akar" class="block text-sm font-medium text-gray-700">Mean BK Akar</label>
                            <input type="text" name="mean_bk_akar" id="mean_bk_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                        <div class="mb-4">
                            <label for="stddev_bk_akar" class="block text-sm font-medium text-gray-700">Stddev BK Akar</label>
                            <input type="text" name="stddev_bk_akar" id="stddev_bk_akar" class="mt-1 block w-full" autocomplete="off" >
                        </div>
                    `;
                    if (action == 'edit') {
                    form.innerHTML += '<input type="hidden" name="_method" value="PUT">'; // Spoof PUT method
                    modalTitle.textContent = "Edit Green House Data";
                    document.getElementById('no').value = data.no;
                    document.getElementById('kode').value = data.kode;
                    document.getElementById('minggu_panen').value = data.minggu_panen;
                    document.getElementById('perlakuan_penyiraman').value = data.perlakuan_penyiraman;
                    document.getElementById('tinggi_tanaman').value = data.tinggi_tanaman;
                    document.getElementById('mean_tinggi_tanaman').value = data.mean_tinggi_tanaman;
                    document.getElementById('stddev_tinggi_tanaman').value = data.stddev_tinggi_tanaman;
                    document.getElementById('panjang_akar').value = data.panjang_akar;
                    document.getElementById('mean_panjang_akar').value = data.mean_panjang_akar;
                    document.getElementById('stddev_panjang_akar').value = data.stddev_panjang_akar;
                    document.getElementById('bb_tunas').value = data.bb_tunas;
                    document.getElementById('mean_bb_tunas').value = data.mean_bb_tunas;
                    document.getElementById('stddev_bb_tunas').value = data.stddev_bb_tunas;
                    document.getElementById('bk_tunas').value = data.bk_tunas;
                    document.getElementById('mean_bk_tunas').value = data.mean_bk_tunas;
                    document.getElementById('stddev_bk_tunas').value = data.stddev_bk_tunas;
                    document.getElementById('bb_akar').value = data.bb_akar;
                    document.getElementById('mean_bb_akar').value = data.mean_bb_akar;
                    document.getElementById('stddev_bb_akar').value = data.stddev_bb_akar;
                    document.getElementById('bk_akar').value = data.bk_akar;
                    document.getElementById('mean_bk_akar').value = data.mean_bk_akar;
                    document.getElementById('stddev_bk_akar').value = data.stddev_bk_akar;
                    form.action = '{{ route('gh.update', ':id') }}'.replace(':id', data.id);
                     }
                    if (action == 'add') {
                        form.innerHTML += '<input type="hidden" name="observation_id" value="{{$observation->id}}">'; 
                        modalTitle.textContent = "Add New Green House Data";
                        form.action = '{{ route('gh.store')}}';
                    }
        }

        // Show the modal
        document.getElementById('my_modal_5').showModal();
    }

    window.openModal = openModal;
    });




</script>
@endpush


</x-app-layout>
