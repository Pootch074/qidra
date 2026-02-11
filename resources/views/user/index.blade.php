@extends('layouts.main')
@section('title', 'Encode')
@section('content')
    <div class="w-full h-[84vh] p-4 bg-gray-200">
        <div class="grid grid-cols-10 gap-2 h-full">
            @php
                use App\Enums\ClientType;
                use App\Enums\QueueStatus;
                use App\Libraries\Sections;
                use App\Libraries\Steps;
                use Illuminate\Support\Facades\Auth;

                $user = Auth::user()->load('category'); // eager load to avoid N+1 queries
                $userCategory = $user->category_name;
                $sectionId = $user->section_id ?? null;
                $stepId = $user->step_id ?? null;

                $crisisSectionId = Sections::CRISIS_INTERVENTION_SECTION();
                $preAssessmentId = Steps::PRE_ASSESSMENT();
                $encodingId = Steps::ENCODE();

                // Determine whether to show Returnee-related blocks
                $showReturnee = !($sectionId == $crisisSectionId && in_array($stepId, [$preAssessmentId, $encodingId]));

                // UPCOMING blocks
                $upcomingBlocks = [
                    [
                        'id' => 'upcomingRegu',
                        'title' => strtoupper(ClientType::REGULAR->value),
                        'show' => in_array($userCategory, [ClientType::REGULAR->value, 'both']),
                    ],
                    [
                        'id' => 'upcomingPrio',
                        'title' => strtoupper(ClientType::PRIORITY->value),
                        'show' => in_array($userCategory, [ClientType::PRIORITY->value, 'both']),
                    ],
                    [
                        'id' => 'upcomingReturnee',
                        'title' => 'RETURNEE',
                        'show' => $showReturnee,
                    ],
                ];
                $visibleUpcoming = collect($upcomingBlocks)->where('show', true)->count();

                // PENDING blocks
                $pendingBlocks = [
                    [
                        'id' => 'pendingRegu',
                        'title' => strtoupper(ClientType::REGULAR->value),
                        'show' => in_array($userCategory, [ClientType::REGULAR->value, 'both']),
                    ],
                    [
                        'id' => 'pendingPrio',
                        'title' => strtoupper(ClientType::PRIORITY->value),
                        'show' => in_array($userCategory, [ClientType::PRIORITY->value, 'both']),
                    ],
                    [
                        'id' => 'pendingReturnee',
                        'title' => 'RETURNEE',
                        'show' => $showReturnee,
                    ],
                    [
                        'id' => 'deferred',
                        'title' => strtoupper(ClientType::DEFERRED->value),
                        'show' => $showReturnee,
                    ],
                ];
                $visiblePending = collect($pendingBlocks)->where('show', true)->count();

                // Determine grid columns based on visible blocks
                $gridMap = [
                    1 => 'grid-cols-1',
                    2 => 'grid-cols-2',
                    3 => 'grid-cols-3',
                    4 => 'grid-cols-4',
                ];
                $upcomingGridClass = $gridMap[$visibleUpcoming] ?? 'grid-cols-1';
                $pendingGridClass = $gridMap[$visiblePending] ?? 'grid-cols-1';
            @endphp

            {{-- ===================== UPCOMING ===================== --}}
            <div class="col-span-3 flex flex-col bg-white rounded-md shadow overflow-hidden min-h-0">
                <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">UPCOMING</div>

                <div
                    class="grid {{ $upcomingGridClass }} gap-4 p-2 bg-white rounded-b-lg border-2 border-[#2e3192] flex-1 w-full min-h-0">
                    @foreach ($upcomingBlocks as $block)
                        @if ($block['show'])
                            <div class="flex flex-col bg-white rounded-md shadow overflow-hidden w-full min-h-0">
                                <div class="bg-[#2e3192] text-white text-center font-bold py-2">{{ $block['title'] }}</div>
                                <div id="{{ $block['id'] }}"
                                    class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh] min-h-0"></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- ===================== PENDING ===================== --}}
            <div class="col-span-4 flex flex-col bg-white rounded-md shadow overflow-hidden min-h-0">
                <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">PENDING</div>

                <div
                    class="grid {{ $pendingGridClass }} gap-4 p-2 bg-white w-full rounded-b-lg border-2 border-[#2e3192] flex-1 min-h-0">
                    @foreach ($pendingBlocks as $block)
                        @if ($block['show'])
                            <div class="flex flex-col bg-white rounded-md shadow overflow-hidden w-full min-h-0">
                                <div class="bg-[#2e3192] text-white text-center font-bold py-2">{{ $block['title'] }}</div>
                                <div id="{{ $block['id'] }}"
                                    class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh] min-h-0"></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- SERVING --}}
            <div
                class="col-span-3 flex flex-col rounded-md bg-white rounded-b-lg border-2 border-[#2e3192] shadow overflow-hidden h-[80vh]">
                <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">
                    @if ($stepNumber || $windowNumber || $categoryName)
                        STEP {{ $stepNumber ?? '-' }}&nbsp;
                        WINDOW {{ $windowNumber ?? '-' }}&nbsp;
                        {{ $categoryName ? \Illuminate\Support\Str::title($categoryName) : '-' }}
                    @endif

                </div>

                <div class="text-black text-center font-bold py-2">
                    <p>{{ strtoupper($fieldOffice ?? '-') }}</p>
                    <p>{{ strtoupper($divisionName ?? '-') }}</p>
                    <p>{{ strtoupper($sectionName ?? '-') }}</p>
                </div>

                <div class="text-black text-center font-bold text-2xl py-2">
                    Now Serving
                </div>

                <div id="servingQueue" class="text-center font-bold flex-1">
                </div>

                @php
                    $userCategory = Auth::user()->assigned_category;
                @endphp

                <div class="content-center text-white text-center font-bold flex-1 px-2">
                    <div class="flex space-x-2 w-full mb-5">
                        {{-- Next Regular Button --}}
                        @if (in_array($userCategory, ['regular', 'both']))
                            <button id="nextRegularBtn"
                                class="queue-btn flex-1 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                            focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                            shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 
                            font-medium rounded-lg text-sm py-5 text-center">Next
                                Regular</button>
                        @endif

                        {{-- Next Priority Button --}}
                        @if (in_array($userCategory, ['priority', 'both']))
                            <button id="nextPriorityBtn"
                                class="queue-btn flex-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br 
                            focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 
                            shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 
                            font-medium rounded-lg text-sm py-5 text-center">Next
                                Priority</button>
                        @endif

                        {{-- Next Returnee Button (hidden for section 15 in steps 1 & 2) --}}
                        @if ($showReturnee)
                            <button id="returneeBtn"
                                class="queue-btn flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                            focus:ring-1 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                            shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                            font-medium rounded-lg text-sm py-5 text-center">
                                Next Returnee
                            </button>
                        @endif

                    </div>

                    <div class="flex space-x-2 w-full">
                        <button id="skipBtn"
                            class="queue-btn flex-1 text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 
                        shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 
                        font-medium rounded-lg text-sm py-5 text-center">Skip</button>
                        <button id="recallBtn"
                            class="queue-btn flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                        shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                        font-medium rounded-lg text-sm py-5 text-center">Recall</button>
                        <button id="deferBtn"
                            class="queue-btn flex-1 text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 
                        font-medium rounded-lg text-sm px-5 py-5 text-center">Defer</button>
                        <button id="proceedBtn"
                            class="queue-btn flex-1 text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                        shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                        font-medium rounded-lg text-sm py-5 text-center">Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="popup-modal" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex mt-10 items-center justify-center backdrop-blur-m p-4">
        <div class="relative w-full max-w-md max-h-full">
            <div
                class="relative bg-gray-200 rounded-lg shadow-lg transform transition-all duration-150 scale-95 opacity-0 border-2 border-[#2e3192]">
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                    id="modalCloseBtn">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>

                <div class="p-4 md:p-5 text-center">

                    <img class="mx-auto mb-4 text-gray-400 w-13 h-13"
                        src="{{ Vite::asset('resources/images/icons/alert-circle.png') }}" alt="">

                    <h3 id="modalMessage" class="mb-5 text-lg font-normal text-gray-700">Are you sure you want to perform
                        this action?</h3>

                    <button id="modalConfirmBtn" type="button"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Yes, confirm
                    </button>

                    <button id="modalCancelBtn" type="button"
                        class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sectionId = {{ $sectionId ?? 'null' }};
            const stepNumber = {{ $stepNumber ?? 'null' }};
            const stepPreassess = @json(\App\Libraries\Steps::PRE_ASSESSMENT());

            const stepEncoding = @json(Steps::ENCODE());
            const stepRelease = @json(Steps::RELEASE());
            const crisisSectionId = {{ $crisisSectionId ?? 'null' }};

            if (sectionId === crisisSectionId && (stepNumber === stepPreassess || stepNumber === stepEncoding)) {
                const restrictedDefer = [
                    '#deferBtn'
                ];

                restrictedDefer.forEach(selector => {
                    const btn = document.querySelector(selector);
                    if (btn) {
                        btn.style.display = 'none';

                    }
                });
            }


            if (sectionId === crisisSectionId && stepNumber === stepRelease) {

                const restrictedBtns = [
                    '#nextRegularBtn',
                    '#nextPriorityBtn',
                    '#returneeBtn',
                    '#recallBtn'

                ];

                restrictedBtns.forEach(selector => {
                    const btn = document.querySelector(selector);
                    if (btn) {
                        btn.style.display = 'none';

                    }
                });
            }


            const servingQueueEl = document.getElementById('servingQueue');

            const modal = document.getElementById('popup-modal');
            const modalMessage = document.getElementById('modalMessage');
            const modalConfirmBtn = document.getElementById('modalConfirmBtn');
            const modalCancelBtn = document.getElementById('modalCancelBtn');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            let currentAction = null;

            const showModal = (message, actionCallback) => {
                modalMessage.textContent = message;
                currentAction = actionCallback;
                modal.classList.remove('hidden');
                const modalContent = modal.querySelector('div.relative.bg-gray-200');
                modalContent.classList.remove('opacity-0', 'scale-95');
                modalContent.classList.add('opacity-100', 'scale-100');
            };

            const hideModal = () => {
                const modalContent = modal.querySelector('div.relative.bg-gray-200');
                modalContent.classList.remove('opacity-100', 'scale-100');
                modalContent.classList.add('opacity-0', 'scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    currentAction = null;
                    modalMessage.textContent = "Are you sure you want to perform this action?";
                    setBtnState(modalConfirmBtn, true);

                    modalMessage.parentElement
                        .querySelectorAll('.custom-modal-btn')
                        .forEach(btn => btn.remove());

                    modalConfirmBtn.classList.remove('hidden');
                    modalCancelBtn.classList.remove('hidden');
                }, 150);
            };


            modalCancelBtn.addEventListener('click', hideModal);
            modalCloseBtn.addEventListener('click', hideModal);
            modalConfirmBtn.addEventListener('click', () => {
                if (currentAction) currentAction();
            });


            function setBtnState(btn, enabled) {
                if (!btn) return;
                btn.disabled = !enabled;
                btn.classList.toggle('opacity-50', !enabled);
                btn.classList.toggle('cursor-not-allowed', !enabled);
            }

            function updateButtonStates(data) {
                const servingEmpty = !data.servingQueue?.length;
                const reguEmpty = !data.upcomingRegu?.length;
                const prioEmpty = !data.upcomingPrio?.length;
                const returneeEmpty = !data.upcomingReturnee?.length;

                setBtnState(document.getElementById('skipBtn'), !servingEmpty);
                setBtnState(document.getElementById('recallBtn'), !servingEmpty);
                setBtnState(document.getElementById('deferBtn'), !servingEmpty);
                setBtnState(document.getElementById('proceedBtn'), !servingEmpty);

                setBtnState(document.getElementById('nextRegularBtn'), servingEmpty && !reguEmpty);
                setBtnState(document.getElementById('nextPriorityBtn'), servingEmpty && !prioEmpty);
                setBtnState(document.getElementById('returneeBtn'), servingEmpty && !returneeEmpty);

                const upcomingIds = ['upcomingRegu', 'upcomingPrio', 'upcomingReturnee', 'pendingRegu',
                    'pendingPrio', 'pendingReturnee'
                ];
                upcomingIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        if (servingEmpty) {
                            el.classList.remove('queue-disabled');
                        } else {
                            el.classList.add('queue-disabled');
                        }
                    }
                });
            }





            const renderQueue = (list, containerId) => {
                const container = document.querySelector(containerId);
                if (!container) return;

                if (!list?.length) {
                    container.innerHTML = `<div class="text-gray-400 text-center py-4 text-sm">🚫Empty</div>`;
                    return;
                }

                container.innerHTML = list.map(queue => `
    <div class="${queue.style_class} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold cursor-pointer"
         data-id="${queue.id}">
        ${queue.formatted_number}
    </div>
`).join('');

            };


            const fetchQueues = () => {
                fetch("{{ route('queues.data') }}", {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(async res => {
                        const text = await res.text();

                        if (!res.ok) {
                            if (res.status === 401) {
                                try {
                                    const data = JSON.parse(text);
                                    if (data.redirect) {
                                        console.warn("Session expired — redirecting...");
                                        window.location.href = data.redirect;
                                        return;
                                    }
                                } catch {
                                    window.location.href = "{{ route('login') }}";
                                    return;
                                }
                            }
                            console.error("Fetch failed:", res.status);
                            return;
                        }

                        try {
                            const data = JSON.parse(text);

                            renderQueue(data.upcomingRegu, '#upcomingRegu');
                            renderQueue(data.upcomingPrio, '#upcomingPrio');
                            renderQueue(data.upcomingReturnee, '#upcomingReturnee');
                            renderQueue(data.pendingRegu, '#pendingRegu');
                            renderQueue(data.pendingPrio, '#pendingPrio');
                            renderQueue(data.pendingReturnee, '#pendingReturnee');
                            renderQueue(data.deferred, '#deferred');
                            renderQueue(data.servingQueue, '#servingQueue');
                            updateButtonStates(data);

                        } catch (e) {
                            console.error("Invalid JSON:", e);
                        }
                    })
                    .catch(err => console.error("FetchQueues error:", err));
            };




            fetchQueues();
            setInterval(fetchQueues, 1000);

            const bindActionWithConfirm = (btnId, routeName, message) => {
                const btn = document.getElementById(btnId);
                if (!btn) return;

                btn.addEventListener('click', () => {
                    showModal(message, () => {
                        if (!routeName) return;
                        setBtnState(modalConfirmBtn, false);
                        fetch(routeName, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                fetchQueues();
                                setTimeout(hideModal, 1000);
                            })
                            .catch(err => {
                                console.error(err);
                                modalMessage.textContent =
                                    "❌ Something went wrong. Please try again.";
                                setBtnState(modalConfirmBtn, true);
                            });
                    });
                });
            };

            function bindPendingQueue(containerId, route, label) {
                document.addEventListener('click', e => {
                    const target = e.target.closest(`${containerId} [data-id]`);
                    if (target) {
                        const id = target.getAttribute('data-id');
                        modalMessage.textContent = "Serve again or proceed client to next step?";
                        const buttonContainer = modalMessage.parentElement;
                        buttonContainer.querySelectorAll('.custom-modal-btn').forEach(btn => btn.remove());
                        modalConfirmBtn.classList.add('hidden');
                        modalCancelBtn.classList.add('hidden');
                        const serveBtn = document.createElement('button');
                        serveBtn.textContent = "Serve";
                        serveBtn.className = `
                custom-modal-btn text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2
            `;
                        const proceedBtn = document.createElement('button');
                        proceedBtn.textContent = "Proceed";
                        proceedBtn.className = `
                custom-modal-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
                hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-green-300 
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2
            `;

                        serveBtn.addEventListener('click', () => {
                            setBtnState(serveBtn, false);
                            fetch("{{ route('queues.serveAgain') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: id,
                                        window_id: {{ Auth::user()->window_id }},
                                        queue_status: 'serving'
                                    })
                                })
                                .then(res => {
                                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                                    return res.json();
                                })
                                .then(() => {
                                    fetchQueues();
                                    setTimeout(hideModal, 1000);
                                })
                                .catch(err => {
                                    modalMessage.textContent =
                                        `❌ Something went wrong: ${err.message}`;
                                    setBtnState(serveBtn, true);
                                });
                        });

                        proceedBtn.addEventListener('click', () => {
                            setBtnState(proceedBtn, false);
                            fetch(route, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id
                                    })
                                })
                                .then(res => {
                                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                                    return res.json();
                                })
                                .then(() => {
                                    fetchQueues();
                                    setTimeout(hideModal, 1000);
                                })
                                .catch(err => {
                                    modalMessage.textContent =
                                        `❌ Something went wrong: ${err.message}`;
                                    setBtnState(proceedBtn, true);
                                });
                        });

                        buttonContainer.appendChild(serveBtn);
                        buttonContainer.appendChild(proceedBtn);

                        modal.classList.remove('hidden');
                        const modalContent = modal.querySelector('div.relative.bg-gray-200');
                        modalContent.classList.remove('opacity-0', 'scale-95');
                        modalContent.classList.add('opacity-100', 'scale-100');
                    }
                });
            }
            bindPendingQueue('#pendingRegu', "{{ route('queues.updatePendingRegu') }}", "Regular");
            bindPendingQueue('#pendingPrio', "{{ route('queues.updatePendingPrio') }}", "Priority");
            bindPendingQueue('#pendingReturnee', "{{ route('queues.updatePendingReturnee') }}", "Returnee");

            function bindUpcomingQueue(containerId) {
                document.addEventListener('click', e => {
                    const target = e.target.closest(`${containerId} [data-id]`);
                    if (!target) return;

                    const id = target.getAttribute('data-id');

                    const stepNumber = {{ $stepNumber ?? 'null' }};
                    const assignedCategory = "{{ strtolower(Auth::user()->assigned_category) }}";

                    if (stepNumber === 1) {
                        modalMessage.textContent = "Serve again or proceed client to next step?";
                        const buttonContainer = modalMessage.parentElement;

                        buttonContainer.querySelectorAll('.custom-modal-btn').forEach(btn => btn.remove());

                        modalConfirmBtn.classList.add('hidden');
                        modalCancelBtn.classList.add('hidden');

                        const serveBtn = document.createElement('button');
                        serveBtn.textContent = "Serve";
                        serveBtn.className = `
                custom-modal-btn text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2
            `;

                        const proceedBtn = document.createElement('button');
                        proceedBtn.textContent = "Proceed";
                        proceedBtn.className = `
                custom-modal-btn text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
                hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-green-300 
                font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2
            `;

                        serveBtn.addEventListener('click', () => {
                            setBtnState(serveBtn, false);
                            fetch("{{ route('queues.serveAgain') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: id,
                                        window_id: {{ Auth::user()->window_id }},
                                        queue_status: 'serving'
                                    })
                                })
                                .then(res => res.json())
                                .then(() => {
                                    fetchQueues();
                                    setTimeout(hideModal, 1000);
                                })
                                .catch(err => {
                                    modalMessage.textContent =
                                        `❌ Something went wrong: ${err.message}`;
                                    setBtnState(serveBtn, true);
                                });
                        });

                        proceedBtn.addEventListener('click', () => {
                            setBtnState(proceedBtn, false);
                            fetch("{{ route('queues.updateUpcomingPreassessRegu') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id
                                    })
                                })
                                .then(res => res.json())
                                .then(() => {
                                    fetchQueues();
                                    setTimeout(hideModal, 1000);
                                })
                                .catch(err => {
                                    modalMessage.textContent =
                                        `❌ Something went wrong: ${err.message}`;
                                    setBtnState(proceedBtn, true);
                                });
                        });

                        buttonContainer.appendChild(serveBtn);
                        buttonContainer.appendChild(proceedBtn);
                        modal.classList.remove('hidden');
                        const modalContent = modal.querySelector('div.relative.bg-gray-200');
                        modalContent.classList.remove('opacity-0', 'scale-95');
                        modalContent.classList.add('opacity-100', 'scale-100');
                    } else {
                        showModal("Start serving this queue?", () => {
                            setBtnState(modalConfirmBtn, false);
                            fetch("{{ route('queues.updateUpcoming') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: Number(id)
                                    })
                                })
                                .then(res => res.json())
                                .then(() => {
                                    fetchQueues();
                                    setTimeout(hideModal, 1000);
                                })
                                .catch(err => {
                                    console.error("Fetch error:", err);
                                    modalMessage.textContent =
                                        "❌ Something went wrong. Please try again.";
                                    setBtnState(modalConfirmBtn, true);
                                });
                        });
                    }
                });
            }


            bindUpcomingQueue('#upcomingRegu');
            bindUpcomingQueue('#upcomingPrio');
            bindUpcomingQueue('#upcomingReturnee');


            bindActionWithConfirm('nextRegularBtn', "{{ route('users.nextRegular') }}",
                "Proceed to the next Regular queue?");
            bindActionWithConfirm('nextPriorityBtn', "{{ route('users.nextPriority') }}",
                "Proceed to the next Priority queue?");
            bindActionWithConfirm('returneeBtn', "{{ route('users.nextReturnee') }}",
                "Proceed to the next Returnee queue?");
            bindActionWithConfirm('skipBtn', "{{ route('users.skipQueue') }}",
                "Are you sure you want to skip this queue?");
            bindActionWithConfirm('recallBtn', "{{ route('users.recallQueue') }}",
                "Are you sure you want to recall this queue?");
            bindActionWithConfirm('deferBtn', "{{ route('users.returnQueue') }}",
                "Mark the current serving client as returnee?");
            bindActionWithConfirm('proceedBtn', "{{ route('users.proceedQueue') }}",
                "Proceed with the current queue?");
        });
    </script>
    <script>
        setInterval(async () => {
            try {
                const res = await fetch("{{ route('session.check') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;
                const data = await res.json();

                if (!data.active) {
                    window.location.href = "{{ route('login') }}";
                }
            } catch (err) {
                console.warn('Session check failed:', err);
            }
        }, 5000);
    </script>
    {{-- ===================== JS SAFEGUARD ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionId = {{ $sectionId ?? 'null' }};
            const stepId = {{ $stepId ?? 'null' }};
            const crisisSectionId = {{ $crisisSectionId ?? 'null' }};
            const preAssessmentId = {{ $preAssessmentId ?? 'null' }};
            const encodingId = {{ $encodingId ?? 'null' }};

            // Safety check to ensure Defer button is hidden for restricted steps
            if (sectionId === crisisSectionId && (stepId === preAssessmentId || stepId === encodingId)) {
                const deferBtn = document.getElementById('deferBtn');
                if (deferBtn) deferBtn.style.display = 'none';
            }
        });
    </script>

    <style>
        #confirmModal>div {
            @apply transform transition-all duration-150 ease-out opacity-0 scale-95;
        }

        #confirmModal.hidden>div {
            @apply opacity-0 scale-95;
        }

        #confirmModal:not(.hidden)>div {
            @apply opacity-100 scale-100;
        }

        .queue-disabled {
            pointer-events: none;
            opacity: 0.5;
            filter: grayscale(50%);
            position: relative;
        }

        /* Optional subtle overlay (visual cue) */
        .queue-disabled::after {
            /* content: "Disabled while serving"; */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.8);
            color: #444;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
@endsection
