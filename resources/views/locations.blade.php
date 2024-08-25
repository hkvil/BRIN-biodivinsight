<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Locations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="locations-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dusun</th>
                            <th>Desa</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten</th>
                            <th>Altitude</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
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
            <h3 class="text-lg font-bold" id="modal-title">Add New Location</h3>
            <form id="location-form" method="POST" action="{{ route('locations.store') }}">
                @csrf
                <input type="hidden" id="location-id" name="id">
                <div class="py-4">
                    <div class="mb-4">
                        <label for="dusun" class="block text-sm font-medium text-gray-700">Dusun</label>
                        <input type="text" name="dusun" id="dusun" class="mt-1 block w-full" maxlength="15" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="desa" class="block text-sm font-medium text-gray-700">Desa</label>
                        <input type="text" name="desa" id="desa" class="mt-1 block w-full" maxlength="15" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="kelurahan" class="block text-sm font-medium text-gray-700">Kelurahan</label>
                        <input type="text" name="kelurahan" id="kelurahan" class="mt-1 block w-full" maxlength="15" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan" class="mt-1 block w-full" maxlength="15" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten</label>
                        <input type="text" name="kabupaten" id="kabupaten" class="mt-1 block w-full" maxlength="15" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="altitude" class="block text-sm font-medium text-gray-700">Altitude</label>
                        <input type="number" name="altitude" id="altitude" class="mt-1 block w-full"  autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="number" name="longitude" id="longitude" class="mt-1 block w-full" maxlength="10" step="0.0000001" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="number" name="latitude" id="latitude" class="mt-1 block w-full" maxlength="10" step="0.0000001" autocomplete="off" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn">Save</button>
                    <button type="button" class="btn" onclick="document.getElementById('my_modal_5').close()">Close</button>
                </div>
            </form>
        </div>
    </dialog>

    @push('scripts')
    {{-- <script>
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
    </script> --}}

    <script>
        $(document).ready(function() {
            // Initialize the DataTable
            const table = $('#locations-table').DataTable({
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
                ],
                layout: {
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
                },order:[[0,'desc']]
            });
    
                // Handle form submission
                $('#location-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response .dusun +" Added");
                            table.ajax.reload(); // Reload the DataTable
                            document.getElementById('my_modal_5').close(); // Close the modal
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                });
            });
    
            function showSuccessAlert(message) {
                const alertContainer = document.createElement('div');
                alertContainer.className = 'alert alert-success shadow-lg mb-4';
                alertContainer.innerHTML = `
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>${message}</span>
                    </div>
                `;
                const datatableWrapper = document.getElementById('locations-table_wrapper');
                datatableWrapper.insertBefore(alertContainer, datatableWrapper.firstChild);
    
                // Remove the alert after 3 seconds
                setTimeout(() => {
                    alertContainer.remove();
                }, 3000);
            }
    
            // Handle Edit button click
            $('#locations-table').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                console.log('Edit button clicked for ID:', id);
                $.ajax({
                    url: '{{ route('locations.edit', '') }}/' + id,
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
    
            // Handle Delete button click
            $('#locations-table').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const url = '{{ route('locations.destroy', '') }}/' + id;
                const token = '{{ csrf_token() }}';
    
                if (confirm('Are you sure you want to delete this location?')) {
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
                            alert('Failed to delete the plant. Please try again.');
                        }
                    });
                }
            });
    
            function openModal(action, data = null) {
                const modal = document.getElementById('my_modal_5');
                const form = document.getElementById('location-form');
                const locationId = document.getElementById('location-id');
                const modalTitle = document.getElementById('modal-title');
                const dusun = document.getElementById('dusun');
                const desa = document.getElementById('desa');
                const kelurahan = document.getElementById('kelurahan');
                const kecamatan = document.getElementById('kecamatan');
                const kabupaten = document.getElementById('kabupaten');
                const altitude = document.getElementById('altitude');
                const longitude = document.getElementById('longitude');
                const latitude = document.getElementById('latitude');
    
                if (action === 'add') {
                    modalTitle.textContent = "Add New Location"
                    form.action = '{{ route('locations.store') }}';
                    locationId.value = '';
                    dusun.value = '';
                    desa.value = '';
                    kelurahan.value = '';
                    kecamatan.value = '';
                    kabupaten.value = '';
                    altitude.value = '';
                    longitude.value = '';
                    latitude.value = '';
                } else if (action === 'edit') {
                    modalTitle.textContent = "Edit Location"
                    form.action = '{{ route('locations.update', '') }}/' + data.id;
                    locationId.value = data.id;
                    dusun.value = data.dusun;
                    desa.value = data.desa;
                    kelurahan.value = data.kelurahan;
                    kecamatan.value = data.kecamatan;
                    kabupaten.value = data.kabupaten;
                    altitude.value = data.altitude;
                    longitude.value = data.longitude;
                    latitude.value = data.latitude;
                    // Add the hidden input field for PUT method
                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }
    
                modal.showModal();
            }
    
        });
    
    
    
    </script>
    @endpush
</x-app-layout>

<!-- 
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
                    { data: 'species_name', name: 'species_name' },
                    { data: 'common_name', name: 'common_name' },
                ],
            });
        });
    </script>
    @endpush -->