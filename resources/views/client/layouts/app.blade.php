<!DOCTYPE html>
<html lang="vi">

<head>
    <title>UNIMART STORE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('client/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('client/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/responsive.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">

    <script src="{{ asset('client/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/main.js') }}" type="text/javascript"></script>
</head>

<style>
    :root {
        --ai-bg: #0b1120;
        /* Màu nền xanh đen y hệt ảnh */
        --ai-panel: #111827;
        --ai-text: #f3f4f6;
        --ai-muted: #9ca3af;
        --ai-accent: #8b5cf6;
        /* Tím nhạt cho icon sparkle */
        --ai-border: rgba(255, 255, 255, 0.12);
        --ai-input-bg: rgba(255, 255, 255, 0.08);
    }

    /* Nút bấm để mở Concierge ở góc màn hình (Giống widget cũ) */
    .ai-trigger-btn {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9999;
        padding: 14px 20px;
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, #2563eb, #8b5cf6);
        color: white;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* KHUNG BAO TOÀN MÀN HÌNH */
    .ai-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: radial-gradient(circle at 50% 0%, #1e293b 0%, var(--ai-bg) 60%);
        z-index: 99999;
        color: var(--ai-text);
        font-family: "Roboto", sans-serif;
        display: flex;
        flex-direction: column;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .ai-overlay.is-active {
        opacity: 1;
        pointer-events: all;
    }

    /* Nút đóng / Nút chức năng góc phải */
    .ai-top-bar {
        position: absolute;
        top: 20px;
        right: 30px;
        z-index: 10;
        display: flex;
        gap: 10px;
    }

    .ai-btn-outline {
        background: transparent;
        border: 1px solid var(--ai-border);
        color: var(--ai-text);
        padding: 8px 16px;
        border-radius: 999px;
        cursor: pointer;
        font-size: 13px;
    }

    .ai-btn-outline:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    /* =========================================
     TRẠNG THÁI 1: LANDING (GIỮA MÀN HÌNH)
     ========================================= */
    .ai-landing-view {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
    }

    .ai-landing-view h1 {
        font-size: 42px;
        font-weight: 500;
        margin-bottom: 40px;
        text-align: center;
    }

    .ai-chips-container {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .ai-chip {
        background: var(--ai-input-bg);
        border: 1px solid var(--ai-border);
        color: var(--ai-text);
        padding: 10px 20px;
        border-radius: 999px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }

    .ai-chip i {
        color: var(--ai-accent);
        font-size: 14px;
    }

    .ai-chip:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    /* Thanh search dùng chung (sẽ move vị trí khi đổi trạng thái) */
    .ai-search-box {
        width: 100%;
        max-width: 800px;
        background: var(--ai-input-bg);
        border: 1px solid var(--ai-border);
        border-radius: 999px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.4s ease;
    }

    .ai-search-box input {
        flex: 1;
        background: transparent;
        border: none;
        color: white;
        font-size: 16px;
        outline: none;
    }

    .ai-search-box input::placeholder {
        color: var(--ai-muted);
    }

    .ai-search-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-icon-btn {
        background: transparent;
        border: none;
        color: var(--ai-text);
        font-size: 18px;
        cursor: pointer;
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
    }

    .ai-icon-btn.primary {
        background: rgba(255, 255, 255, 0.1);
    }

    .ai-icon-btn:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    /* =========================================
     TRẠNG THÁI 2: SPLIT SCREEN (SAU KHI CHAT)
     ========================================= */
    .ai-split-view {
        flex: 1;
        display: flex;
        opacity: 0;
        pointer-events: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 20px;
        gap: 20px;
        transition: opacity 0.4s ease;
    }

    /* Cấu hình khi chuyển trạng thái */
    .ai-overlay.is-chatting .ai-landing-view {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-20px);
        position: absolute;
    }

    .ai-overlay.is-chatting .ai-split-view {
        opacity: 1;
        pointer-events: all;
        position: relative;
    }

    /* Cột trái: Console Chat */
    .ai-sidebar {
        width: 350px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--ai-border);
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .ai-sidebar-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--ai-border);
        font-weight: bold;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ai-sidebar-header i {
        color: var(--ai-accent);
        margin-right: 8px;
    }

    .ai-chat-history {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .ai-chat-history::-webkit-scrollbar {
        width: 6px;
    }

    .ai-chat-history::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    /* Bubble Chat */
    .msg-bot {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .msg-bot i {
        color: var(--ai-accent);
        font-size: 14px;
        margin-top: 4px;
    }

    .msg-bot .text {
        color: var(--ai-text);
        font-size: 14px;
        line-height: 1.5;
    }

    .msg-user {
        align-self: flex-end;
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 16px;
        border-radius: 16px 16px 4px 16px;
        font-size: 14px;
        color: #fff;
        max-width: 85%;
    }

    /* Input trong sidebar */
    .ai-sidebar-input {
        padding: 15px;
        border-top: 1px solid var(--ai-border);
    }

    /* Cột phải: Kết quả / Sản phẩm */
    .ai-main-content {
        flex: 1;
        overflow-y: auto;
        padding-right: 10px;
    }

    .ai-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .ai-product-card {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .ai-product-card:hover {
        transform: translateY(-5px);
    }

    .ai-product-img {
        width: 100%;
        aspect-ratio: 1/1;
        border-radius: 16px;
        object-fit: cover;
        background: #1e293b;
        border: 1px solid var(--ai-border);
        margin-bottom: 12px;
    }

    .ai-product-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .ai-product-title {
        font-size: 14px;
        color: var(--ai-text);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ai-product-add {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid var(--ai-border);
        display: flex;
        justify-content: center;
        align-items: center;
        background: transparent;
        color: white;
        cursor: pointer;
        flex-shrink: 0;
        transition: 0.2s;
    }

    .ai-product-add:hover {
        background: white;
        color: black;
    }

    /* Hiệu ứng loading */
    .typing-indicator {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }

    .typing-indicator span {
        width: 6px;
        height: 6px;
        background: var(--ai-muted);
        border-radius: 50%;
        animation: blink 1.4s infinite both;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 0.2;
        }

        20% {
            opacity: 1;
        }
    }
</style>

<body>
    <div id="site">
        <div id="container">
            {{-- --- HEADER --- --}}
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <!-- <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a> -->
                        <section class="benefits fl-left">
                            <div class="benefits-inner">
                                <div class="benefit"><i class="fa-solid fa-truck"></i><span>Miễn phí vận chuyển</span>
                                </div>
                                <div class="benefit"><i class="fa-solid fa-headset"></i><span>Tư vấn 24/7</span></div>
                                <div class="benefit"><i class="fa-solid fa-sack-dollar"></i><span>Tiết kiệm hơn</span>
                                </div>
                                <div class="benefit"><i class="fa-solid fa-credit-card"></i><span>Thanh toán
                                        nhanh</span></div>
                                <div class="benefit"><i class="fa-solid fa-cart-arrow-down"></i><span>Đặt hàng
                                        online</span></div>

                                <!-- Nhân đôi để nối mượt -->
                                <div class="benefit"><i class="fa-solid fa-truck"></i><span>Miễn phí vận chuyển</span>
                                </div>
                                <div class="benefit"><i class="fa-solid fa-headset"></i><span>Tư vấn 24/7</span></div>
                                <div class="benefit"><i class="fa-solid fa-sack-dollar"></i><span>Tiết kiệm hơn</span>
                                </div>
                                <div class="benefit"><i class="fa-solid fa-credit-card"></i><span>Thanh toán
                                        nhanh</span></div>
                                <div class="benefit"><i class="fa-solid fa-cart-arrow-down"></i><span>Đặt hàng
                                        online</span></div>
                            </div>
                        </section>

                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li><a href="bai-viet" title="Blog">Blog</a></li>
                                <li><a href="gioi-thieu" title="Giới thiệu">Giới thiệu</a></li>
                                <li><a href="lien-he" title="LIên hệ">LIên hệ</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="head-body" class="main-header">
                    <div class="wp-inner">
                        <a href="{{ url('/') }}" title="Trang chủ" id="logo" class="header-logo">
                            <img src="{{ asset('client/images/logo.png') }}" alt="Logo" />
                        </a>

                        {{-- ĐANG TEST --}}
                        {{-- <div class="category-wrapper">
                            
                        </div> --}}
                        <button class="navbar__item button__menu" aria-label="Danh mục sản phẩm">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.7041 4H10.7041V10H4.7041V4Z" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M14.7041 4H20.7041V10H14.7041V4Z" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M4.7041 14H10.7041V20H4.7041V14Z" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M14.7041 17C14.7041 17.7956 15.0202 18.5587 15.5828 19.1213C16.1454 19.6839 16.9085 20 17.7041 20C18.4998 20 19.2628 19.6839 19.8254 19.1213C20.388 18.5587 20.7041 17.7956 20.7041 17C20.7041 16.2044 20.388 15.4413 19.8254 14.8787C19.2628 14.3161 18.4998 14 17.7041 14C16.9085 14 16.1454 14.3161 15.5828 14.8787C15.0202 15.4413 14.7041 16.2044 14.7041 17Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                            <span class="navbar__item-text">Danh mục</span>

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.7041 9L12.7041 15L18.7041 9" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>

                        <div class="section category-dropdown" id="category-product-wp">
                            <ul class="list-item">
                                @foreach ($categories as $category)
                                    @include('client.partials.menu', ['category' => $category])
                                @endforeach
                            </ul>
                        </div>

                        <div id="search-wp" class="header-search">
                            <form method="GET" action="{{ url('tim-kiem') }}" role="search">
                                <input type="text" name="q" id="s"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!" required>
                                <button type="submit" id="sm-s"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>

                        <div id="action-wp" class="header-actions">
                            <div id="advisory-wp" class="advisory">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>

                            <div id="cart-wp" class="cart-wp">
                                <a href="{{ url('gio-hang') }}">
                                    <div id="cart-btn-wp" class="cart-btn">
                                        <div id="btn-cart">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span
                                                id="num">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                                        </div>
                                        <span class="cart-text">Giỏ hàng</span>
                                    </div>
                                </a>
                                <div id="dropdown" class="cart-dropdown">
                                    {{-- Ajax hoặc Include giỏ hàng tại đây --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- --- NỘI DUNG CHÍNH --- --}}
            <div id="wp-content">
                @yield('content')
            </div>

            {{-- --- FOOTER --- --}}
            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">UNIMART</h3>
                            <p class="desc">UNIMART luôn cung cấp sản phẩm chính hãng...</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="{{ asset('client/images/img-foot.png') }}" alt="Payment methods">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>Landmak 81 - Hồ Chí Minh</p>
                                </li>
                                <li>
                                    <p>0987.654.321</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner text-center">
                        <p id="copyright">© Bản quyền thuộc về unimart | Hoàng Minh Trí</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="btn-top">
        <strong>Lên đầu</strong>
        <i class="fa-solid fa-chevron-up" style="color: white;"></i>
    </div>

    {{-- UI FOR AI CONCIERGE --}}
    <button class="ai-trigger-btn" onclick="openAiConcierge()">
        <i class="fa-solid fa-wand-magic-sparkles"></i> Unimart Concierge
    </button>

    <div id="aiOverlay" class="ai-overlay">
        <div class="ai-top-bar">
            <button class="ai-btn-outline">How it works</button>
            <button class="ai-btn-outline" onclick="closeAiConcierge()">
                <i class="fa-solid fa-xmark"></i> Close
            </button>
        </div>

        <div class="ai-landing-view">
            <h1>What can I help you<br>find today?</h1>

            <div class="ai-chips-container">
                <div class="ai-chip" onclick="submitQuery('Laptop tầm 15 triệu cho sinh viên')">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Laptop tầm 15 triệu cho sinh viên.
                </div>
                <div class="ai-chip" onclick="submitQuery('Gợi ý điện thoại chụp ảnh đẹp')">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Gợi ý điện thoại chụp ảnh đẹp.
                </div>
                <div class="ai-chip" onclick="submitQuery('Có khuyến mãi nào hôm nay?')">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Có khuyến mãi nào hôm nay?
                </div>
            </div>

            <div class="ai-search-box">
                <input type="text" id="landingInput" placeholder="What can we help you find?"
                    onkeypress="handleEnter(event, this)">
                <div class="ai-search-actions">
                    <button class="ai-icon-btn"><i class="fa-regular fa-image"></i></button>
                    <button class="ai-icon-btn primary"><i class="fa-solid fa-microphone"></i></button>
                    <button class="ai-icon-btn" onclick="submitQuery(document.getElementById('landingInput').value)">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="ai-split-view">
            <aside class="ai-sidebar">
                <div class="ai-sidebar-header">
                    <div><i class="fa-solid fa-wand-magic-sparkles"></i> Shopping Console</div>
                    <button style="background:transparent; border:none; color:white; cursor:pointer"
                        onclick="resetAiChat()">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>

                <div class="ai-chat-history" id="chatHistory">
                </div>

                <div class="ai-sidebar-input">
                    <div class="ai-search-box" style="padding: 8px 15px;">
                        <input type="text" id="sidebarInput" placeholder="Ask a follow up..."
                            onkeypress="handleEnter(event, this)">
                        <div class="ai-search-actions">
                            <button class="ai-icon-btn" style="width:28px; height:28px"
                                onclick="submitQuery(document.getElementById('sidebarInput').value)">
                                <i class="fa-regular fa-paper-plane" style="font-size:14px"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="ai-main-content">
                <h2 style="font-size: 20px; font-weight: normal; margin-bottom: 10px; color: var(--ai-muted)"
                    id="resultTitle">
                    Waiting for query...
                </h2>
                <div class="ai-product-grid" id="productGrid">
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <script>
        const aiOverlay = document.getElementById('aiOverlay');
        const chatHistory = document.getElementById('chatHistory');
        const productGrid = document.getElementById('productGrid');
        const resultTitle = document.getElementById('resultTitle');
        const landingInput = document.getElementById('landingInput');
        const sidebarInput = document.getElementById('sidebarInput');

        const RASA_SOCKET_URL = "http://127.0.0.1:5005";
        let socket = null;
        // Sử dụng ID cố định hoặc theo session để Rasa nhận diện đúng context
        const userId = "unimart_user_01";

        // Mở/Đóng UI
        function openAiConcierge() {
            aiOverlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
            connectRasa();
        }

        function closeAiConcierge() {
            aiOverlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }

        function resetAiChat() {
            aiOverlay.classList.remove('is-chatting');
            chatHistory.innerHTML = '';
            productGrid.innerHTML = '';
            landingInput.value = '';
            sidebarInput.value = '';

            // Gửi lệnh greet để Rasa chào lại hoặc tự append tin nhắn mặc định
            if (socket && socket.connected) {
                socket.emit('user_uttered', {
                    message: "/greet",
                    session_id: userId
                });
            }
        }

        // Kết nối Rasa qua Socket.IO
        function connectRasa() {
            if (socket && socket.connected) return;

            socket = io(RASA_SOCKET_URL, {
                path: "/socket.io/",
                transports: ["websocket"]
            });

            socket.on("connect", () => {
                console.log("Connected to Rasa Server");

                socket.emit("session_request", {
                    session_id: userId
                });
            });

            socket.on("bot_uttered", (payload) => {

                removeTypingIndicator();

                if (payload.text) {
                    appendBotMessage(payload.text);
                }

                if (payload.custom && payload.custom.products) {
                    renderProducts(payload.custom.products);
                }

            });

            socket.on("disconnect", () => {
                console.log("Disconnected from Rasa Server");
            });
        }

        function handleEnter(e, inputEl) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitQuery(inputEl.value);
            }
        }

        function submitQuery(text) {
            if (!text.trim()) return;

            // Chuyển sang giao diện Chat
            if (!aiOverlay.classList.contains('is-chatting')) {
                aiOverlay.classList.add('is-chatting');
            }

            appendUserMessage(text);
            resultTitle.innerHTML = `Searching for: <span style="color: white">"${text}"</span>`;

            // Clear input
            landingInput.value = '';
            sidebarInput.value = '';

            showTypingIndicator();

            if (socket && socket.connected) {
                socket.emit("user_uttered", {
                    message: text,
                    session_id: userId
                });
            } else {
                removeTypingIndicator();
                appendBotMessage("Lỗi: Không thể kết nối tới máy chủ Rasa. Vui lòng kiểm tra lại server.");
            }
        }

        function appendUserMessage(text) {
            const div = document.createElement('div');
            div.className = 'msg-user';
            div.innerText = text;
            chatHistory.appendChild(div);
            scrollToBottom();
        }

        function appendBotMessage(text) {
            const div = document.createElement('div');
            div.className = 'msg-bot';
            div.innerHTML = `<i class="fa-solid fa-wand-magic-sparkles"></i><div class="text">${text}</div>`;
            chatHistory.appendChild(div);
            scrollToBottom();
        }

        function showTypingIndicator() {
            const div = document.createElement('div');
            div.className = 'msg-bot';
            div.id = 'typingLoader';
            div.innerHTML =
                `<i class="fa-solid fa-wand-magic-sparkles"></i><div class="typing-indicator"><span></span><span></span><span></span></div>`;
            chatHistory.appendChild(div);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            const loader = document.getElementById('typingLoader');
            if (loader) loader.remove();
        }

        function scrollToBottom() {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        function renderProducts(items) {
            productGrid.innerHTML = '';
            if (items.length === 0) {
                productGrid.innerHTML = '<p style="color: var(--ai-muted)">No products found.</p>';
                return;
            }
            items.forEach(item => {
                const html = `
                <div class="ai-product-card">
                    <img src="${item.image}" class="ai-product-img" alt="${item.name}">
                    <div class="ai-product-info">
                        <div>
                            <div class="ai-product-title">${item.name}</div>
                            <div style="color: #fbbf24; font-size: 13px; font-weight: bold; margin-top:4px">${item.price || ''}</div>
                        </div>
                        <button class="ai-product-add"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            `;
                productGrid.insertAdjacentHTML('beforeend', html);
            });
        }
    </script>
    {{-- Script Facebook SDK --}}
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        $(document).ready(function() {
            // Toggle dropdown khi click nút danh mục
            $('.button__menu').click(function(e) {
                e.stopPropagation(); // Ngăn bọt lan tỏa
                $('#category-product-wp').toggleClass('active');
            });

            // Đóng dropdown khi click ngoài
            $(document).click(function() {
                $('#category-product-wp').removeClass('active');
            });

            // Ngăn đóng khi click vào dropdown
            $('#category-product-wp').click(function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>

</html>
