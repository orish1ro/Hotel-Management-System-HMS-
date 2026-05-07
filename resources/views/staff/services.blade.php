<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@include('staff.admin-sidebar')

<div class="container" style="padding: 32px 36px;">
    <h3 class="section-title">
        <i class="fa-solid fa-concierge-bell"></i> Manage Services
    </h3>

    {{-- Success / Error Alerts --}}
    @if(session('success'))
        <div id="successAlert" style="
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-left: 5px solid #16a34a;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 10px rgba(22,163,74,0.08);
        ">
            <i class="fa-solid fa-circle-check" style="font-size:16px;"></i>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('successAlert');
                if (el) { el.style.transition='opacity 0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500); }
            }, 4000);
        </script>
    @endif

    @if(session('error'))
        <div style="
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-left: 5px solid #dc2626;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #991b1b;
            display: flex;
            align-items: center;
            gap: 10px;
        ">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start;">

        {{-- ===== LEFT: Services Table ===== --}}
        <div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Service Name</th>
                            <th class="text-left">Category</th>
                            <th class="text-left">Description</th>
                            <th class="num-header">Price</th>
                            <th class="text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $svc)
                        <tr>
                            <td class="text-left" style="font-weight: 600;">{{ $svc->Service_Name }}</td>
                            <td class="text-left">
                                <span style="
                                    background: #eff6ff;
                                    color: #1d4ed8;
                                    border: 1px solid #bfdbfe;
                                    border-radius: 20px;
                                    padding: 3px 10px;
                                    font-size: 12px;
                                    font-weight: 600;
                                ">{{ $svc->Service_Category ?? '—' }}</span>
                            </td>
                            <td class="text-left" style="font-size: 13px; color: #64748b; max-width: 220px;">
                                {{ $svc->Description ?? '—' }}
                            </td>
                            <td class="num-header" style="font-weight: 700; color: #15803d;">
                                ₱{{ number_format($svc->Price, 2) }}
                            </td>
                            <td style="white-space: nowrap;">
                                {{-- Edit button triggers modal --}}
                                <button
                                    onclick="openEditModal({{ $svc->SERVICES_ID }}, '{{ addslashes($svc->Service_Name) }}', '{{ $svc->Price }}', '{{ addslashes($svc->Service_Category ?? '') }}', '{{ addslashes($svc->Description ?? '') }}')"
                                    class="btn-confirm"
                                    style="margin-right: 6px;"
                                >
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>

                                <form action="/staff/delete-service/{{ $svc->SERVICES_ID }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-cancel"
                                        onclick="return confirm('Delete \'{{ $svc->Service_Name }}\'? This cannot be undone.');">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fa-solid fa-concierge-bell" style="font-size: 2rem; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                                No services added yet. Add your first service on the right.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $services->links() }}
            </div>
        </div>

        {{-- ===== RIGHT: Add Service Form ===== --}}
        <div style="
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        ">
            <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus-circle" style="color: #3b82f6;"></i> Add New Service
            </div>

            <form action="/staff/add-service" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        Service Name <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="service_name" placeholder="e.g. Airport Shuttle" required style="
                        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                        border-radius: 8px; font-size: 14px; box-sizing: border-box;
                        outline: none; transition: border-color 0.2s;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        Category <span style="color:#dc2626;">*</span>
                    </label>
                    <select name="category" required style="
                        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                        border-radius: 8px; font-size: 14px; box-sizing: border-box;
                        background: #fff; outline: none;
                    ">
                        <option value="">— Select Category —</option>
                        <option value="Transport">Transport</option>
                        <option value="Dining">Dining</option>
                        <option value="Accommodation">Accommodation</option>
                        <option value="Housekeeping">Housekeeping</option>
                        <option value="Wellness">Wellness</option>
                        <option value="Activities">Activities</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        Price (₱) <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="number" name="price" placeholder="0.00" min="0" step="0.01" required style="
                        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                        border-radius: 8px; font-size: 14px; box-sizing: border-box;
                        outline: none; transition: border-color 0.2s;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div class="form-group" style="margin-bottom: 22px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        Description
                    </label>
                    <textarea name="description" rows="3" placeholder="Brief description of the service..." style="
                        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                        border-radius: 8px; font-size: 14px; box-sizing: border-box;
                        resize: vertical; outline: none; transition: border-color 0.2s;
                        font-family: inherit;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'"></textarea>
                </div>

                <button type="submit" style="
                    width: 100%;
                    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                    color: #fff;
                    border: none;
                    border-radius: 10px;
                    padding: 12px;
                    font-size: 14px;
                    font-weight: 700;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    transition: opacity 0.2s;
                " onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='1'">
                    <i class="fa-solid fa-plus"></i> Add Service
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ===== Edit Modal ===== --}}
<div id="editModal" style="
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
">
    <div style="
        background: #fff;
        border-radius: 16px;
        padding: 32px 28px;
        width: 100%;
        max-width: 460px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        position: relative;
    ">
        <button onclick="closeEditModal()" style="
            position: absolute; top: 16px; right: 18px;
            background: none; border: none; font-size: 20px;
            color: #94a3b8; cursor: pointer;
        ">&times;</button>

        <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 22px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-pen" style="color:#3b82f6;"></i> Edit Service
        </div>

        <form id="editForm" method="POST">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                    Service Name <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" id="edit_name" name="service_name" required style="
                    width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                    border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;
                ">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                    Category <span style="color:#dc2626;">*</span>
                </label>
                <select id="edit_category" name="category" required style="
                    width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                    border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #fff; outline: none;
                ">
                    <option value="Transport">Transport</option>
                    <option value="Dining">Dining</option>
                    <option value="Accommodation">Accommodation</option>
                    <option value="Housekeeping">Housekeeping</option>
                    <option value="Wellness">Wellness</option>
                    <option value="Activities">Activities</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                    Price (₱) <span style="color:#dc2626;">*</span>
                </label>
                <input type="number" id="edit_price" name="price" min="0" step="0.01" required style="
                    width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                    border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;
                ">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                    Description
                </label>
                <textarea id="edit_description" name="description" rows="3" style="
                    width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1;
                    border-radius: 8px; font-size: 14px; box-sizing: border-box;
                    resize: vertical; outline: none; font-family: inherit;
                "></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeEditModal()" style="
                    flex: 1; padding: 11px; border: 1px solid #e2e8f0;
                    border-radius: 10px; background: #f8fafc; color: #64748b;
                    font-size: 14px; font-weight: 600; cursor: pointer;
                ">Cancel</button>
                <button type="submit" style="
                    flex: 2; padding: 11px;
                    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                    color: #fff; border: none; border-radius: 10px;
                    font-size: 14px; font-weight: 700; cursor: pointer;
                    display: flex; align-items: center; justify-content: center; gap: 6px;
                ">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, price, category, description) {
        document.getElementById('editForm').action = '/staff/edit-service/' + id;
        document.getElementById('edit_name').value        = name;
        document.getElementById('edit_price').value       = price;
        document.getElementById('edit_description').value = description;

        const catSelect = document.getElementById('edit_category');
        for (let opt of catSelect.options) {
            opt.selected = opt.value === category;
        }

        const modal = document.getElementById('editModal');
        modal.style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Close modal on backdrop click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>

</body>
</html>