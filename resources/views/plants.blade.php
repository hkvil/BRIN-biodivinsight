<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="plants-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Species Name') }}</th>
                            <th>{{ __('Common Name') }}</th>
                            <th>Actions</th>
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
            <h3 class="text-lg font-bold" id="modal-title">Add New Plant</h3>
            <form id="plant-form" method="POST" action="{{ route('plants.store') }}">
                @csrf
                <input type="hidden" id="plant-id" name="id">
                <div class="py-4">
                    <div class="mb-4">
                        <label for="species_name" class="block text-sm font-medium text-gray-700">Species Name</label>
                        <input type="text" name="species_name" id="species_name" class="mt-1 block w-full" autocomplete="off" required>
                    </div>
                    <div class="mb-4">
                        <label for="common_name" class="block text-sm font-medium text-gray-700">Common Name</label>
                        <input type="text" name="common_name" id="common_name" class="mt-1 block w-full" autocomplete="off" required>
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
<script>
    $(document).ready(function() {
        // Initialize the DataTable
        const table = $('#plants-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('plants.data') }}',
                type: 'GET',
                error: function (xhr, error, thrown) {
                    console.error(xhr.responseText); // log the error for debugging
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'species_name', name: 'species_name' },
                { data: 'common_name', name: 'common_name' },
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
            $('#plant-form').on('submit', function(e) {
            e.preventDefault();
            const submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showSuccessAlert(response .species_name +" Added");
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
            const datatableWrapper = document.getElementById('plants-table_wrapper');
            datatableWrapper.insertBefore(alertContainer, datatableWrapper.firstChild);

            // Remove the alert after 3 seconds
            setTimeout(() => {
                alertContainer.remove();
            }, 3000);
        }

        // Handle Edit button click
        $('#plants-table').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            console.log('Edit button clicked for ID:', id);
            $.ajax({
                url: '{{ route('plants.edit', '') }}/' + id,
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
        $('#plants-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const url = '{{ route('plants.destroy', '') }}/' + id;
            const token = '{{ csrf_token() }}';

            if (confirm('Are you sure you want to delete this plant?')) {
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
            const form = document.getElementById('plant-form');
            const plantId = document.getElementById('plant-id');
            const modalTitle = document.getElementById('modal-title');
            const speciesName = document.getElementById('species_name');
            const commonName = document.getElementById('common_name');

            if (action === 'add') {
                modalTitle.textContent = "Add New Plant"
                form.action = '{{ route('plants.store') }}';
                plantId.value = '';
                speciesName.value = '';
                commonName.value = '';
            } else if (action === 'edit') {
                modalTitle.textContent = "Edit Plant"
                form.action = '{{ route('plants.update', '') }}/' + data.id;
                plantId.value = data.id;
                speciesName.value = data.species_name;
                commonName.value = data.common_name;

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
            var table = $('#plants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('plants.data') }}',
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