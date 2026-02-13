@extends('layouts.auth')
@section('content')
    <div id="auth-register">
        <div class="w-full max-w-6xl bg-[#f6f6f6] rounded-2xl shadow-lg p-8">
            <div class="grid grid-cols-1 2xl:grid-cols-[1fr_2fr] gap-4">
                <div>
                    <div class="text-center flex flex-col items-center">
                        <h1 class="mt-2 text-2xl font-bold text-gray-500">
                            Create an account
                        </h1>
                        <div class="flex flex-col items-center mt-10">
                            <img x-show="show" src="{{ Vite::asset('resources/images/dswd-trademark.png') }}" class="w-30">
                            <img x-show="show" src="{{ Vite::asset('resources/images/qidra-logo3.png') }}" class="w-60">
                        </div>
                    </div>
                </div>

                <div>
                    <form id="registerForm" class="space-y-5" action="{{ route('register.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                    placeholder="First Name"
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            </div>
                            <div class="relative">
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                    placeholder="Last Name"
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            </div>
                            <div class="relative">
                                <select name="division_id" id="divisionId" required
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('division_id') ? '' : 'selected' }}>Area of
                                        Assignment</option>
                                    @foreach ($divisions as $dvsn)
                                        <option value="{{ $dvsn->id }}"
                                            {{ old('division_id') == $dvsn->id ? 'selected' : '' }}>
                                            {{ $dvsn->division_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <select name="section_id" id="sectionId" required {{ old('division_id') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('section_id') ? '' : 'selected' }}>
                                        Section/Office
                                    </option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <select name="position" id="position" required {{ old('section_id') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('position') ? '' : 'selected' }}>Select Position
                                    </option>
                                    @foreach ($positions as $pstn)
                                        <option value="{{ $pstn->position_name }}"
                                            {{ old('position') == $pstn->position_name ? 'selected' : '' }}>
                                            {{ $pstn->position_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                @php
                                    use App\Models\User;
                                @endphp
                                <select name="user_type" id="userType" required {{ old('position') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                                    <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>
                                        Select Role
                                    </option>

                                    @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('user_type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <select name="step_id" id="stepId" required {{ old('user_type') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('step_id') ? '' : 'selected' }}>Select Step
                                    </option>
                                    @foreach ($steps as $stp)
                                        <option value="{{ $stp->id }}"
                                            {{ old('step_id') == $stp->id ? 'selected' : '' }}>
                                            {{ $stp->step_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <select name="category_id" id="categoryId" required {{ old('step_id') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select
                                        Category
                                    </option>
                                    @foreach ($categories as $ctgrs)
                                        <option value="{{ $ctgrs->id }}"
                                            {{ old('category_id') == $ctgrs->id ? 'selected' : '' }}>
                                            {{ $ctgrs->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <select name="window_id" id="windowId" required {{ old('category_id') ? '' : 'disabled' }}
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled {{ old('window_id') ? '' : 'selected' }}>Select Window
                                    </option>
                                    @foreach ($windows as $wndw)
                                        <option value="{{ $wndw->id }}"
                                            {{ old('window_id') == $wndw->id ? 'selected' : '' }}>
                                            {{ $wndw->window_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <input type="email" name="email" autocomplete="email" value="{{ old('email') }}"
                                    required placeholder="Email Address"
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                                @error('email')
                                    <p id="emailMessage" class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required placeholder="Password"
                                    class="block w-full h-14 pl-3 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                        class="w-5 h-5">
                                    <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                        class="w-5 h-5">
                                </button>
                            </div>

                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                    placeholder="Confirm Password"
                                    class="block w-full h-14 pl-3 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                        class="w-5 h-5">
                                    <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                        class="w-5 h-5">
                                </button>
                            </div>
                            @error('password')
                                <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            @error('password_confirmation')
                                <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-center space-x-2 pl-2">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms" class="text-sm text-gray-700">
                                I agree to the <button type="button" id="openTermsModal"
                                    class="text-indigo-600 hover:underline">
                                    User Service Agreement & Privacy Notice
                                </button>.
                            </label>
                        </div>

                        <div id="termsModal"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 pointer-events-none">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 pointer-events-auto">
                                <x-privacy-modal />
                                <div class="mt-6 flex justify-end">
                                    <button id="closeTermsModal"
                                        class="px-4 py-2 text-sm text-white rounded rounded-xl bg-[#2e3192] font-semibold hover:bg-indigo-700 transition">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center mt-4">
                            <button type="submit"
                                class="w-75 h-14 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700 transition">
                                Register
                            </button>
                        </div>
                    </form>
                    <div class="text-center text-sm text-gray-500 space-y-2 mt-2">
                        <div>
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Sign
                                in</a>
                        </div>
                        <div>
                            Need Help? Email us at
                            <a href="mailto:ictsupport.fo11@dswd.gov.ph"
                                class="text-indigo-600 hover:underline">ictsupport.fo11@dswd.gov.ph</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
{{-- <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script> --}}

@section('scripts')
    <script>
        setTimeout(() => {
            document.querySelectorAll('.form-error').forEach(el => {
                el.remove();
            });
        }, 10000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const divisionSelect = document.getElementById('divisionId');
            const sectionSelect = document.getElementById('sectionId');
            const positionSelect = document.getElementById('position');
            const userTypeSelect = document.getElementById('userType');
            const stepSelect = document.getElementById('stepId');
            const categorySelect = document.getElementById('categoryId');
            const windowSelect = document.getElementById('windowId');

            const populateDropdown = (select, url, placeholder, textKey, callback) => {
                select.disabled = true;
                select.innerHTML = `<option value="" disabled>Loading...</option>`;
                fetch(url)
                    .then(res => res.json())
                    .then(items => {
                        select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
                        if (items.length) {
                            items.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = item[textKey];
                                select.appendChild(option);
                            });
                        } else {
                            select.innerHTML +=
                                `<option value="">No ${placeholder.toLowerCase()} available</option>`;
                        }
                        select.disabled = false;
                        if (callback) callback();
                    })
                    .catch(() => {
                        select.innerHTML =
                            `<option value="">Failed to load ${placeholder.toLowerCase()}</option>`;
                        select.disabled = false;
                        if (callback) callback();
                    });
            };

            const triggerChangeIfOld = (select, callback) => {
                if (select.value) {
                    select.dispatchEvent(new Event('change'));
                    if (callback) callback();
                }
            };

            // Division → Section
            divisionSelect.addEventListener('change', () => {
                // sectionSelect.innerHTML =
                //     `<option value="" disabled selected>Section/Office</option>`;
                // stepSelect.innerHTML = `<option value="" disabled selected>Select Step</option>`;
                // categorySelect.innerHTML = `<option value="" disabled selected>Select Category</option>`;
                // windowSelect.innerHTML = `<option value="" disabled selected>Select Window</option>`;

                // sectionSelect.disabled = true;
                // stepSelect.disabled = true;
                // categorySelect.disabled = true;
                // windowSelect.disabled = true;

                if (divisionSelect.value) {
                    populateDropdown(sectionSelect, `/auth/sections/${divisionSelect.value}`,
                        'Section/Office', 'section_name');
                }
            });

            // Section → Step
            sectionSelect.addEventListener('change', () => {
                // stepSelect.innerHTML = `<option value="" disabled selected>Step</option>`;
                // categorySelect.innerHTML = `<option value="" disabled selected>Category</option>`;
                // windowSelect.innerHTML = `<option value="" disabled selected>Window</option>`;

                // stepSelect.disabled = true;
                // categorySelect.disabled = true;
                // windowSelect.disabled = true;

                if (sectionSelect.value) {
                    populateDropdown(stepSelect, `/auth/steps/${sectionSelect.value}`, 'Step', 'step_name');
                }
            });

            // Step → Category
            stepSelect.addEventListener('change', () => {
                categorySelect.innerHTML = `<option value="" disabled selected>Category</option>`;
                windowSelect.innerHTML = `<option value="" disabled selected>Window</option>`;

                categorySelect.disabled = true;
                windowSelect.disabled = true;

                if (stepSelect.value) {
                    populateDropdown(categorySelect, `/auth/categories/${stepSelect.value}`, 'Category',
                        'category_name');
                }
            });

            categorySelect.addEventListener('change', () => {
                windowSelect.innerHTML = `<option value="" disabled selected>Window</option>`;
                windowSelect.disabled = true;

                if (categorySelect.value) {
                    populateDropdown(windowSelect, `/auth/windows/${categorySelect.value}`, 'Window',
                        'window_number');
                }
            });

            // Trigger change on page load for old values
            triggerChangeIfOld(divisionSelect, () => {
                triggerChangeIfOld(sectionSelect, () => {
                    triggerChangeIfOld(stepSelect);
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('openTermsModal');
            const closeBtn = document.getElementById('closeTermsModal');
            const modal = document.getElementById('termsModal');

            if (openBtn && closeBtn && modal) {
                openBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.classList.remove('hidden');
                    modal.classList.remove('pointer-events-none');
                });

                closeBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    modal.classList.add('pointer-events-none');
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userTypeSelect = document.getElementById('userType');
            const stepSelect = document.getElementById('stepId');
            const categorySelect = document.getElementById('categoryId');
            const windowSelect = document.getElementById('windowId');

            const disableFields = () => {
                const value = parseInt(userTypeSelect.value);

                // Reset all first
                stepSelect.disabled = false;
                categorySelect.disabled = false;
                windowSelect.disabled = false;

                if (value === 1 || value === 3) { // Admin or PACD
                    stepSelect.disabled = true;
                    categorySelect.disabled = true;
                    windowSelect.disabled = true;
                } else if (value === 6) { // Display
                    stepSelect.disabled = true;
                    categorySelect.disabled = false;
                    windowSelect.disabled = true;
                }
            };

            // Trigger on change
            userTypeSelect.addEventListener('change', disableFields);

            // Trigger on page load if old value exists
            if (userTypeSelect.value) {
                disableFields();
            }
        });
    </script>
@endsection
{{-- @vite('resources/css/app.css') --}}
