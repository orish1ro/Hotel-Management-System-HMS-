<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room #{{ $room->Room_Number }} - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/staff_style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@if(session('staff_role') === 'Admin')
    @include('staff.admin-sidebar')
@else
    @include('staff.sidebar')
@endif

<div class="container" style="padding: 25px 36px;">

    {{-- Page Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
        <h3 class="section-title" style="margin:0;">
            <i class="fa-solid fa-pen-to-square"></i> Edit Room Details
        </h3>
        <a href="/staff/rooms" class="btn-checkout" style="text-decoration:none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Rooms
        </a>
    </div>

    <div style="max-width:820px; margin:0 auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); border:1px solid #e2e8f0;">

        {{-- Card Header --}}
        <div style="background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding:24px 28px; display:flex; align-items:center; gap:16px;">
            <div style="background:rgba(255,255,255,0.15); border-radius:12px; width:46px; height:46px; min-width:46px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-bed" style="color:#fff; font-size:20px;"></i>
            </div>
            <div>
                <div style="color:#fff; font-size:18px; font-weight:700; line-height:1.2;">Room #{{ $room->Room_Number }}</div>
                <div style="color:rgba(255,255,255,0.65); font-size:13px; margin-top:3px;">
                    {{ $room->Room_Type }} &nbsp;·&nbsp; Last updated: {{ $room->updated_at ? \Carbon\Carbon::parse($room->updated_at)->format('M d, Y') : 'N/A' }}
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="/staff/edit-room-submit/{{ $room->ROOM_ID }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="padding:28px 28px 0;">

                {{-- Section: Room Info --}}
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                    <i class="fa-solid fa-circle-info" style="color:#3b82f6; font-size:12px;"></i>
                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8;">Room Information</span>
                    <div style="flex:1; height:1px; background:#f1f5f9;"></div>
                </div>

                {{-- Row 1 --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                            Room Number <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="room_number" value="{{ $room->Room_Number }}" required style="
                            width:100%; padding:10px 13px; border:1.5px solid #e2e8f0;
                            border-radius:10px; font-size:14px; color:#1e293b;
                            box-sizing:border-box; outline:none; background:#fff;
                            transition:border-color 0.2s;
                        " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                            Room Type <span style="color:#dc2626;">*</span>
                        </label>
                        <select name="room_type" style="
                            width:100%; padding:10px 13px; border:1.5px solid #e2e8f0;
                            border-radius:10px; font-size:14px; color:#1e293b;
                            box-sizing:border-box; outline:none; background:#fff;
                        ">
                            <option value="Executive Suite" {{ $room->Room_Type == 'Executive Suite' ? 'selected' : '' }}>Executive Suite</option>
                            <option value="Family Suite"    {{ $room->Room_Type == 'Family Suite'    ? 'selected' : '' }}>Family Suite</option>
                            <option value="Deluxe Room"     {{ $room->Room_Type == 'Deluxe Room'     ? 'selected' : '' }}>Deluxe Room</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                            Price Per Night (₱) <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="number" name="price" value="{{ $room->Price_Per_Night }}" required style="
                            width:100%; padding:10px 13px; border:1.5px solid #e2e8f0;
                            border-radius:10px; font-size:14px; color:#1e293b;
                            box-sizing:border-box; outline:none; background:#fff;
                            transition:border-color 0.2s;
                        " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                            Max Capacity (Guests) <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="number" name="capacity" value="{{ $room->Capacity }}" required style="
                            width:100%; padding:10px 13px; border:1.5px solid #e2e8f0;
                            border-radius:10px; font-size:14px; color:#1e293b;
                            box-sizing:border-box; outline:none; background:#fff;
                            transition:border-color 0.2s;
                        " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Description --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                        Description / Details <span style="color:#dc2626;">*</span>
                    </label>
                    <textarea name="details" rows="2" required style="
                        width:100%; padding:10px 13px; border:1.5px solid #e2e8f0;
                        border-radius:10px; font-size:14px; color:#1e293b;
                        box-sizing:border-box; outline:none; resize:vertical;
                        font-family:inherit; background:#fff; transition:border-color 0.2s;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">{{ $room->Details }}</textarea>
                </div>

                {{-- Section: Cover Image --}}
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                    <i class="fa-solid fa-image" style="color:#3b82f6; font-size:12px;"></i>
                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8;">Cover Image</span>
                    <div style="flex:1; height:1px; background:#f1f5f9;"></div>
                </div>

                <div style="display:grid; grid-template-columns:180px 1fr; gap:20px; align-items:start; margin-bottom:28px;">

                    {{-- Current image --}}
                    <div>
                        <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:7px;">Current</div>
                        @if($room->Picture_Url)
                            <img src="{{ asset($room->Picture_Url) }}" style="width:180px; height:115px; object-fit:cover; border-radius:10px; border:2px solid #e2e8f0; display:block;">
                        @else
                            <div style="width:180px; height:115px; border-radius:10px; background:#f1f5f9; border:2px dashed #e2e8f0; display:flex; align-items:center; justify-content:center; color:#cbd5e1;">
                                <i class="fa-solid fa-image" style="font-size:1.5rem;"></i>
                            </div>
                        @endif
                        <div style="font-size:11px; color:#94a3b8; margin-top:6px; line-height:1.4;">Leave blank to keep current image.</div>
                    </div>

                    {{-- Upload zone --}}
                    <div>
                        <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:7px;">Replace With</div>

                        <div id="uploadZone" onclick="document.getElementById('room_image_file').click()" style="
                            border:2px dashed #cbd5e1; border-radius:12px;
                            height:115px; display:flex; flex-direction:column;
                            align-items:center; justify-content:center;
                            cursor:pointer; background:#f8fafc;
                            transition:border-color 0.2s, background 0.2s;
                        "
                        onmouseenter="this.style.borderColor='#3b82f6'; this.style.background='#eff6ff';"
                        onmouseleave="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc';">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.4rem; color:#94a3b8; margin-bottom:7px;"></i>
                            <div style="font-weight:600; color:#475569; font-size:13px;">Click to browse</div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:3px;">JPG, PNG, WEBP — optional</div>
                        </div>

                        <input type="file" id="room_image_file" name="room_image" accept="image/*" style="display:none;">

                        <div id="imagePreviewWrap" style="display:none; margin-top:8px;">
                            <div style="position:relative; display:inline-block; width:100%;">
                                <img id="imagePreview" src="#" style="width:100%; height:115px; object-fit:cover; border-radius:10px; border:2px solid #3b82f6; display:block;">
                                <button type="button" onclick="clearImage()" style="
                                    position:absolute; top:7px; right:7px;
                                    background:rgba(0,0,0,0.55); color:#fff; border:none;
                                    border-radius:50%; width:26px; height:26px; font-size:15px;
                                    cursor:pointer; display:flex; align-items:center; justify-content:center;
                                ">&times;</button>
                            </div>
                            <div id="imageFileName" style="font-size:11px; color:#64748b; margin-top:5px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons — always visible at the bottom --}}
            <div style="
                display:flex; gap:12px; justify-content:flex-end;
                padding:18px 28px;
                background:#f8fafc;
                border-top:1px solid #e2e8f0;
            ">
                <a href="/staff/rooms" style="
                    display:inline-flex; align-items:center; gap:7px;
                    padding:10px 20px; border:1.5px solid #e2e8f0; border-radius:10px;
                    background:#fff; color:#64748b; font-size:14px; font-weight:600;
                    text-decoration:none; transition:background 0.15s;
                " onmouseenter="this.style.background='#f1f5f9'" onmouseleave="this.style.background='#fff'">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
                <button type="submit" style="
                    display:inline-flex; align-items:center; gap:8px;
                    padding:10px 28px;
                    background:linear-gradient(135deg, #2563eb, #1d4ed8);
                    color:#fff; border:none; border-radius:10px;
                    font-size:14px; font-weight:700; cursor:pointer;
                    box-shadow:0 4px 12px rgba(37,99,235,0.25);
                    transition:opacity 0.15s;
                " onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='1'">
                    <i class="fa-solid fa-floppy-disk"></i> Save Updates
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const fileInput   = document.getElementById('room_image_file');
    const preview     = document.getElementById('imagePreview');
    const previewWrap = document.getElementById('imagePreviewWrap');
    const fileName    = document.getElementById('imageFileName');
    const uploadZone  = document.getElementById('uploadZone');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            previewWrap.style.display = 'block';
            uploadZone.style.display  = 'none';
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        };
        reader.readAsDataURL(file);
    });

    function clearImage() {
        fileInput.value = '';
        preview.src = '#';
        previewWrap.style.display = 'none';
        uploadZone.style.display  = 'block';
        fileName.textContent = '';
    }
</script>

</body>
</html>