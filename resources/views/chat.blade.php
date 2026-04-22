@extends('layouts.app')

@section('title', 'Ai-asisstant - Itungin')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false, // Ini kunci rahasianya! Mencegah Tailwind merusak CSS Bootstrap & Sidebar
        }
    }
</script>

<style>
    /* Mengunci font dan scrollbar */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    
    /* Sembunyikan footer bawaan layout agar tidak merusak h-screen */
    footer, .footer, .main-footer {
        display: none !important;
    }

    main, .main-content, .content-wrapper, #app, .container-fluid {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    header {
        margin-top: 0 !important;
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }

    .content-wrapper, .main-content, #app {
        height: 100vh !important;
        display: flex;
        flex-direction: column;
    }
    
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden !important;
    }
    
    /* 2. UBAH DI SINI: Jangan gunakan tag 'body', tapi buat class khusus agar sidebar tidak kena efeknya */
    .ai-chat-area { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }

    /* Custom Scrollbar untuk area chat agar tetap clean */
    #chat-container::-webkit-scrollbar { width: 5px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    #chat-container::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    .dot-pulse { animation: pulse 1.5s infinite ease-in-out; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-400 { animation-delay: 0.4s; }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.4; }
        50% { transform: scale(1.3); opacity: 1; }
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="ai-chat-area flex flex-col h-screen overflow-hidden bg-[#f8fafc] pt-0 mt-0">
    
    <header class="flex-none h-20 bg-white border-b border-slate-200 flex items-center justify-between px-10 z-20 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                <i class="bi bi-robot fs-4"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 m-0 tracking-tight">Itungin Intelligence</h2>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Online</span>
                </div>
            </div>
        </div>
    </header>

    <div id="chat-container" class="flex-1 overflow-y-auto p-6 md:px-24 py-12 space-y-8 scroll-smooth">
    
    @if($history->isEmpty())
    <div id="welcome-hero" class="max-w-2xl mx-auto py-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-6">
            <i class="bi bi-stars fs-2"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-800 mb-3 tracking-tight">Halo, {{ Auth::user()->name }}!</h1>
        <p class="text-slate-500 leading-relaxed text-sm max-w-sm mx-auto">Tanyakan apapun soal rencana keuanganmu hari ini.</p>
    </div>
    @endif

    @foreach($history as $chat)
        <div class="flex justify-end mb-6">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-[2rem] rounded-tr-none shadow-xl max-w-[80%] text-sm leading-relaxed">
                {{ $chat->message }}
            </div>
        </div>

        <div class="flex justify-start mb-6">
            <div class="flex gap-4 max-w-[90%]">
                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm border flex items-center justify-center text-blue-600 shrink-0">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div class="bg-white border border-slate-100 text-slate-700 px-6 py-4 rounded-[2rem] rounded-tl-none shadow-sm text-sm leading-relaxed">
                    {!! nl2br(e($chat->reply)) !!}
                </div>
            </div>
        </div>
    @endforeach
    
    <div id="new-messages-anchor"></div>
</div>

    <div class="flex-none p-6 md:px-24 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto">
            
            <div id="quick-actions" class="mb-4 flex gap-2 justify-center">
                <button onclick="quickChat('Tips hemat minggu ini')" class="bg-slate-50 border border-slate-200 text-slate-600 px-4 py-1.5 rounded-full text-[11px] font-bold hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                    Tips Hemat
                </button>
                <button onclick="quickChat('Cara menabung 1 juta')" class="bg-slate-50 border border-slate-200 text-slate-600 px-4 py-1.5 rounded-full text-[11px] font-bold hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                    Target Nabung
                </button>
            </div>

            <div class="relative flex items-center">
                <div class="absolute left-4 text-slate-300">
                    <i class="bi bi-plus-circle-fill fs-5"></i>
                </div>
                <input type="text" id="user-input" 
                    class="w-full pl-12 pr-28 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all outline-none text-sm text-slate-700 shadow-inner" 
                    placeholder="Tanya Itungin AI sesuatu...">
                
                <button id="send-btn" class="absolute right-2 top-2 bottom-2 bg-blue-600 text-white px-6 rounded-xl font-bold text-xs hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <p class="text-center text-[9px] text-slate-400 mt-3 uppercase tracking-widest font-bold">Intelligence Engine by Gemini AI</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const chatContainer = document.getElementById('chat-container');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const welcomeHero = document.getElementById('welcome-hero');

    // Fungsi otomatis scroll ke bawah
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Jalankan scroll ke bawah saat halaman pertama kali dibuka (untuk history)
    window.onload = scrollToBottom;

    function appendMessage(text, isUser) {
        if (welcomeHero) welcomeHero.style.display = 'none';

        const wrapper = document.createElement('div');
        wrapper.className = isUser ? 'flex justify-end mb-6' : 'flex justify-start mb-6';

        // Gunakan replace untuk handle newline pada pesan baru
        const formattedText = text.replace(/\n/g, '<br>');

        const bubble = isUser 
            ? `<div class="bg-blue-600 text-white px-6 py-4 rounded-[2rem] rounded-tr-none shadow-xl shadow-blue-50 max-w-[80%] text-sm leading-relaxed">
                ${formattedText}
               </div>`
            : `<div class="flex gap-4 max-w-[90%]">
                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div class="bg-white border border-slate-100 text-slate-700 px-6 py-4 rounded-[2rem] rounded-tl-none shadow-sm text-sm leading-relaxed">
                    ${formattedText}
                </div>
               </div>`;

        wrapper.innerHTML = bubble;
        chatContainer.appendChild(wrapper);
        scrollToBottom();
    }

    async function handleSendMessage(msg = null) {
        const text = msg || userInput.value;
        if (!text.trim()) return;

        appendMessage(text, true);
        userInput.value = '';

        const loadingId = 'loading-' + Date.now();
        const loadingWrapper = document.createElement('div');
        loadingWrapper.id = loadingId;
        loadingWrapper.className = 'flex justify-start mb-6';
        loadingWrapper.innerHTML = `
            <div class="flex gap-4 max-w-[90%] items-center">
                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div class="bg-white border border-slate-100 px-6 py-4 rounded-[2rem] rounded-tl-none shadow-sm flex gap-1">
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full dot-pulse"></div>
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full dot-pulse delay-200"></div>
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full dot-pulse delay-400"></div>
                </div>
            </div>
        `;
        chatContainer.appendChild(loadingWrapper);
        scrollToBottom();

        try {
            const response = await fetch("/chat-process", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) loadingElement.remove();

            if (data.status === 'success') {
                appendMessage(data.reply, false);
            } else {
                appendMessage("Maaf, sepertinya ada kendala: " + data.message, false);
            }

        } catch (error) {
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) loadingElement.remove();
            appendMessage('Gagal koneksi ke server. Cek internet kamu ya!', false);
        }
    }

    function quickChat(text) { handleSendMessage(text); }
    sendBtn.addEventListener('click', () => handleSendMessage());
    userInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') handleSendMessage(); });
</script>
@endpush