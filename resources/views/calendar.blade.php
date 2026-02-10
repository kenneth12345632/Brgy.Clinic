@extends('layouts.app')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Clinic Events</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage programs, meetings, and community activities</p>
        </div>
        <button onclick="openModalForCreate()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl font-bold flex items-center gap-3 shadow-xl shadow-blue-200 transition-all active:scale-95">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Add New Event</span>
        </button>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div id='calendar' data-events='@json($appointments)'></div>
    </div>
</div>

<div id="eventModal" class="fixed inset-0 z-[100] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-md">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
            <div class="bg-blue-600 p-10 text-white relative">
                <h3 id="modalTitle" class="text-3xl font-bold">Schedule Event</h3>
                <p id="modalSubtitle" class="text-blue-100 opacity-80 mt-1">Enter the details for the activity</p>
                <button onclick="closeModal()" class="absolute top-8 right-8 text-white/40 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form id="eventForm" action="{{ route('appointments.store') }}" method="POST" class="p-10 space-y-6">
                @csrf
                <div id="methodField"></div> {{-- For spoofing PUT/PATCH if needed --}}
                
                <input type="hidden" name="id" id="event_id">

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Event Title / Description</label>
                    <input type="text" name="service_type" id="service_type" placeholder="e.g. Feeding Program" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Date</label>
                        <input type="date" name="appointment_date" id="appointment_date" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Time</label>
                        <input type="time" name="appointment_time" id="appointment_time" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <input type="hidden" name="status" value="confirmed">
                <input type="hidden" name="patient_id" value="">

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" id="submitBtn" class="w-full py-5 rounded-[1.5rem] bg-blue-600 text-white font-bold text-lg shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98]">
                        Save Event
                    </button>
                    
                    <button type="button" id="deleteBtn" onclick="handleDelete()" class="hidden w-full py-4 rounded-[1.5rem] bg-red-50 text-red-600 font-bold border border-red-100 hover:bg-red-100 transition-all">
                        Delete Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const rawData = JSON.parse(calendarEl.getAttribute('data-events') || '[]');
        
        const eventData = rawData.map(function(app) {
            return {
                id: app.id,
                title: app.service_type,
                start: app.appointment_date + 'T' + app.appointment_time,
                color: '#9333ea',
                allDay: false,
                extendedProps: {
                    date: app.appointment_date,
                    time: app.appointment_time
                }
            };
        });

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            height: 'auto',
            events: eventData,
            eventClick: function(info) {
                openModalForEdit(info.event);
            }
        });
        
        calendar.render();
    });

    function openModalForCreate() {
        const form = document.getElementById('eventForm');
        form.reset();
        form.action = "{{ route('appointments.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').innerText = "Schedule Event";
        document.getElementById('submitBtn').innerText = "Save Event to Calendar";
        document.getElementById('deleteBtn').classList.add('hidden');
        document.getElementById('eventModal').classList.remove('hidden');
    }

    function openModalForEdit(event) {
        const form = document.getElementById('eventForm');
        document.getElementById('modalTitle').innerText = "Edit Event";
        document.getElementById('submitBtn').innerText = "Update Changes";
        
        // Fill Data
        document.getElementById('event_id').value = event.id;
        document.getElementById('service_type').value = event.title;
        document.getElementById('appointment_date').value = event.extendedProps.date;
        document.getElementById('appointment_time').value = event.extendedProps.time;

        // Set action to update route (Assumes your route is /appointments/{id})
        form.action = `/appointments/${event.id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        document.getElementById('deleteBtn').classList.remove('hidden');
        document.getElementById('eventModal').classList.remove('hidden');
    }

    function handleDelete() {
        const id = document.getElementById('event_id').value;
        if (confirm('Are you sure you want to delete this event?')) {
            const deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = `/appointments/${id}`;
            deleteForm.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(deleteForm);
            deleteForm.submit();
        }
    }

    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        let modal = document.getElementById('eventModal');
        if (event.target == modal) closeModal();
    }
</script>

<style>
    .fc { --fc-border-color: #f1f5f9; font-family: inherit; }
    .fc .fc-toolbar-title { font-size: 1.5rem; font-weight: 800; color: #1e293b; }
    .fc .fc-button-primary { background: white; border: 1px solid #e2e8f0; color: #475569; font-weight: 700; border-radius: 0.75rem; }
    .fc .fc-button-primary:hover { background: #f8fafc; color: #1e293b; }
    .fc .fc-button-active { background: #2563eb !important; color: white !important; border-color: #2563eb !important; }
    .fc-event { cursor: pointer; border-radius: 6px; padding: 4px 8px; font-weight: 600; border: none !important; margin-bottom: 2px; }
</style>
@endsection