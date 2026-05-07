<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 relative h-screen w-full">

    <header>
        <div class="logo-text">Ragadio Plaza Hotel</div>
        <div class="search-container">
            <input type="text" placeholder="search">
        </div>
        <nav>
            <a href="/">Home</a>
            <a href="/rooms">Rooms</a>
            <a href="/reservations">Reservations</a>
            
            @if(session()->has('guest_id'))
                <a href="/logout">Logout</a>
            @else
                <a href="/login">Login</a>
            @endif
        </nav>
    </header>

    <button id="chat-icon" class="fixed bottom-5 right-5 bg-blue-600 text-white p-4 rounded-full shadow-2xl hover:bg-blue-700 transition duration-200 z-50 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
    </button>

    <div id="chat-box" class="hidden fixed bottom-5 right-5 w-80 sm:w-96 bg-white shadow-2xl rounded-xl border border-gray-200 flex flex-col z-50">
        
        <div class="bg-blue-600 text-white px-4 py-3 rounded-t-xl flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 bg-green-400 rounded-full"></div>
                <h3 class="font-bold text-sm">Ragadio Plaza Support</h3>
            </div>
            <button id="close-chat" class="text-white hover:text-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="h-80 p-4 overflow-y-auto bg-gray-50 flex flex-col space-y-3">
            <div class="flex items-start justify-start">
                <div class="bg-gray-200 text-gray-800 p-3 rounded-2xl rounded-tl-sm max-w-[80%] text-sm shadow-sm">
                    Hello! Welcome to Ragadio Plaza Hotel. How can I help you today?
                </div>
            </div>
            <div class="flex items-end justify-end">
                <div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-sm max-w-[80%] text-sm shadow-sm">
                    Hi, I have a question about the room capacity.
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-gray-200 bg-white rounded-b-xl flex gap-2 items-center">
            <input type="text" placeholder="Type your message..." class="flex-1 bg-gray-100 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
            <button class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition duration-200 focus:outline-none">
                <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                </svg>
            </button>
        </div>
        
    </div>

    <script>
        const chatIcon = document.getElementById('chat-icon');
        const chatBox = document.getElementById('chat-box');
        const closeChat = document.getElementById('close-chat');

        // When you click the round icon, show the chat box and hide the icon
        chatIcon.addEventListener('click', () => {
            chatBox.classList.remove('hidden');