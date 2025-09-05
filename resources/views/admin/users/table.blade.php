@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">
    <div class="p-4 sm:ml-64">

        {{-- Header & Add User --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Users</h2>
            <button id="openAddUserModal" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add User
            </button>
        </div>

        {{-- ✅ Add User Modal --}}
        <div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Add New User</h3>
            <button id="closeAddUserModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        </div>

        <form id="addUserForm" class="space-y-5">
            @csrf

            {{-- First & Last Name --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">First Name</label>
                    <input type="text" name="first_name" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Last Name</label>
                    <input type="text" name="last_name" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" required 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Position & User Type --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Position</label>
                    <input type="text" name="position" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">User Type</label>
                    <select name="user_type" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select --</option>
                        @foreach($userTypes as $typeId => $typeName)
                            <option value="{{ $typeId }}">{{ $typeName }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            {{-- Assign Category & Step --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Category</label>
                    <select name="assigned_category" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select --</option>
                        <option value="regular">Regular</option>
                        <option value="priority">Priority</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                    <select name="step_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Step --</option>
                        @foreach($steps as $step)
                            <option value="{{ $step->id }}">{{ $step->step_number }} - {{ $step->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            {{-- Assign Window --}}
<div>
    <label class="block text-sm font-medium text-gray-600">Assign Window</label>
<select name="window_id" required disabled
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="">-- Select Window --</option>
</select>

</div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter password">
            </div>


            {{-- Buttons --}}
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelAddUser" 
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" 
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>

        {{-- ✅ Fancy Users Table --}}
<div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
    <table id="usersTable" class="min-w-full text-sm text-left border-collapse">
        <thead>
            <tr class="bg-[#150e60] text-white">
                @foreach($userColumns as $label)
                    <th class="px-6 py-3 font-semibold tracking-wide">{{ $label }}</th>
                @endforeach
                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Step</th>
                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Window</th>
                <th class="px-6 py-3 font-semibold tracking-wide text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($users as $u)
                <tr class="hover:bg-indigo-50 transition duration-200">
                    @foreach($userColumns as $field => $label)
                        <td class="px-6 py-3 text-gray-700">
                            @if($field === 'user_type')
                                {{ $u->getUserTypeName() }}
                            @else
                                {{ $u->$field ?? '—' }}
                            @endif
                        </td>
                    @endforeach


                    {{-- Assigned Step --}}
                    <td class="px-6 py-3 font-medium text-gray-700">
                        {{ $u->step->step_number ?? '—' }}
                    </td>

                    {{-- Assigned Window --}}
                    <td class="px-6 py-3 font-medium text-gray-700">
                        {{ $u->window->window_number ?? '—' }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-3 text-center space-x-2">
                        <a href="#"
                           class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                           <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteUser({{ $u->id }})"
                                class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-6 text-center text-gray-500">
                        🚫 No users found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addUserModal');
    const openBtn = document.getElementById('openAddUserModal');
    const closeBtn = document.getElementById('closeAddUserModal');
    const cancelBtn = document.getElementById('cancelAddUser');

    // Open modal with animation
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    });

    // Close modal
    const closeModal = () => {
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    };
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Add user AJAX
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("{{ route('admin.users.store') }}", {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: formData
})
.then(async res => {
    if (!res.ok) {
        const text = await res.text();
        throw new Error(text);
    }
    return res.json();
})
.then(data => {
    if(data.success) {
        const u = data.user;
        const row = `
        <tr id="userRow-${u.id}" class="hover:bg-indigo-50 transition duration-200">
            <td class="px-6 py-4">${u.first_name}</td>
            <td class="px-6 py-4">${u.last_name}</td>
            <td class="px-6 py-4">${u.email}</td>
            <td class="px-6 py-4">${u.position}</td>
            <td class="px-6 py-4">${u.user_type_name}</td>
            <td class="px-6 py-4">${u.assigned_category}</td>
            <td class="px-6 py-4">${u.step_number ?? '—'}</td>
            <td class="px-6 py-4">${u.window_number ?? '—'}</td>
            <td class="px-6 py-4 space-x-2">
                <a href="#" class="text-green-600 hover:underline">Edit</a>
                <button onclick="deleteUser(${u.id})" class="text-red-600 hover:underline">Delete</button>
            </td>
        </tr>`;
        document.querySelector('#usersTable tbody').insertAdjacentHTML('beforeend', row);
        closeModal();
        this.reset();
    } else {
        alert('Error: ' + (data.message ?? 'Unknown error'));
    }
})
.catch(err => {
    console.error("Save user failed:", err);
    alert("Save failed. Check console for details.");
});

    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const userTypeSelect = document.querySelector('select[name="user_type"]');
    const assignedCategory = document.querySelector('select[name="assigned_category"]');
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    const toggleFields = () => {
        const selectedText = userTypeSelect.options[userTypeSelect.selectedIndex].text.toLowerCase();
        const isDisplay = selectedText === 'display';

        assignedCategory.disabled = isDisplay;
        stepSelect.disabled = isDisplay;
        windowSelect.disabled = isDisplay;

        // Optional: visually indicate disabled state
        [assignedCategory, stepSelect, windowSelect].forEach(field => {
            field.classList.toggle('bg-gray-100', isDisplay);
            field.classList.toggle('cursor-not-allowed', isDisplay);
        });
    };

    // Trigger on change
    userTypeSelect.addEventListener('change', toggleFields);

    // Initialize in case the modal opens with Display selected
    toggleFields();
});
</script>


<script>
    // Delete User Function
function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Remove the user row from the table
            const row = document.getElementById(`userRow-${userId}`);
            if (row) row.remove();
        } else {
            alert('Error deleting user: ' + (data.message ?? 'Unknown error'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while deleting the user.');
    });
}

</script>

<script>
function renderUsers(users) {
    const tbody = document.querySelector('#usersTable tbody');
    tbody.innerHTML = '';

    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-6 text-center text-gray-500">
                🚫 No users found.
            </td>
        </tr>`;
        return;
    }

    users.forEach(u => {
        const row = document.createElement('tr');
        row.id = `userRow-${u.id}`;
        row.className = 'hover:bg-indigo-50 transition duration-200';

        row.innerHTML = `
            <td class="px-6 py-3 text-gray-700">${u.first_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.last_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.email}</td>
            <td class="px-6 py-3 text-gray-700">${u.position}</td>
            <td class="px-6 py-3 text-gray-700">${u.user_type_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.assigned_category}</td>

            <!-- IMPORTANT: Step then Window (match the table header) -->
            <td class="px-6 py-3 text-gray-700">${u.step_number ?? '—'}</td>
            <td class="px-6 py-3 text-gray-700">${u.window_number ?? '—'}</td>

            <td class="px-6 py-3 text-center space-x-2">
                <a href="#" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button onclick="deleteUser(${u.id})" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}





// Poll the endpoint every 1 second
function fetchUsers() {
    fetch("{{ route('admin.users.json') }}")
        .then(res => res.json())
        .then(data => renderUsers(data))
        .catch(err => console.error(err));
}

// Initial fetch and interval
fetchUsers();
setInterval(fetchUsers, 1000);
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    stepSelect.addEventListener('change', function () {
        const stepId = this.value;

        // reset window dropdown
        windowSelect.innerHTML = '<option value="">-- Select Window --</option>';
        windowSelect.disabled = true;

        if (stepId) {
            fetch(`/windows/by-step/${stepId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(w => {
                            let opt = document.createElement('option');
                            opt.value = w.id;
                            opt.textContent = w.window_number;
                            windowSelect.appendChild(opt);
                        });
                        windowSelect.disabled = false;
                    }
                })
                .catch(err => console.error(err));
        }
    });
});
</script>

@endsection
