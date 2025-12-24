// Data Mockup - Data sementara sebelum connect ke Database
// TIPS: Untuk mengganti gambar menu dengan foto sendiri:
// 1. Pastikan foto sudah ada di folder "img" (misal: img/kopi-susu.jpg)
// 2. Di bawah ini, ubah bagian 'image' sesuai nama file Anda.

const MOCK_MENU = [
    {
        id: 1,
        name: "Cappucino",
        price: 15000,
        image: "img/cappucino.jpg",
        category: "coffee",
        description: "Perpaduan sempurna espresso dan busa susu yang creamy untuk pengalaman kopi terbaik."
    },
    {
        id: 2,
        name: "Matcha Latte",
        price: 20000,
        image: "img/matchalatte.jpg",
        category: "non-coffee",
        description: "Minuman segar dari teh hijau matcha premium dengan susu hangat dan rasa yang menenangkan."
    },
    {
        id: 3,
        name: "Caramel Macchiato",
        price: 15000,
        image: "img/caramelmachiato.jpg",
        category: "coffee",
        description: "Kopi espresso dengan lapisan susu dan caramel manis yang menciptakan rasa unik yang tak terlupakan."
    },
    {
        id: 4,
        name: "Iced Latte",
        price: 18000,
        image: "img/icedlatte.jpg",
        category: "coffee",
        description: "Kopi susu yang didinginkan sempurna untuk menemani hari yang panas dengan kesegaran maksimal."
    },
    {
        id: 5,
        name: "Americano",
        price: 12000,
        image: "img/americano.jpg",
        category: "coffee",
        description: "Kopi murni dengan rasa yang kuat dan authentic untuk para pecinta kopi sejati."
    },
    {
        id: 6,
        name: "Chocolate Mocha",
        price: 22000,
        image: "img/chocolatemocha.jpg",
        category: "coffee",
        description: "Kombinasi lezat antara kopi, cokelat, dan susu yang menciptakan harmoni rasa yang sempurna."
    }
];

// Variables
let menuData = [];
const menuGrid = document.getElementById('menu-grid');
const loadingIndicator = document.getElementById('loading');
const noResults = document.getElementById('no-results');
const searchInput = document.getElementById('search-input');
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

// Format Currency IDR
const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

// Capitalize category name (coffee -> Coffee, non-coffee -> Non-Coffee)
const formatCategory = (category) => {
    return category
        .split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join('-');
};

// --- FUNGSI KONEKSI KE BACKEND (LARAVEL/PHP) ---
async function fetchMenu() {
    // Simulasi loading
    await new Promise(resolve => setTimeout(resolve, 800));

    try {
        // --- INSTRUKSI BACKEND ---
        // Jika backend Laravel/PHP sudah siap, hapus baris 'return MOCK_MENU;'
        // dan aktifkan kode fetch di bawah ini:
        
        /*
        // Contoh URL Endpoint Laravel
        const apiUrl = 'http://localhost:8000/api/menu'; 
        
        const response = await fetch(apiUrl);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        
        // Sesuaikan dengan format response JSON dari Laravel Anda
        // Pastikan field JSON-nya (name, price, image) sesuai dengan database
        return result.data; 
        */

        // Selama backend belum ada, kita pakai data palsu ini:
        return MOCK_MENU;

    } catch (error) {
        console.error("Gagal mengambil data dari server:", error);
        // Tampilkan pesan error di console
        return [];
    }
}

// Function to render menu items
function renderMenu(items) {
    menuGrid.innerHTML = '';
    
    if (items.length === 0) {
        menuGrid.classList.add('hidden');
        noResults.classList.remove('hidden');
        return;
    }

    noResults.classList.add('hidden');
    menuGrid.classList.remove('hidden');

    items.forEach(item => {
        const itemCard = document.createElement('div');
        itemCard.className = 'bg-[#604230] rounded-xl overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300';
        
        itemCard.innerHTML = `
            <div class="relative group overflow-hidden h-[300px]">
                <!-- Image Container dengan Hover Effect -->
                <div class="relative w-full h-full">
                    <!-- Circular Image -->
                    <div class="absolute inset-0 flex items-center justify-center p-4">
                        <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-[#EFDBC4]/20 shadow-inner bg-white transition-transform duration-300 group-hover:scale-95">
                            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Dark Overlay on Hover -->
                    <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <!-- Description Text -->
                        <p class="text-white text-center px-6 font-serif leading-relaxed text-sm md:text-base">
                            ${item.description}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Info Section (Tidak terpengaruh hover) -->
            <div class="p-6 flex flex-col items-center bg-[#604230]">
                <h3 class="text-2xl font-serif text-white mb-2 text-center">${item.name}</h3>
                <p class="text-[#EFDBC4] text-xl font-light mb-3">
                    ${formatRupiah(item.price)}
                </p>
                <!-- Category Label -->
                <span class="text-xs px-3 py-1 bg-[#EFDBC4]/20 text-[#EFDBC4] rounded-full font-semibold tracking-wide mb-4">
                    ${formatCategory(item.category)}
                </span>
                
                <!-- Order Button -->
                <button onclick="addToCart(${item.id})" class="mt-3 bg-[#EFDBC4] hover:bg-white text-[#604230] px-6 py-2.5 rounded-full font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2 mx-auto">
                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                    <span>Pesan Sekarang</span>
                </button>
            </div>
        `;
        menuGrid.appendChild(itemCard);
    });
    
    // Re-initialize Lucide icons for new elements
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// --- FUNGSI OPENSTREETMAP DENGAN LEAFLET (100% GRATIS, TANPA API KEY) ---
function initMap() {
    // Koordinat Thetazine Coffee Shop di Malang
    const thetazineLocation = [-7.97918829279658, 112.61148768673092];
    
    // Buat peta dengan Leaflet
    const map = L.map('map').setView(thetazineLocation, 16);
    
    // Tambahkan tile layer dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ' OpenStreetMap contributors',
        maxZoom: 19,
        tileSize: 256
    }).addTo(map);
    
    // Buat custom marker icon dengan warna cokelat Thetazine
    const customIcon = L.divIcon({
        html: `
            <div style="
                background-color: #604230;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                border: 3px solid #EFDBC4;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                cursor: pointer;
            ">
                
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 20],
        popupAnchor: [0, -20]
    });
    
    // Tambahkan marker di lokasi toko
    const marker = L.marker(thetazineLocation, { icon: customIcon }).addTo(map);
    
    // Buat popup content
    const popupContent = `
        <div style="font-family: Georgia, serif; min-width: 250px;">
            <h3 style="margin: 0 0 10px 0; color: #604230; font-size: 18px; font-weight: bold;">
                 Thetazine Coffee
            </h3>
            <p style="margin: 8px 0; font-size: 13px; color: #604230;">
                <strong> Alamat:</strong><br>
                Jl. Surabaya No. 45<br>
                Kelurahan Sumbersari<br>
                Kota Malang, Jawa Timur 65145
            </p>
            <p style="margin: 8px 0; font-size: 13px; color: #604230;">
                <strong> Jam Buka:</strong><br>
                Senin-Jumat: 07:00 - 22:00<br>
                Sabtu-Minggu: 08:00 - 23:00
            </p>
            <p style="margin: 8px 0; font-size: 13px; color: #604230;">
                <strong> Hubungi:</strong><br>
                +62 341 123 4567
            </p>
        </div>
    `;
    
    // Bind popup ke marker
    marker.bindPopup(popupContent, {
        maxWidth: 300,
        className: 'thetazine-popup'
    });
    
    // Buka popup otomatis saat peta selesai dimuat
    setTimeout(() => {
        marker.openPopup();
    }, 500);
    
    // Tambahkan CSS custom untuk popup
    const style = document.createElement('style');
    style.textContent = `
        .thetazine-popup .leaflet-popup-content {
            background-color: #EFDBC4;
            border: 2px solid #604230;
            border-radius: 8px;
            padding: 12px;
        }
        .thetazine-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(96, 66, 48, 0.3);
        }
        .thetazine-popup .leaflet-popup-tip {
            background-color: #EFDBC4;
            border-top-color: #604230;
        }
    `;
    document.head.appendChild(style);
}

// --- AUTHENTICATION FUNCTIONS ---
// Shopping Cart
let cart = [];

// Add item to cart
function addToCart(itemId) {
    const authToken = localStorage.getItem('auth_token');
    
    if (!authToken) {
        if (confirm('Anda harus login terlebih dahulu untuk memesan. Login sekarang?')) {
            window.location.href = '../coffee-shop-backend/login';
        }
        return;
    }
    
    const item = menuData.find(m => m.id === itemId);
    if (!item) return;
    
    const existingItem = cart.find(c => c.id === itemId);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            image: item.image,
            quantity: 1
        });
    }
    
    updateCartUI();
    showNotification(`${item.name} ditambahkan ke keranjang!`, 'success');
}

function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    updateCartUI();
    renderCartItems();
}

function updateQuantity(itemId, change) {
    const item = cart.find(c => c.id === itemId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(itemId);
        } else {
            updateCartUI();
            renderCartItems();
        }
    }
}

function updateCartUI() {
    const cartBtn = document.getElementById('cart-btn');
    const cartCount = document.getElementById('cart-count');
    
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
    
    const authToken = localStorage.getItem('auth_token');
    if (cartBtn && authToken) {
        if (totalItems > 0) {
            cartBtn.classList.remove('hidden');
            cartBtn.classList.add('flex');
        }
    }
}

function openCart() {
    const modal = document.getElementById('cart-modal');
    if (modal) {
        modal.classList.remove('hidden');
        renderCartItems();
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

function closeCart() {
    const modal = document.getElementById('cart-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function renderCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCart = document.getElementById('empty-cart');
    const cartFooter = document.getElementById('cart-footer');
    const cartTotal = document.getElementById('cart-total');
    
    if (cart.length === 0) {
        cartItemsContainer.classList.add('hidden');
        emptyCart.classList.remove('hidden');
        cartFooter.classList.add('hidden');
        return;
    }
    
    cartItemsContainer.classList.remove('hidden');
    emptyCart.classList.add('hidden');
    cartFooter.classList.remove('hidden');
    
    let total = 0;
    cartItemsContainer.innerHTML = cart.map(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        return `
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                <img src="${item.image}" alt="${item.name}" class="w-20 h-20 rounded-lg object-cover">
                <div class="flex-1">
                    <h3 class="font-bold text-thetazine-primary">${item.name}</h3>
                    <p class="text-thetazine-secondary">${formatRupiah(item.price)}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="updateQuantity(${item.id}, -1)" class="bg-gray-200 hover:bg-gray-300 text-thetazine-primary w-8 h-8 rounded-full font-bold">-</button>
                    <span class="font-bold text-lg w-8 text-center">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)" class="bg-gray-200 hover:bg-gray-300 text-thetazine-primary w-8 h-8 rounded-full font-bold">+</button>
                </div>
                <div class="text-right">
                    <p class="font-bold text-thetazine-primary">${formatRupiah(subtotal)}</p>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 text-sm mt-1">Hapus</button>
                </div>
            </div>
        `;
    }).join('');
    
    if (cartTotal) {
        cartTotal.textContent = formatRupiah(total);
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    notification.className = `fixed top-24 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.innerHTML = `<p class="font-semibold">${message}</p>`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Checkout function - Open checkout form
function checkout() {
    const authToken = localStorage.getItem('auth_token');
    
    if (!authToken) {
        alert('Anda harus login terlebih dahulu!');
        return;
    }
    
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    
    closeCart();
    openCheckout();
}

// Open checkout form modal
function openCheckout() {
    const modal = document.getElementById('checkout-modal');
    const user = localStorage.getItem('user');
    
    if (modal) {
        modal.classList.remove('hidden');
        
        if (user) {
            const userData = JSON.parse(user);
            const emailField = document.getElementById('customer_email');
            if (emailField && userData.email) {
                emailField.value = userData.email;
            }
            const nameField = document.getElementById('customer_name');
            if (nameField && userData.name) {
                nameField.value = userData.name;
            }
        }
        
        updateCheckoutSummary();
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

// Close checkout form modal
function closeCheckout() {
    const modal = document.getElementById('checkout-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Update checkout summary
function updateCheckoutSummary() {
    const checkoutTotal = document.getElementById('checkout-total');
    const checkoutCount = document.getElementById('checkout-count');
    
    const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (checkoutTotal) {
        checkoutTotal.textContent = formatRupiah(totalAmount);
    }
    if (checkoutCount) {
        checkoutCount.textContent = totalItems;
    }
}

// Submit checkout to API
async function submitCheckout(customerName, customerEmail, customerPhone, notes) {
    const authToken = localStorage.getItem('auth_token');
    
    const orderItems = cart.map(item => ({
        product_id: item.id,
        quantity: item.quantity
    }));
    
    try {
        const response = await fetch('../coffee-shop-backend/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`
            },
            body: JSON.stringify({
                customer_name: customerName,
                customer_email: customerEmail,
                customer_phone: customerPhone,
                items: orderItems,
                notes: notes || null
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showNotification('Pesanan berhasil dibuat!', 'success');
            
            const total = result.data.total_amount || (cart.reduce((sum, item) => sum + (item.price * item.quantity), 0));
            
            cart = [];
            updateCartUI();
            closeCheckout();
            
            alert(`Pesanan Anda telah dibuat!\n\nNomor Pesanan: ${result.data.uuid || result.data.id}\nTotal: ${formatRupiah(total)}\n\nTerima kasih telah memesan!`);
        } else {
            showNotification(result.message || 'Gagal membuat pesanan. Silakan coba lagi.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    }
}

// Handle Order Click - Check if user is logged in
function handleOrderClick() {
    const authToken = localStorage.getItem('auth_token');
    const user = localStorage.getItem('user');
    
    if (authToken && user) {
        // User is logged in, redirect to menu section to order
        const menuSection = document.getElementById('menu');
        if (menuSection) {
            menuSection.scrollIntoView({ behavior: 'smooth' });
            
            // Optional: Show notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-24 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = '<p class="font-semibold">Silakan pilih menu yang Anda inginkan!</p>';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    } else {
        // User is not logged in, show alert and redirect to login page
        if (confirm('Anda harus login terlebih dahulu untuk memesan. Login sekarang?')) {
            window.location.href = '../coffee-shop-backend/login';
        }
    }
}

// Check if user is logged in and update UI
function checkAuthStatus() {
    const authToken = localStorage.getItem('auth_token');
    const user = localStorage.getItem('user');
    
    // Get elements
    const loginBtn = document.getElementById('login-btn');
    const logoutBtn = document.getElementById('logout-btn');
    const userInfo = document.getElementById('user-info');
    const userName = document.getElementById('user-name');
    
    const mobileLoginBtn = document.getElementById('mobile-login-btn');
    const mobileLogoutBtn = document.getElementById('mobile-logout-btn');
    const mobileUserInfo = document.getElementById('mobile-user-info');
    const mobileUserName = document.getElementById('mobile-user-name');
    
    if (authToken && user) {
        // User is logged in
        const userData = JSON.parse(user);
        
        // Desktop: Hide Login, Show Logout
        if (loginBtn) loginBtn.classList.add('hidden');
        if (logoutBtn) {
            logoutBtn.classList.remove('hidden');
            logoutBtn.classList.add('flex');
        }
        if (userInfo) {
            userInfo.classList.remove('hidden');
            userInfo.classList.add('flex');
        }
        if (userName) userName.textContent = userData.name || 'User';
        
        // Mobile: Hide Login, Show Logout
        if (mobileLoginBtn) mobileLoginBtn.classList.add('hidden');
        if (mobileLogoutBtn) {
            mobileLogoutBtn.classList.remove('hidden');
            mobileLogoutBtn.classList.add('block');
        }
        if (mobileUserInfo) {
            mobileUserInfo.classList.remove('hidden');
            mobileUserInfo.classList.add('block');
        }
        if (mobileUserName) mobileUserName.textContent = userData.name || 'User';
    } else {
        // User is not logged in
        // Desktop: Show Login, Hide Logout
        if (loginBtn) loginBtn.classList.remove('hidden');
        if (logoutBtn) {
            logoutBtn.classList.add('hidden');
            logoutBtn.classList.remove('flex');
        }
        if (userInfo) {
            userInfo.classList.add('hidden');
            userInfo.classList.remove('flex');
        }
        
        // Mobile: Show Login, Hide Logout
        if (mobileLoginBtn) mobileLoginBtn.classList.remove('hidden');
        if (mobileLogoutBtn) {
            mobileLogoutBtn.classList.add('hidden');
            mobileLogoutBtn.classList.remove('block');
        }
        if (mobileUserInfo) {
            mobileUserInfo.classList.add('hidden');
            mobileUserInfo.classList.remove('block');
        }
    }
    
    // Re-initialize Lucide icons after UI changes
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Handle logout
async function handleLogout() {
    const authToken = localStorage.getItem('auth_token');
    
    if (authToken) {
        try {
            // Call logout API
            await fetch('../coffee-shop-backend/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                }
            });
        } catch (error) {
            console.error('Logout API error:', error);
        }
    }
    
    // Clear local storage
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    
    // Reload page to update UI
    window.location.reload();
}

// Initial Load
document.addEventListener('DOMContentLoaded', async () => {
    // 1. Check authentication status and update UI
    checkAuthStatus();
    
    // 2. Fetch Data Menu
    try {
        menuData = await fetchMenu();
        loadingIndicator.classList.add('hidden');
        renderMenu(menuData);
    } catch (error) {
        console.error("Error fetching menu:", error);
        loadingIndicator.innerHTML = '<p class="text-red-500">Gagal memuat data.</p>';
    }

    // 3. Search Functionality
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filteredItems = menuData.filter(item => 
            item.name.toLowerCase().includes(searchTerm)
        );
        renderMenu(filteredItems);
    });

    // 4. Mobile Menu Toggle
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // 5. Cart button handler
    const cartBtn = document.getElementById('cart-btn');
    if (cartBtn) {
        cartBtn.addEventListener('click', openCart);
    }

    // 6. Logout button handlers
    const logoutBtn = document.getElementById('logout-btn');
    const mobileLogoutBtn = document.getElementById('mobile-logout-btn');
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }
    
    if (mobileLogoutBtn) {
        mobileLogoutBtn.addEventListener('click', handleLogout);
    }

    // 7. Checkout form submit handler
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const customerName = document.getElementById('customer_name').value;
            const customerEmail = document.getElementById('customer_email').value;
            const customerPhone = document.getElementById('customer_phone').value;
            const notes = document.getElementById('notes').value;
            submitCheckout(customerName, customerEmail, customerPhone, notes);
        });
    }

    // 8. Initialize Map dengan Leaflet (OpenStreetMap)
    // Cek apakah map container ada dan Leaflet sudah loaded
    if (document.getElementById('map') && typeof L !== 'undefined') {
        initMap();
    }
});
