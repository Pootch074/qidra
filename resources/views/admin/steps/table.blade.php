@extends('layouts.admin')
@section('title', 'Steps')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            {{-- ✅ Add Step Modal --}}
            <div id="addUserModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
                <div
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">Add New Step</h3>
                        <button id="closeAddUserModal"
                            class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                    </div>

                    <form id="addStepForm" method="POST" action="{{ route('store.step') }}">
                        @csrf
                        <x-step-select name="step_name" :selected="old('step_name')" />
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAddUser"
                                class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Cancel
                            </button>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Save Step
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Header & Add Step --}}
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Steps</h2>
                    <button id="openAddUserModal"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Add Step
                    </button>
                </div>
                <div class="overflow-x-auto flex-1">
                    <div class="md:w-7/12 w-full bg-[#2e3192] p-3 flex flex-col h-full">
                        <div id="stepsContainer" class="flex flex-col w-full h-full justify-start">
                        </div>
                        <div id="noSteps" class="hidden text-white text-lg font-medium">
                            No steps available for your section.
                        </div>
                    </div>

                    {{-- <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide rounded-tl-lg">Step Number</th>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide">Step Name</th>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse($steps as $step)
                                <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200"
                                    data-id="{{ $step->id }}">
                                    <td class="text-left px-6 py-3 font-medium text-gray-700">
                                        {{ $step->step_number }}
                                    </td>

                                    <td class="text-left px-6 py-3">
                                        <span class="editable-step-name cursor-pointer text-gray-800 hover:text-blue-600"
                                            data-id="{{ $step->id }}">
                                            {{ $step->step_name }}
                                        </span>
                                        <input type="text"
                                            class="hidden w-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                            value="{{ $step->step_name }}" data-id="{{ $step->id }}">
                                    </td>

                                    <td class="px-6 py-3 text-left">
                                        @if ($step->step_number === 1)
                                            <button
                                                class="bg-gray-400 text-white px-4 py-1.5 rounded-lg shadow-sm cursor-not-allowed"
                                                disabled>
                                                <i class="fas fa-ban"></i> Protected
                                            </button>
                                        @else
                                            <button
                                                class="delete-step text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2"
                                                data-id="{{ $step->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 py-6">
                                        🚫 No steps available for your section.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sectionId = "{{ Auth::user()->section_id }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // ✅ Modal Toggle
            const openBtn = document.getElementById('openAddUserModal');
            const closeBtn = document.getElementById('closeAddUserModal');
            const cancelBtn = document.getElementById('cancelAddUser');
            const modal = document.getElementById('addUserModal');

            if (openBtn && modal) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            if (closeBtn && modal) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            if (cancelBtn && modal) cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));

            // ✅ Step Name Duplicate Check
            const stepNameSelect = document.getElementById('stepName');
            const saveButton = document.querySelector('#addStepForm button[type="submit"]');
            const stepNameError = document.getElementById('stepNameError');

            stepNameSelect.addEventListener('change', () => {
                const stepName = stepNameSelect.value;

                if (!stepName || stepName === "None") {
                    stepNameSelect.setCustomValidity("");
                    stepNameError.textContent = "";
                    stepNameError.classList.add('hidden');
                    saveButton.disabled = false;
                    return;
                }

                fetch(`${window.appBaseUrl}/steps/check-name/${sectionId}/${encodeURIComponent(stepName)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            stepNameSelect.setCustomValidity("This step name already exists.");
                            stepNameError.textContent =
                                "This step name already exists in your section.";
                            stepNameError.classList.remove('hidden');
                            saveButton.disabled = true;
                        } else {
                            stepNameSelect.setCustomValidity("");
                            stepNameError.textContent = "";
                            stepNameError.classList.add('hidden');
                            saveButton.disabled = false;
                        }
                        stepNameSelect.reportValidity();
                    })
                    .catch(err => console.error("Step name check failed:", err));


            });

            // ✅ Inline edit for step names
            document.querySelectorAll('.editable-step-name').forEach(span => {
                span.addEventListener('click', () => {
                    const input = span.nextElementSibling;
                    if (!input) return;
                    span.classList.add('hidden');
                    input.classList.remove('hidden');
                    input.focus();
                });
            });

            document.querySelectorAll('td input[type="text"]').forEach(input => {
                input.addEventListener('blur', () => {
                    const id = input.dataset.id;
                    const newValue = input.value.trim();
                    const span = input.previousElementSibling;

                    // Prevent duplicate names except for "None"
                    if (newValue !== "None") {
                        fetch(
                                `${window.appBaseUrl}/steps/check-name/${sectionId}/${encodeURIComponent(newValue)}`
                            )
                            .then(res => res.json())
                            .then(data => {
                                // If duplicate AND not the same row
                                if (data.exists && newValue !== span.textContent.trim()) {
                                    alert("This step name already exists in your section.");
                                    input.value = span.textContent.trim();
                                    input.classList.add('hidden');
                                    span.classList.remove('hidden');
                                    return;
                                }

                                // Proceed to update if no duplicate
                                updateStepName();
                            });
                    } else {
                        updateStepName(); // Auto-allow "None"
                    }

                    function updateStepName() {
                        fetch(`${window.appBaseUrl}/steps/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    step_name: newValue,
                                    _method: 'PUT'
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    span.textContent = newValue;
                                } else {
                                    alert("Error updating step name.");
                                }
                                input.classList.add('hidden');
                                span.classList.remove('hidden');
                            })
                            .catch(() => {
                                alert("Failed to update step name.");
                                input.classList.add('hidden');
                                span.classList.remove('hidden');
                            });
                    }



                });

                input.addEventListener('keydown', e => {
                    if (e.key === 'Enter') input.blur();
                    if (e.key === 'Escape') {
                        input.value = input.previousElementSibling.textContent.trim();
                        input.classList.add('hidden');
                        input.previousElementSibling.classList.remove('hidden');
                    }
                });
            });

            // ✅ Delete step
            document.querySelectorAll('.delete-step').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    if (!confirm("Are you sure you want to delete this step?")) return;

                    fetch(`${window.appBaseUrl}/steps/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) row.remove();
                            } else {
                                alert("Error deleting step.");
                            }
                        })
                        .catch(err => {
                            console.error('Delete step failed:', err);
                            alert("Failed to delete step.");
                        });
                });
            });

        });
    </script>
@endsection
