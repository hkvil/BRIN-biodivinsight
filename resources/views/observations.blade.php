<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Observation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="observations-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Plant Name</th>
                            <th>Location Name</th>
                            <th>Observation Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            <!-- Add New Data Modal -->
    <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold" id="modal-title">Add New Data</h3>
            <form id="general-form" method="POST" action="'{{ route('observations.store') }}'">
                @csrf
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
                    { data: 'observation_type', name: 'observation_type' },
                    { data: 'observation_date', name: 'observation_date' },
                    { data: 'observation_time', name: 'observation_time' },
                    { data: 'remarks', name: 'remarks' },
                    {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let modifyButtons = '';
                        if (row.can_modify) {
                            modifyButtons = `
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            `;
                        }
                        return `
                        ${modifyButtons}
                        <button class="btn btn-sm btn-info detail-btn" data-id="${row.id}">
                            <i class="fas fa-info-circle"></i>
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
                                openModal('add');
                            }
                        }
                    ]
                }
            }
            
            });

            // Handle Detail button click
            $('#observations-table').on('click', '.detail-btn', function() {
                const id = $(this).data('id');
                console.log('Detail button clicked for ID:', id);
                window.location.href = '/observation/' + id;
            });

            // Handle Edit button click
            $('#observations-table').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                console.log('Edit button clicked for ID:', id);
                $.ajax({
                    url: '{{ route('observation.edit', '') }}/' + id,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            openModal('edit', response.data);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                });
            });

            $('#general-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // showSuccessAlert("Added");
                        table.ajax.reload(); // Reload the DataTable
                        document.getElementById('my_modal_5').close(); // Close the modal
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // log the error for debugging
                }
            });
        });
        // Handle Delete button click
        $('#observations-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const url = '{{ route('observations.destroy', '') }}/' + id;
            const token = '{{ csrf_token() }}';

            if (confirm('Are you sure you want to delete this observation?')) {
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
                        console.error('Delete failed for ID:', id);
                        alert('Failed to delete. Please try again.');
                    }
                });
            }
        });

        function openModal(action, data = null) {
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('general-form');
        const formFields = document.getElementById('form-fields');

        formFields.innerHTML = `
        <div class="mb-4">
            <label for="observation_type" class="block text-sm font-medium text-gray-700">Observation Type</label>
            <select name="observation_type" id="observation_type" class="mt-1 block w-full" required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="mb-4">
            <label for="plant_id" class="block text-sm font-medium text-gray-700">Plant Name</label>
            <select name="plant_id" id="plant_id" class="mt-1 block w-full" required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="mb-4">
            <label for="location_id" class="block text-sm font-medium text-gray-700">Location Name</label>
            <select name="location_id" id="location_id" class="mt-1 block w-full" required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="mb-4">
            <label for="observation_date" class="block text-sm font-medium text-gray-700">Observation Date</label>
            <input type="date" name="observation_date" id="observation_date" class="mt-1 block w-full" autocomplete="off" required>
        </div>
        <div class="mb-4">
            <label for="observation_time" class="block text-sm font-medium text-gray-700">Observation Time</label>
            <input type="time" name="observation_time" id="observation_time" class="mt-1 block w-full" autocomplete="off" required>
        </div>
        <div class="mb-4">
            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
            <textarea name="remarks" id="remarks" class="mt-1 block w-full" autocomplete="off" required></textarea>
        </div>
    `;

        if (action === 'add') {
            modalTitle.textContent = 'Add New Data';
            form.action = '{{ route('observations.store') }}';
        } else if (action === 'edit') {
            modalTitle.textContent = 'Edit Data';
            form.action = '{{ route('observations.update', '') }}/' + data.id;

            // Populate the form fields with the data
            document.getElementById('plant_id').value = data.plant_id;
            document.getElementById('location_id').value = data.location_id;
            document.getElementById('observation_date').value = data.observation_date;
            document.getElementById('observation_time').value = data.observation_time;
            document.getElementById('remarks').value = data.remarks.remarks;

            document.getElementById('observation_type').value = data.observation_type;
            document.getElementById('observation_type').disabled = true;

            // Add the hidden input field for PUT method
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }

        populateSelectOptions('observation_type', '/api/observation-types');
        populateSelectOptions('plant_id', '/api/plants');
        populateSelectOptions('location_id','/api/locations');

        // Show the modal
        document.getElementById('my_modal_5').showModal();
        }

        function populateSelectOptions(selectId, apiUrl) {
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data fetched:', data);
                        const select = document.getElementById(selectId);
                        data.forEach(item => {
                            console.log('tes')
                            const option = document.createElement('option');
                            option.value = item.id;
                            if(selectId === 'plant_id') {
                                option.value = item.id;
                                option.text = item.species_name + ' (' + item.common_name + ')';
                            } else if(selectId === 'location_id') {
                                option.text = `${item.dusun}, ${item.desa}, ${item.kelurahan}, ${item.kecamatan}, ${item.kabupaten}, Altitude: ${item.altitude}, Longitude: ${item.longitude}, Latitude: ${item.latitude}`;
                            } else if (selectId === 'observation_type') {
                                option.value = item;
                                option.text = item;
                            }
                            select.appendChild(option);
                        });
                        
                        $('#' + selectId).chosen();
                        console.log('Select2 initialized for:', selectId);
                    })
                    .catch(error => console.error('Error fetching data:', error));

                
            }});

    </script>
    @endpush
</x-app-layout>
