<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class = "pt-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
            <!-- Card 1 -->
            <div class="bg-base-200 p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold mb-1">Total Users</h4>
                    <p class="text-lg text-gray-600">{{$userCount}}</p>
                </div>
                <i class="fas fa-binoculars text-green-500 text-2xl"></i>
            </div>

            <!-- Card 2 -->
            <div class="bg-base-200 p-4 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold mb-1">Total Admins</h4>
                    <p class="text-lg text-gray-600">{{$adminCount}}</p>
                </div>
                <i class="fas fa-leaf text-blue-500 text-2xl"></i>
            </div>

  
        </div>
    </div>
</div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table id="users-table" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
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
            <form id="general-form" method="POST" action="'{{ route('users.store') }}'">
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
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('users.data') }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error(xhr.responseText); // log the error for debugging
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'role', name: 'role' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    {
                    data: null,
                    name: 'action',
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
            },
                scrollX: true,
            
            });


            $('#users-table').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                console.log('Edit button clicked for ID:', id);
                $.ajax({
                    url: '{{ route('users.edit', '') }}/' + id,
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
            const submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true);
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
        $('#users-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const url = '{{ route('users.destroy', '') }}/' + id;
            const token = '{{ csrf_token() }}';

            if (confirm('Are you sure you want to delete this user?')) {
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
        console.log('Modal opened');
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('general-form');
        const formFields = document.getElementById('form-fields');
        let id = `        
        <div class="mb-4">
            <label for="id" class="block text-sm font-medium text-gray-700">ID</label>
            <input type="text" name="id" id="id" class="mt-1 block w-full" autocomplete="off" required>
        </div>`;

        formFields.innerHTML = `
        ${action === 'edit' ? id : ''}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full" autocomplete="off">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full" autocomplete="off">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full" autocomplete="off">
        </div>
    `;

        if (action === 'add') {
            modalTitle.textContent = 'Add New Data';
            form.action = '{{ route('users.store') }}';
        } else if (action === 'edit') {
            modalTitle.textContent = 'Edit Data';
            form.action = '{{ route('users.update', '') }}/' + data.id;

            // Populate the form fields with the data
            document.getElementById('id').readOnly = true;
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            
            // Add the hidden input field for PUT method
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }

        document.getElementById('my_modal_5').showModal();
        
    }
        });
    </script>
    @endpush


</x-app-layout>

