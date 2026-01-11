<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Aktyvūs bilietai
        </h2>
    </x-slot>

    <style>
        .modal-enter {
            opacity: 0;
            transform: scale(0.95);
        }
        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 150ms ease-out, transform 150ms ease-out;
        }
        .modal-leave {
            opacity: 1;
            transform: scale(1);
        }
        .modal-leave-active {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 150ms ease-in, transform 150ms ease-in;
        }

        .dragging {
            opacity: 0.5;
            transform: scale(0.97);
            transition: transform 100ms ease, opacity 100ms ease;
        }

        .modal-prio-red {
            border-left: 6px solid #dc2626;
            background-color: rgba(254, 226, 226, 0.95);
        }
        .modal-prio-orange {
            border-left: 6px solid #ea580c;
            background-color: rgba(255, 237, 213, 0.95);
        }
        .modal-prio-yellow {
            border-left: 6px solid #ca8a04;
            background-color: rgba(254, 249, 195, 0.95);
        }
        .modal-prio-green {
            border-left: 6px solid #16a34a;
            background-color: rgba(220, 252, 231, 0.95);
        }
        .modal-prio-default {
            border-left: 6px solid #6b7280;
            background-color: rgba(243, 244, 246, 0.95);
        }
    </style>

    {{-- PAGRINDINIS --}}
    <div class="p-6">

        {{-- Filtravimo mygtukai ir admin įrankiai --}}
        <div class="flex gap-2 mb-4">
            <a href="{{ route('aktyvus', ['mano' => $rodytiTikMano ? 0 : 1]) }}"
               class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
                {{ $rodytiTikMano ? 'Rodyti visus bilietus' : 'Rodyti tik mano bilietus' }}
            </a>

            @if(auth()->user()->name === 'admin')
                <a href="{{ route('aktyvus.ataskaita') }}"
                   class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-600">
                    Aktyvių bilietų ataskaita
                </a>

                <button onclick="openEmailModal()"
                        class="px-4 py-2 bg-indigo-700 text-white rounded hover:bg-indigo-600">
                    Siųsti ataskaitą el. paštu
                </button>
            @endif
        </div>

        {{-- Bilietų lentelė --}}
        @php
            function prioritetas_klase($prioritetas) {
                return match($prioritetas) {
                    'Labai skubus' => 'border-l-4 border-red-600 bg-red-50 dark:bg-red-900/30',
                    'Skubus'       => 'border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900/30',
                    'Vidutinis'    => 'border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/30',
                    'Žemas'        => 'border-l-4 border-green-500 bg-green-50 dark:bg-green-900/30',
                    default        => 'border-l-4 border-gray-400 bg-white dark:bg-gray-700',
                };
            }
        @endphp

        {{-- Trijų stulpelių gridas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            {{-- Laukiama --}}
            <div data-column="Laukiama"
                 class="bg-gray-100 dark:bg-gray-800 p-4 rounded min-h-[200px]"
                 ondrop="drop(event)" ondragover="allowDrop(event)">

                <h3 class="text-lg font-bold mb-3">
                    Laukiama ({{ count($bilietai['Laukiama'] ?? []) }})
                </h3>

                @foreach($bilietai['Laukiama'] ?? [] as $b)
                    <div draggable="{{ auth()->user()->name === 'admin' ? 'true' : 'false' }}"
                         ondragstart="drag(event)"
                         ondragend="dragEnd(event)"
                         data-id="{{ $b->id }}"
                         data-statusas="Laukiama"
                         onclick='openModal(this, @json($b))'
                         class="ticket p-3 mb-3 rounded shadow cursor-pointer transition-transform duration-150 hover:scale-[1.01] {{ prioritetas_klase($b->prioritetas) }}">

                        <div class="font-bold">#{{ $b->bilieto_id }}</div>
                        <div class="font-semibold">{{ $b->pavadinimas }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $b->uzregistruota }}</div>
                        <div class="text-xs mt-1 text-gray-600 dark:text-gray-300">
                            {{ $b->prioritetas }} • {{ $b->kategorija }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Vykdoma --}}
            <div data-column="Vykdoma"
                 class="bg-gray-100 dark:bg-gray-800 p-4 rounded min-h-[200px]"
                 ondrop="drop(event)" ondragover="allowDrop(event)">

                <h3 class="text-lg font-bold mb-3">
                    Vykdoma ({{ count($bilietai['Vykdoma'] ?? []) }})
                </h3>

                @foreach($bilietai['Vykdoma'] ?? [] as $b)
                    <div draggable="{{ auth()->user()->name === 'admin' ? 'true' : 'false' }}"
                         ondragstart="drag(event)"
                         ondragend="dragEnd(event)"
                         data-id="{{ $b->id }}"
                         data-statusas="Vykdoma"
                         onclick='openModal(this, @json($b))'
                         class="ticket p-3 mb-3 rounded shadow cursor-pointer transition-transform duration-150 hover:scale-[1.01] {{ prioritetas_klase($b->prioritetas) }}">

                        <div class="font-bold">#{{ $b->bilieto_id }}</div>
                        <div class="font-semibold">{{ $b->pavadinimas }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $b->uzregistruota }}</div>
                        <div class="text-xs mt-1 text-gray-600 dark:text-gray-300">
                            {{ $b->prioritetas }} • {{ $b->kategorija }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Įvykdyta --}}
            <div data-column="Įvykdyta"
                 class="bg-gray-100 dark:bg-gray-800 p-4 rounded min-h-[200px]"
                 ondrop="drop(event)" ondragover="allowDrop(event)">

                <h3 class="text-lg font-bold mb-3">
                    Įvykdyta ({{ count($bilietai['Įvykdyta'] ?? []) }})
                </h3>

                @foreach($bilietai['Įvykdyta'] ?? [] as $b)
                    <div draggable="{{ auth()->user()->name === 'admin' ? 'true' : 'false' }}"
                         ondragstart="drag(event)"
                         ondragend="dragEnd(event)"
                         data-id="{{ $b->id }}"
                         data-statusas="Įvykdyta"
                         onclick='openModal(this, @json($b))'
                         class="ticket p-3 mb-3 rounded shadow cursor-pointer transition-transform duration-150 hover:scale-[1.01] {{ prioritetas_klase($b->prioritetas) }}">

                        <div class="font-bold">#{{ $b->bilieto_id }}</div>
                        <div class="font-semibold">{{ $b->pavadinimas }}</div>

                        <div class="ivykdyta-laikas text-xs text-gray-500 mt-1">
                            Užregistruota: {{ $b->uzregistruota }}
                        </div>

                        @if($b->uzdaryta)
                            <div class="ivykdyta-laikas text-xs text-gray-500 mt-1">
                                Uždaryta: {{ \Carbon\Carbon::parse($b->uzdaryta)->format('Y-m-d H:i:s') }}
                            </div>
                        @endif

                        <div class="text-xs mt-1 text-gray-600 dark:text-gray-300">
                            {{ $b->prioritetas }} • {{ $b->kategorija }}
                        </div>

                        @if(auth()->user()->name === 'admin' || (auth()->id() === $b->user_id && $b->statusas === 'Įvykdyta'))
                            <div class="ivykdyta-komentaras text-xs text-gray-500 mt-1">
                                Komentaras: {{ $b->komentaras }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div> 
    </div> 

    {{-- Bilieto modalas --}}
    <div id="modal-backdrop"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center"
        onclick="closeModal()">

        <div id="modal-content"
            class="modal-enter p-6 rounded shadow max-w-lg w-full relative modal-prio-default"
            onclick="event.stopPropagation()">

            <button class="absolute top-2 right-2 text-xl" onclick="closeModal()">×</button>

            <h2 id="m_pavadinimas" class="text-2xl font-bold mb-4"></h2>

            <p><strong>ID:</strong> <span id="m_id"></span></p>
            <p><strong>Prioritetas:</strong> <span id="m_prioritetas"></span></p>
            <p><strong>Kategorija:</strong> <span id="m_kategorija"></span></p>
            <p><strong>Aprašymas:</strong></p>
            <p id="m_aprasymas" class="mb-4"></p>

            <div id="m_veiksmai" class="mt-4 hidden">
                <div class="flex gap-2">

                    <a id="m_edit" href="#"
                    class="flex-1 px-3 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-500 hidden">
                        Redaguoti
                    </a>

                    <button id="m_delete"
                            class="flex-1 px-3 py-2 text-xs text-white bg-red-600 rounded hover:bg-red-500">
                        Ištrinti
                    </button>

                    <button id="m_comment"
                            class="flex-1 px-3 py-2 text-xs text-white bg-green-600 rounded hover:bg-green-500 hidden">
                        Pridėti komentarą
                    </button>

                </div>
            </div>

            <div id="m_komentaras_box" class="hidden mt-4">
                <p><strong>Įvykdymo komentaras:</strong></p>
                <p id="m_komentaras" class="italic text-gray-700 dark:text-gray-300"></p>
            </div>

        </div>
    </div>

    {{-- Komentarų modalas --}}
    <div id="comment-modal"
        class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center"
        onclick="closeCommentModal()">

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow w-80"
            onclick="event.stopPropagation()">

            <h3 class="text-lg font-bold mb-2">Pridėti komentarą</h3>

            <textarea id="comment-text"
                    class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"
                    rows="3"
                    placeholder="Įveskite komentarą..."></textarea>

            <div class="flex justify-end gap-2 mt-3">
                <button onclick="submitComment()"
                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500">
                    Komentuoti ir uždaryti
                </button>
            </div>
        </div>
    </div>

    {{-- El. pašto modalas --}}
    <div id="email-modal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        onclick="closeEmailModal()">

        <div class="bg-white p-6 rounded shadow max-w-md w-full relative"
            onclick="event.stopPropagation()">

            <button class="absolute top-2 right-2 text-xl" onclick="closeEmailModal()">×</button>

            <h2 class="text-xl font-bold mb-4">Siųsti ataskaitą el. paštu</h2>

            <input type="email" id="email-input"
                class="w-full px-3 py-2 border rounded mb-4"
                placeholder="Įveskite el. pašto adresą">

            <button onclick="sendReportEmail()"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                Siųsti
            </button>
        </div>
    </div>

    {{-- *************************** JavaScript kodas *************************** --}}
    <script>
    // Patikrinama, ar prisijungęs vartotojas yra administratorius
    const isAdmin = "{{ auth()->user()->name }}" === "admin";
     
    // Pagal prioritetą grąžina CSS klasę, kuri nuspalvina modalą atitinkama spalva
    function modalPriorityClass(prioritetas) {
        switch (prioritetas) {
            case 'Labai skubus': return 'modal-prio-red';
            case 'Skubus': return 'modal-prio-orange';
            case 'Vidutinis': return 'modal-prio-yellow';
            case 'Žemas': return 'modal-prio-green';
            default: return 'modal-prio-default';
        }
    }

    // Kintamasis, kuriame saugomas šiuo metu tempiamas ticket elementas
    let dragged;

    // Drag and Drop funkcijos veikia tik adminui
    function drag(ev) {
        if (!isAdmin) return;
        dragged = ev.target.closest('.ticket');
        dragged.classList.add('dragging');
    }

    // Drag pabaiga
    function dragEnd(ev) {
        const el = ev.target.closest('.ticket');
        if (el) el.classList.remove('dragging');
    }

    // leidžia "numesti" kortelę į stulepelį    
    function allowDrop(ev) {
        if (!isAdmin) return;
        ev.preventDefault();
    }

    // Paleidžiama, kai ticket numetamas į naują stulpelį, atnaujiną statusą tiek vizualiai, tiek db
    function drop(ev) {
        if (!isAdmin) return;
        ev.preventDefault();

        // Randamas stulpelis, ant kurio numestas bilietas
        const column = ev.target.closest("[data-column]");
        if (!column) return;

        // Naujas statusas paimamas iš data-column atributo
        const statusas = column.dataset.column;

        // tempiamas bilietas
        const ticket = dragged;
        if (!ticket) return;

        // atnaujintamas statuso atributas
        ticket.dataset.statusas = statusas;

        // bilietas fiziškai perkeltas į naują stulpelį
        column.appendChild(ticket);

        // jei bilietas perkeltas į "Įvykdyta", pridedami uždarymo laikas ir komentaras 
        if (statusas === "Vykdoma") {
            const timeBox = ticket.querySelector('.ivykdyta-laikas');
            if (timeBox) timeBox.remove();

            const commentBox = ticket.querySelector('.ivykdyta-komentaras');
            if (commentBox) commentBox.remove();
        }

        // atnaujinami bilietų kiekiai stulpeliuose
        updateColumnCounts();

        // siunčiantas užklausą serveriui atnaujinti bilieto statusą duomenų bazėje
        fetch("{{ route('keistiStatusa') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id: parseInt(ticket.dataset.id),
                statusas: statusas
            })
        }).catch(console.error);
    }

    // atveria bilieto modalą ir užpildo duomenim
    function openModal(element, b) {
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');

        // rodo modalą
        backdrop.classList.remove('hidden');
        backdrop.classList.add('flex');

        //paleidžia atidarymo animaciją 
        content.classList.remove('modal-leave', 'modal-leave-active');
        content.classList.add('modal-enter');
        requestAnimationFrame(() => {
            content.classList.add('modal-enter-active');
        });

        const statusas = element.dataset.statusas;
        const isOwner = b.user_id === {{ auth()->id() }};

        // Užpildom tekstus
        document.getElementById('m_id').innerText = b.bilieto_id;
        document.getElementById('m_pavadinimas').innerText = b.pavadinimas;
        document.getElementById('m_prioritetas').innerText = b.prioritetas;
        document.getElementById('m_kategorija').innerText = b.kategorija;
        document.getElementById('m_aprasymas').innerText = b.aprasymas;

        // Prioriteto spalvos
        content.classList.remove(
            'modal-prio-red',
            'modal-prio-orange',
            'modal-prio-yellow',
            'modal-prio-green',
            'modal-prio-default'
        );
        content.classList.add(modalPriorityClass(b.prioritetas));

        // Mygtukai
        const veiksmai = document.getElementById('m_veiksmai');
        const editBtn = document.getElementById('m_edit');
        const deleteBtn = document.getElementById('m_delete');
        const commentBtn = document.getElementById('m_comment');

        //default - paslepia visus mygtukus
        veiksmai.classList.add('hidden');
        editBtn.classList.add('hidden');
        commentBtn.classList.add('hidden');

        // ADMIN LOGIKA
        if (isAdmin) {
            veiksmai.classList.remove('hidden');

            //admin gali redaguoti visus bilietus kol jie vykdomi
            if (statusas === "Vykdoma") {
                commentBtn.classList.remove('hidden');
                commentBtn.onclick = function () {
                    openCommentModal(b.id);
                };
            }
        }

        // PAPRASTAS VARTOTOJAS ***********
        else if (statusas === "Laukiama" && isOwner) {
            veiksmai.classList.remove('hidden');
            editBtn.classList.remove('hidden');
        }

        // DELETE mygtukas visiems
        deleteBtn.onclick = function () {
            if (confirm("Ar tikrai norite ištrinti šį bilietą?")) {
                fetch(b.id + "/istrinti", {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                })
                .then(res => {
                    if (res.ok) {
                        const card = document.querySelector('.ticket[data-id="' + b.id + '"]');
                        if (card) card.remove();
                        updateColumnCounts();
                        closeModal();
                    }
                })
                .catch(console.error);
            }
        };

        // Redagavimo nuoroda
        editBtn.href = window.location.origin + "/" + b.id + "/redaguoti";

        // Rodyti komentarą tik jei bilietas įvykdytas ir vartotojas yra autorius
        if (b.statusas === "Įvykdyta" && isOwner) {
            document.getElementById('m_komentaras').innerText = b.komentaras ?? "Komentaro nėra";
            document.getElementById('m_komentaras_box').classList.remove('hidden');
        } else {
            document.getElementById('m_komentaras_box').classList.add('hidden');
        }
    }

    // uždaro bilieto modalą su animacija
    function closeModal() {
        const backdrop = document.getElementById('modal-backdrop');
        const content = document.getElementById('modal-content');

        // paleidžia uždarymo animaciją
        content.classList.remove('modal-enter', 'modal-enter-active');
        content.classList.add('modal-leave');

        // uždarymo animacija
        requestAnimationFrame(() => {
            content.classList.add('modal-leave-active');
        });

        // po 150ms paslepia modalą
        setTimeout(() => {
            backdrop.classList.add('hidden');
            backdrop.classList.remove('flex');
            content.classList.remove('modal-leave', 'modal-leave-active');
        }, 150);
    }

    // atnaujina bilietų skaičių kiekviename stulpelyje
    function updateColumnCounts() {
        const columns = document.querySelectorAll('[data-column]');
        columns.forEach(col => {
            const status = col.dataset.column;
            const count = col.querySelectorAll('.ticket').length;
            const title = col.querySelector('h3');
            title.innerHTML = `${status} (${count})`;
        });
    }


    // KOMENTARŲ MODALAS
    // Laikomas bilieto ID, kuriam šiuo metu rašomas komentaras
    let currentCommentTicketId = null;

    //Atidaro komentaro modalą ir išsaugo bilieto ID, kad žinotume, kuriam bilietui komentaras priklauso
    function openCommentModal(ticketId) {
        currentCommentTicketId = ticketId;

        const modal = document.getElementById('comment-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    //Uždaro komentaro modalą ir išvalo įvedimo lauką
    function closeCommentModal() {
        const modal = document.getElementById('comment-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.getElementById('comment-text').value = "";
    }

    //Išsiunčia komentarą į db ir atnaujina bilieto kortelę
    function submitComment() {
        const text = document.getElementById('comment-text').value.trim();
        if (!text) return alert("Įveskite komentarą.");

        fetch("/komentaras/" + currentCommentTicketId, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ komentaras: text })
        })
        .then(async res => {
            const data = await res.json();

            if (res.ok) {
                const card = document.querySelector('.ticket[data-id="' + currentCommentTicketId + '"]');
                const doneColumn = document.querySelector('[data-column="Įvykdyta"]');

                if (card && doneColumn) {

                    // Pašaliname senus laikus ir komentarus, jei buvo
                    let oldTime = card.querySelector('.ivykdyta-laikas');
                    if (oldTime) oldTime.remove();

                    let oldComment = card.querySelector('.ivykdyta-komentaras');
                    if (oldComment) oldComment.remove();

                    //Suformatuoja datą į YYYY-MM-DD HH:MM:SS
                    function formatDateDash(dateStr) {
                        const d = new Date(dateStr);

                        const yyyy = d.getUTCFullYear();
                        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
                        const dd = String(d.getUTCDate()).padStart(2, '0');

                        // Konvertuojame į LT laiką UTC+2
                        let hours = d.getUTCHours() + 2;
                        if (hours >= 24) hours -= 24;

                        const hh = String(hours).padStart(2, '0');
                        const min = String(d.getUTCMinutes()).padStart(2, '0');
                        const ss = String(d.getUTCSeconds()).padStart(2, '0');

                        return `${yyyy}-${mm}-${dd} ${hh}:${min}:${ss}`;
                    }

                    // Sukuriame naują laiko bloką
                    let timeBox = document.createElement('div');
                    timeBox.className = "ivykdyta-laikas text-xs text-gray-500 mt-1";
                    timeBox.innerHTML = `
                        Užregistruota: ${formatDateDash(data.uzregistruota)}<br>
                        Uždaryta: ${formatDateDash(data.uzdaryta)}
                    `;
                    card.appendChild(timeBox);

                    // Sukuriame naują komentaro bloką
                    let commentBox = document.createElement('div');
                    commentBox.className = "ivykdyta-komentaras text-xs text-gray-500 mt-1";
                    commentBox.innerText = `Komentaras: ${data.komentaras}`;
                    card.appendChild(commentBox);

                    // Perkeliame bilietą į "Įvykdyta" stulpelį
                    card.dataset.statusas = "Įvykdyta";
                    doneColumn.appendChild(card);

                    // Atnaujiname stulpelių skaičius
                    updateColumnCounts();
                }

                // Uždaryti abu modalus
                closeCommentModal();
                closeModal();
            }
        })
        .catch(console.error);
    }

    //EL. PAŠTO MODALAS 

    // Atidaro el. pašto modalą
    function openEmailModal() {
        document.getElementById('email-modal').classList.remove('hidden');
        document.getElementById('email-modal').classList.add('flex');
    }
    // Uždaro el. pašto modalą
    function closeEmailModal() {
        document.getElementById('email-modal').classList.add('hidden');
        document.getElementById('email-modal').classList.remove('flex');
    }
    // Siunčia ataskaitą el. paštu
    function sendReportEmail() {
        const email = document.getElementById('email-input').value;

        // Validacija
        if (!email || !email.includes('@')) {
            alert('Įveskite galiojantį el. pašto adresą.');
            return;
        }

        // siunčia užklausą serveriui
        fetch('/ataskaita/siusti', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email })
        })
        .then(res => {
            // jei gražinio OK, rodom sėkmės pranešimą
            if (res.ok) {
                alert('Ataskaita išsiųsta!');
                closeEmailModal();
            } else {
                alert('Nepavyko išsiųsti ataskaitos.');
            }
        })
        //klaidos gaudymas
        .catch(() => alert('Klaida siunčiant ataskaitą.'));
    }
    </script>
</x-app-layout>
