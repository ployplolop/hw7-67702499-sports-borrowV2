<?php require __DIR__ . '/../views/layout/header.php'; ?>

<?php
require_once __DIR__ . '/../config/database.php';
$pdo = Database::connect();

// Fetch all borrow records for calendar
$borrows = $pdo->query("
    SELECT br.id, br.borrow_date, br.due_date, br.return_date, br.status, br.quantity,
           u.full_name AS user_name, e.name AS equipment_name,
           DATEDIFF(br.due_date, NOW()) AS days_left
    FROM borrow_records br
    JOIN users u ON u.id = br.user_id
    JOIN equipment e ON e.id = br.equipment_id
    ORDER BY br.borrow_date DESC
")->fetchAll();

// Build FullCalendar events JSON
$events = [];
foreach ($borrows as $b) {
    $isOverdue = $b['status'] === 'borrowed' && strtotime($b['due_date']) < time();
    $isReturned = $b['status'] === 'returned';

    if ($isReturned) {
        $color = '#22c55e'; // green
    } elseif ($isOverdue) {
        $color = '#ef4444'; // red
    } else {
        $color = '#3b82f6'; // blue
    }

    // Borrow start event
    $events[] = [
        'title' => '📤 ' . $b['equipment_name'] . ' (' . $b['user_name'] . ')',
        'start' => $b['borrow_date'],
        'color' => $color,
        'extendedProps' => [
            'type' => 'borrow',
            'id' => $b['id'],
            'equipment' => $b['equipment_name'],
            'user' => $b['user_name'],
            'qty' => $b['quantity'],
            'status' => $b['status'],
            'borrow_date' => $b['borrow_date'],
            'due_date' => $b['due_date'],
            'return_date' => $b['return_date'],
        ],
    ];

    // Due date event
    $events[] = [
        'title' => '📅 Due: ' . $b['equipment_name'],
        'start' => $b['due_date'],
        'color' => $isOverdue ? '#ef4444' : '#f59e0b',
        'extendedProps' => [
            'type' => 'due',
            'id' => $b['id'],
            'equipment' => $b['equipment_name'],
            'user' => $b['user_name'],
            'qty' => $b['quantity'],
            'status' => $b['status'],
            'borrow_date' => $b['borrow_date'],
            'due_date' => $b['due_date'],
            'return_date' => $b['return_date'],
        ],
    ];

    // Return event (if returned)
    if ($b['return_date']) {
        $events[] = [
            'title' => '✅ Returned: ' . $b['equipment_name'],
            'start' => $b['return_date'],
            'color' => '#22c55e',
            'extendedProps' => [
                'type' => 'return',
                'id' => $b['id'],
                'equipment' => $b['equipment_name'],
                'user' => $b['user_name'],
                'status' => 'returned',
            ],
        ];
    }
}
$eventsJson = json_encode($events, JSON_UNESCAPED_UNICODE);
?>

<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<style>
    .cal-page h2 {
        display: flex; align-items: center; gap: 10px;
        font-size: 1.3rem; color: #1e293b; margin-bottom: 20px;
    }
    .cal-legend {
        display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .cal-legend-item {
        display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #475569;
    }
    .cal-legend-dot {
        width: 12px; height: 12px; border-radius: 4px;
    }
    .cal-container {
        background: #fff; border-radius: 14px; padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    /* FullCalendar overrides */
    .fc { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .fc .fc-toolbar-title { font-size: 1.2rem !important; font-weight: 700; color: #1e293b; }
    .fc .fc-button {
        background: #f1f5f9 !important; border: 1px solid #e2e8f0 !important;
        color: #475569 !important; font-weight: 600 !important; font-size: .85rem !important;
        border-radius: 8px !important; padding: 6px 14px !important;
        text-transform: capitalize !important;
    }
    .fc .fc-button:hover {
        background: #e2e8f0 !important; color: #1e293b !important;
    }
    .fc .fc-button-active {
        background: #3b82f6 !important; color: #fff !important;
        border-color: #3b82f6 !important;
    }
    .fc .fc-daygrid-day-number { font-size: .85rem; color: #475569; font-weight: 500; }
    .fc .fc-col-header-cell-cushion { font-size: .82rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
    .fc .fc-event {
        border: none !important; border-radius: 6px !important;
        padding: 2px 6px !important; font-size: .78rem !important;
        font-weight: 500 !important; cursor: pointer !important;
    }
    .fc .fc-daygrid-day.fc-day-today {
        background: rgba(59,130,246,.05) !important;
    }

    /* Event Detail Modal */
    .cal-modal-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,.4); z-index: 500;
        justify-content: center; align-items: center;
    }
    .cal-modal-overlay.active { display: flex; }
    .cal-modal {
        background: #fff; border-radius: 16px; padding: 28px 32px;
        width: 420px; max-width: 90vw;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        animation: calModalIn .25s ease;
    }
    @keyframes calModalIn {
        from { transform: scale(.9) translateY(20px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }
    .cal-modal .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;
    }
    .cal-modal .modal-header h3 { font-size: 1.05rem; color: #1e293b; }
    .cal-modal .close-btn {
        width: 32px; height: 32px; border-radius: 8px; border: none;
        background: #f1f5f9; cursor: pointer; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .cal-modal .close-btn:hover { background: #e2e8f0; }
    .cal-modal .detail-row {
        display: flex; justify-content: space-between; padding: 8px 0;
        font-size: .9rem;
    }
    .cal-modal .detail-label { color: #64748b; }
    .cal-modal .detail-value { font-weight: 600; color: #1e293b; }
    .cal-modal .status-badge {
        padding: 4px 14px; border-radius: 20px; font-size: .8rem; font-weight: 600; color: #fff;
    }
    .status-borrowed  { background: #f59e0b; }
    .status-returned  { background: #22c55e; }
    .status-overdue   { background: #ef4444; }
</style>

<div class="cal-page">
    <h2>
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
        Borrow Calendar
    </h2>

    <div class="cal-legend">
        <div class="cal-legend-item"><div class="cal-legend-dot" style="background:#3b82f6;"></div> Active Borrow</div>
        <div class="cal-legend-item"><div class="cal-legend-dot" style="background:#f59e0b;"></div> Due Date</div>
        <div class="cal-legend-item"><div class="cal-legend-dot" style="background:#ef4444;"></div> Overdue</div>
        <div class="cal-legend-item"><div class="cal-legend-dot" style="background:#22c55e;"></div> Returned</div>
    </div>

    <div class="cal-container">
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="cal-modal-overlay" id="calModal">
    <div class="cal-modal">
        <div class="modal-header">
            <h3 id="modalTitle">Event Detail</h3>
            <button class="close-btn" onclick="closeCalModal()">✕</button>
        </div>
        <div id="modalBody"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calEl = document.getElementById('calendar');
    var events = <?= $eventsJson ?>;

    var calendar = new FullCalendar.Calendar(calEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,listWeek'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        },
        events: events,
        eventClick: function(info) {
            var p = info.event.extendedProps;
            var statusClass = p.status;
            if (p.status === 'borrowed' && new Date(p.due_date) < new Date()) {
                statusClass = 'overdue';
            }

            document.getElementById('modalTitle').innerHTML =
                (p.type === 'borrow' ? '📤 Borrow' : p.type === 'due' ? '📅 Due' : '✅ Return') +
                ' — ' + (p.equipment || '');

            var html = '';
            html += '<div class="detail-row"><span class="detail-label">Equipment</span><span class="detail-value">' + (p.equipment || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="detail-label">Borrower</span><span class="detail-value">' + (p.user || '-') + '</span></div>';
            if (p.qty) html += '<div class="detail-row"><span class="detail-label">Quantity</span><span class="detail-value">' + p.qty + '</span></div>';
            html += '<div class="detail-row"><span class="detail-label">Borrow Date</span><span class="detail-value">' + (p.borrow_date || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="detail-label">Due Date</span><span class="detail-value">' + (p.due_date || '-') + '</span></div>';
            if (p.return_date) html += '<div class="detail-row"><span class="detail-label">Return Date</span><span class="detail-value">' + p.return_date + '</span></div>';
            html += '<div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="status-badge status-' + statusClass + '">' + statusClass.charAt(0).toUpperCase() + statusClass.slice(1) + '</span></span></div>';

            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('calModal').classList.add('active');
        },
        height: 'auto',
        dayMaxEvents: 3,
        eventDisplay: 'block',
    });

    calendar.render();
});

function closeCalModal() {
    document.getElementById('calModal').classList.remove('active');
}
document.getElementById('calModal').addEventListener('click', function(e) {
    if (e.target === this) closeCalModal();
});
</script>

<?php require __DIR__ . '/../views/layout/footer.php'; ?>
