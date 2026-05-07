<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($room) ? 'Edit Room' : 'Add New Room' }} - Ragadio Plaza Hotel</title>
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

    <div class="container rooms-container">
        
        <div class="header-flex" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="section-title" style="margin:0;">
                <i class="fa-solid fa-bed"></i> {{ isset($room) ? 'Edit Room Details' : 'Add New Room' }}
            </h3>
            <a href="/staff/rooms" class="btn-checkout" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Rooms
            </a>
        </div>

        <div class="rooms-scroll-area">
            <div class="form-card" style="max-width: 800px; margin: 0 auto; padding: 40px;">
                <form action="{{ isset($room) ? '/staff/edit-room-submit/'.$room->ROOM_ID : '/staff/add-room-submit' }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label class="text-left">Room Number</label>
                        <input type="text" name="room_number" value="{{ $room->Room_Number ?? '' }}" class="text-right" placeholder="e.g. 101" required>
                    </div>

                    <div class="form-group">
                        <label class="text-left">Room Type</label>
                        <select name="room_type" class="text-left">
                            <option value="Executive Suite" {{ (isset($room) && $room->Room_Type == 'Executive Suite') ? 'selected' : '' }}>Executive Suite</option>
                            <option value="Family Suite" {{ (isset($room) && $room->Room_Type == 'Family Suite') ? 'selected' : '' }}>Family Suite</option>
                            <option value="Deluxe Room" {{ (isset($room) && $room->Room_Type == 'Deluxe Room') ? 'selected' : '' }}>Deluxe Room</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="text-left">Price Per Night (₱)</label>
                            <input type="number" name="price" value="{{ $room->Price_Per_Night ?? '' }}" class="text-right" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label class="text-left">Max Capacity (Guests)</label>
                            <input type="number" name="capacity" value="{{ $room->Capacity ?? '' }}" class="text-right" placeholder="0" required>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label class="text-left">Description / Details</label>
                        <textarea name="details" class="text-left" rows="2" placeholder="Write room features here..." required>{{ $room->Details ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="text-left">Cover Image</label>

                        <div id="uploadZone" onclick="document.getElementById('room_image_file').click()" style="
                            border: 2px dashed #cbd5e1;
                            border-radius: 12px;
                            padding: 30px 20px;
                            text-align: center;
                            cursor: pointer;
                            background: #f8fafc;
                            transition: border-color 0.2s, background 0.2s;
                            margin-bottom: 16px;
                        "
                        onmouseenter="this.style.borderColor='#3b82f6'; this.style.background='#eff6ff';"
                        onmouseleave="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc';">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #94a3b8; margin-bottom: 8px; display: block;"></i>
                            <div style="font-weight: 600; color: #475569; font-size: 14px;">Click to browse image</div>
                            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">JPG, PNG, WEBP supported</div>
                        </div>

                        <input type="file" id="room_image_file" name="room_image" accept="image/*" style="display: none;">

                        <div id="imagePreviewWrap" style="display: none; margin-bottom: 16px;">
                            <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Preview</div>
                            <div style="position: relative; display: inline-block;">
                                <img id="imagePreview" src="#" alt="Preview" style="
                                    width: 100%;
                                    max-width: 360px;
                                    height: 200px;
                                    object-fit: cover;
                                    border-radius: 10px;
                                    border: 2px solid #e2e8f0;
                                    display: block;
                                ">
                                <button type="button" onclick="clearImage()" style="
                                    position: absolute;
                                    top: 8px;
                                    right: 8px;
                                    background: rgba(0,0,0,0.55);
                                    color: #fff;
                                    border: none;
                                    border-radius: 50%;
                                    width: 28px;
                                    height: 28px;
                                    font-size: 14px;
                                    cursor: pointer;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                " title="Remove image">&times;</button>
                            </div>
                            <div id="imageFileName" style="font-size: 12px; color: #64748b; margin-top: 6px;"></div>
                        </div>

                        @if(isset($room) && $room->Picture_Url)
                        <div id="currentImageWrap" style="margin-bottom: 12px;">
                            <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Current Image</div>
                            <img src="{{ asset($room->Picture_Url) }}" style="
                                width: 200px;
                                height: 120px;
                                object-fit: cover;
                                border-radius: 8px;
                                border: 2px solid #e2e8f0;
                            ">
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Upload a new image above to replace this.</div>
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-save" style="margin-top: 10px;">
                        <i class="fa-solid fa-floppy-disk"></i> {{ isset($room) ? 'Update Room' : 'Save Room' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const fileInput  = document.getElementById('room_image_file');
        const preview    = document.getElementById('imagePreview');
        const previewWrap = document.getElementById('imagePreviewWrap');
        const fileName   = document.getElementById('imageFileName');
        const uploadZone = document.getElementById('uploadZone');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                previewWrap.style.display = 'block';
                uploadZone.style.display = 'none';
                fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            };
            reader.readAsDataURL(file);
        });

        function clearImage() {
            fileInput.value = '';
            preview.src = '#';
            previewWrap.style.display = 'none';
            uploadZone.style.display = 'block';
            fileName.textContent = '';
        }
    </script>
</body>
</html>