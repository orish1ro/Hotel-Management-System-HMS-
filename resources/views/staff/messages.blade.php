<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Messages - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <style>
        body {
            padding-left: 0 !important;
            overflow: hidden !important;
            display: flex;
            height: 100vh;
        }

        .messages-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .guest-panel {
            width: 280px;
            min-width: 280px;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 8px rgba(0,0,0,0.03);
        }

        .guest-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        #guest-list {
            flex: 1;
            overflow-y: auto;
        }

        .guest-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.15s;
        }

        .guest-item:hover { background: #eff6ff; }
        .guest-item:hover .guest-name { color: #3b82f6; }

        .guest-item-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .guest-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 14px;
        }

        .guest-dot {
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
        }

        .guest-id {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }

        .chat-header {
            padding: 16px 24px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
        }

        #staff-chat-history {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .chat-input-bar {
            padding: 16px 24px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
        }

        #staff-input {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        #staff-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        #staff-send-btn {
            background: #003366;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        #staff-send-btn:hover { background: #004fa3; }

        #empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            background: #f8fafc;
        }

        #empty-state svg {
            width: 60px;
            height: 60px;
            margin-bottom: 16px;
        }

        #chat-window {
            flex: 1;
            display: none;
            flex-direction: column;
        }

        #chat-window.visible {
            display: flex;
        }
    </style>
</head>
<body>

@if(session('staff_role') === 'Admin')
@include('staff.admin-sidebar')
@else
@include('staff.sidebar')
@endif

<div class="messages-wrapper">

    <div class="guest-panel">
        <div class="guest-panel-header">Guest Inquiries</div>
        <div style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; background: #fff;">
            <div style="position: relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; width:13px; height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                <input type="text" id="guest-search" placeholder="Search guests..."
                    style="width:100%; padding:8px 10px 8px 30px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#334155; box-sizing:border-box; outline:none;"
                    oninput="filterGuests(this.value)">
            </div>
        </div>
        <div id="guest-list"></div>
    </div>

    <div id="chat-window">
        <div class="chat-header" id="current-guest-name">Select a conversation</div>
        <div id="staff-chat-history"></div>
        <div class="chat-input-bar">
            <input type="text" id="staff-input" placeholder="Write a reply...">
            <button id="staff-send-btn">Send</button>
        </div>
    </div>

    <div id="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
        </svg>
        <p>Select a guest from the left to view messages</p>
    </div>

</div>

<script>
    let selectedGuestId = null;
    let allGuests = [];

    function renderGuestList(guests) {
        const list = document.getElementById('guest-list');
        list.innerHTML = '';

        if (guests.length === 0) {
            list.innerHTML = '<p style="padding:16px;color:#94a3b8;font-size:13px;">No guests found.</p>';
            return;
        }

        guests.forEach(g => {
            const displayName = g.guest_name || 'Guest #' + g.guest_id;
            list.innerHTML += `
                <div class="guest-item" onclick="selectGuest(${g.guest_id}, '${displayName}')">
                    <div class="guest-item-top">
                        <span class="guest-name">${displayName}</span>
                        <span class="guest-dot"></span>
                    </div>
                    <div class="guest-id">Guest ID #${g.guest_id}</div>
                </div>
            `;
        });
    }

    function filterGuests(query) {
        const q = query.toLowerCase().trim();
        if (!q) {
            renderGuestList(allGuests);
            return;
        }
        const filtered = allGuests.filter(g => {
            const name = (g.guest_name || 'Guest #' + g.guest_id).toLowerCase();
            return name.includes(q) || String(g.guest_id).includes(q);
        });
        renderGuestList(filtered);
    }

    function loadGuestList() {
        fetch('/staff/get-guests')
            .then(res => res.json())
            .then(guests => {
                allGuests = guests;
                const query = document.getElementById('guest-search').value;
                if (query) {
                    filterGuests(query);
                } else {
                    renderGuestList(allGuests);
                }
            })
            .catch(err => console.error('Failed to load guest list:', err));
    }

    function selectGuest(id, name) {
        selectedGuestId = id;
        document.getElementById('chat-window').classList.add('visible');
        document.getElementById('empty-state').style.display = 'none';
        document.getElementById('current-guest-name').innerText = name;
        loadStaffMessages();
    }

    function loadStaffMessages() {
        if (!selectedGuestId) return;

        fetch('/staff/get-messages/' + selectedGuestId)
            .then(res => res.json())
            .then(messages => {
                const history = document.getElementById('staff-chat-history');
                history.innerHTML = '';

                messages.forEach(msg => {
                    // STAFF_ID being set means it's a staff message; otherwise it's a guest message
                    const isStaff  = msg.STAFF_ID !== null && msg.STAFF_ID !== undefined;
                    const align    = isStaff ? 'align-items:flex-end' : 'align-items:flex-start';
                    const bubbleBg = isStaff
                        ? 'background:#003366;color:white;'
                        : 'background:#fff;color:#1e293b;border:1px solid #e2e8f0;';
                    const radius   = isStaff
                        ? 'border-top-right-radius:4px;'
                        : 'border-top-left-radius:4px;';
                    const label    = isStaff ? 'Staff' : 'Guest';

                    history.innerHTML += `
                        <div style="display:flex;flex-direction:column;${align}">
                            <span style="font-size:11px;color:#94a3b8;margin-bottom:4px;">${label}</span>
                            <div style="${bubbleBg}padding:12px 16px;border-radius:16px;${radius}max-width:70%;font-size:14px;line-height:1.5;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                                ${msg.Message_Text}
                            </div>
                        </div>
                    `;
                });

                history.scrollTop = history.scrollHeight;
            })
            .catch(err => console.error('Failed to load messages:', err));
    }

    document.getElementById('staff-send-btn').addEventListener('click', () => {
        const input = document.getElementById('staff-input');
        const text  = input.value.trim();
        if (!text || !selectedGuestId) return;

        const btn     = document.getElementById('staff-send-btn');
        btn.disabled  = true;
        btn.innerText = 'Sending...';

        fetch('/staff/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ guest_id: selectedGuestId, message: text })
        })
        .then(res => {
            if (!res.ok) throw new Error('Send failed');
            return res.json();
        })
        .then(() => {
            input.value = '';
            loadStaffMessages();
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            btn.disabled  = false;
            btn.innerText = 'Send';
        });
    });

    document.getElementById('staff-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') document.getElementById('staff-send-btn').click();
    });

    loadGuestList();
    setInterval(loadStaffMessages, 3000);
</script>

</body>
</html>