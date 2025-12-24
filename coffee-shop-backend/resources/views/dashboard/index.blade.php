@extends('layouts.app')

@section('title', 'Dashboard - Thetazine Coffee')

@section('content')
<div x-data="dashboardApp()" x-init="init()" class="min-h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-thetazine-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <i data-lucide="coffee" class="h-8 w-8 text-white"></i>
                    <span class="text-white text-xl font-bold">Thetazine Dashboard</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-white" x-text="'Hello, ' + userName"></span>
                    <button 
                        @click="logout()"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Total Products</p>
                        <p class="text-3xl font-bold text-thetazine-primary" x-text="stats.totalProducts"></p>
                    </div>
                    <i data-lucide="package" class="h-12 w-12 text-thetazine-primary opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Total Orders</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="stats.totalOrders"></p>
                    </div>
                    <i data-lucide="shopping-cart" class="h-12 w-12 text-blue-600 opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Pending Orders</p>
                        <p class="text-3xl font-bold text-yellow-600" x-text="stats.pendingOrders"></p>
                    </div>
                    <i data-lucide="clock" class="h-12 w-12 text-yellow-600 opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-600" x-text="formatCurrency(stats.totalRevenue)"></p>
                    </div>
                    <i data-lucide="dollar-sign" class="h-12 w-12 text-green-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow-md mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button 
                        @click="activeTab = 'products'"
                        :class="activeTab === 'products' ? 'border-thetazine-primary text-thetazine-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-4 border-b-2 font-semibold text-sm transition"
                    >
                        Products Management
                    </button>
                    <button 
                        @click="activeTab = 'orders'"
                        :class="activeTab === 'orders' ? 'border-thetazine-primary text-thetazine-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-4 border-b-2 font-semibold text-sm transition"
                    >
                        Orders Management
                    </button>
                </nav>
            </div>
        </div>

        <!-- Products Tab -->
        <div x-show="activeTab === 'products'" class="space-y-6">
            <!-- Add Product Button -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-thetazine-primary">Products</h2>
                <button 
                    @click="openProductModal()"
                    class="bg-thetazine-primary hover:bg-thetazine-secondary text-white px-6 py-2 rounded-lg transition flex items-center gap-2"
                >
                    <i data-lucide="plus" class="h-5 w-5"></i>
                    Add Product
                </button>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="product in products" :key="product.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 mr-4">
                                            <img 
                                                :src="product.image_url || 'https://via.placeholder.com/100?text=No+Image'" 
                                                :alt="product.name"
                                                class="h-12 w-12 rounded-lg object-cover"
                                                onerror="this.src='https://via.placeholder.com/100?text=No+Image'"
                                            >
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900" x-text="product.name"></div>
                                            <div class="text-sm text-gray-500" x-text="product.description"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800" x-text="product.category"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatCurrency(product.price)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="product.stock"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="product.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        x-text="product.is_available ? 'Available' : 'Unavailable'"
                                    ></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="openProductModal(product)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button @click="deleteProduct(product.uuid)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Tab -->
        <div x-show="activeTab === 'orders'" class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-thetazine-primary">Orders</h2>
                <button 
                    @click="openOrderModal()"
                    class="bg-thetazine-primary hover:bg-thetazine-secondary text-white px-6 py-2 rounded-lg transition flex items-center gap-2"
                >
                    <i data-lucide="plus" class="h-5 w-5"></i>
                    Create Order
                </button>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="order in orders" :key="order.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900" x-text="order.uuid.substring(0, 8)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="order.customer_name"></div>
                                    <div class="text-sm text-gray-500" x-text="order.customer_email"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatCurrency(order.total_amount)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                            'bg-blue-100 text-blue-800': order.status === 'processing',
                                            'bg-green-100 text-green-800': order.status === 'completed',
                                            'bg-red-100 text-red-800': order.status === 'cancelled'
                                        }"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                        x-text="order.status"
                                    ></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="new Date(order.created_at).toLocaleDateString()"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="updateOrderStatus(order)" class="text-blue-600 hover:text-blue-900 mr-3">Update</button>
                                    <button @click="deleteOrder(order.uuid)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Product Modal -->
    <div x-show="showProductModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="showProductModal = false">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8">
            <h3 class="text-2xl font-bold text-thetazine-primary mb-6" x-text="productForm.id ? 'Edit Product' : 'Add Product'"></h3>
            
            <form @submit.prevent="saveProduct()">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input type="text" x-model="productForm.name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select x-model="productForm.category" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                            <option value="coffee">Coffee</option>
                            <option value="pastry">Pastry</option>
                            <option value="snack">Snack</option>
                            <option value="beverage">Beverage</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea x-model="productForm.description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (Rp)</label>
                        <input type="number" x-model="productForm.price" required min="0" step="1000" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stock</label>
                        <input type="number" x-model="productForm.stock" required min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Image URL</label>
                    <input type="url" x-model="productForm.image_url" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary" placeholder="https://example.com/image.jpg">
                    <div x-show="productForm.image_url" class="mt-3">
                        <p class="text-xs text-gray-500 mb-2">Preview:</p>
                        <img 
                            :src="productForm.image_url" 
                            alt="Product preview"
                            class="h-32 w-32 rounded-lg object-cover border-2 border-gray-200"
                            onerror="this.src='https://via.placeholder.com/150?text=Invalid+URL'"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" x-model="productForm.is_available" class="rounded border-gray-300 text-thetazine-primary focus:ring-thetazine-primary">
                        <span class="ml-2 text-sm font-semibold text-gray-700">Available for sale</span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-thetazine-primary hover:bg-thetazine-secondary text-white font-bold py-3 rounded-lg transition">
                        Save Product
                    </button>
                    <button type="button" @click="showProductModal = false" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-3 rounded-lg transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Modal -->
    <div x-show="showOrderModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="showOrderModal = false">
        <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-8">
            <h3 class="text-2xl font-bold text-thetazine-primary mb-6">Create New Order</h3>
            
            <form @submit.prevent="saveOrder()">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Name</label>
                        <input type="text" x-model="orderForm.customer_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Email</label>
                        <input type="email" x-model="orderForm.customer_email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Phone</label>
                    <input type="tel" x-model="orderForm.customer_phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary">
                </div>

                <!-- Order Items -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Order Items</label>
                    <template x-for="(item, index) in orderForm.items" :key="index">
                        <div class="flex gap-2 mb-2">
                            <select x-model="item.product_id" required class="flex-1 px-4 py-2 border rounded-lg">
                                <option value="">Select Product</option>
                                <template x-for="product in products.filter(p => p.is_available)" :key="product.id">
                                    <option :value="product.id" x-text="`${product.name} - ${formatCurrency(product.price)}`"></option>
                                </template>
                            </select>
                            <input type="number" x-model="item.quantity" required min="1" placeholder="Qty" class="w-20 px-4 py-2 border rounded-lg">
                            <button type="button" @click="orderForm.items.splice(index, 1)" class="px-3 bg-red-500 text-white rounded-lg">×</button>
                        </div>
                    </template>
                    <button type="button" @click="orderForm.items.push({product_id: '', quantity: 1})" class="mt-2 text-thetazine-primary hover:underline text-sm">
                        + Add Item
                    </button>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea x-model="orderForm.notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-thetazine-primary hover:bg-thetazine-secondary text-white font-bold py-3 rounded-lg transition">
                        Create Order
                    </button>
                    <button type="button" @click="showOrderModal = false" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-3 rounded-lg transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function dashboardApp() {
        return {
            activeTab: 'products',
            userName: '',
            stats: {
                totalProducts: 0,
                totalOrders: 0,
                pendingOrders: 0,
                totalRevenue: 0
            },
            products: [],
            orders: [],
            showProductModal: false,
            showOrderModal: false,
            productForm: {
                id: null,
                name: '',
                description: '',
                price: 0,
                stock: 0,
                category: 'coffee',
                image_url: '',
                is_available: true
            },
            orderForm: {
                customer_name: '',
                customer_email: '',
                customer_phone: '',
                items: [{product_id: '', quantity: 1}],
                notes: ''
            },

            async init() {
                // Check authentication
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                const user = JSON.parse(localStorage.getItem('user') || '{}');
                
                // Check if user is admin
                if (user.role !== 'admin') {
                    alert('Access denied. Admin only.');
                    window.location.href = '{{ route("home") }}';
                    return;
                }
                
                this.userName = user.name || 'User';

                await this.loadProducts();
                await this.loadOrders();
                this.updateStats();
                
                // Reinitialize icons after DOM updates
                setTimeout(() => lucide.createIcons(), 100);
            },

            async loadProducts() {
                try {
                    const response = await axios.get('/products');
                    this.products = response.data.data.data || response.data.data;
                } catch (error) {
                    alert('Failed to load products');
                }
            },

            async loadOrders() {
                try {
                    const response = await axios.get('/orders');
                    this.orders = response.data.data.data || response.data.data;
                } catch (error) {
                    console.error('Failed to load orders:', error);
                }
            },

            updateStats() {
                this.stats.totalProducts = this.products.length;
                this.stats.totalOrders = this.orders.length;
                this.stats.pendingOrders = this.orders.filter(o => o.status === 'pending').length;
                this.stats.totalRevenue = this.orders
                    .filter(o => o.status === 'completed')
                    .reduce((sum, o) => sum + parseFloat(o.total_amount), 0);
            },

            openProductModal(product = null) {
                if (product) {
                    this.productForm = {...product};
                } else {
                    this.productForm = {
                        id: null,
                        name: '',
                        description: '',
                        price: 0,
                        stock: 0,
                        category: 'coffee',
                        image_url: '',
                        is_available: true
                    };
                }
                this.showProductModal = true;
            },

            async saveProduct() {
                try {
                    if (this.productForm.id) {
                        await axios.put(`/products/${this.productForm.uuid}`, this.productForm);
                    } else {
                        await axios.post('/products', this.productForm);
                    }
                    this.showProductModal = false;
                    await this.loadProducts();
                    this.updateStats();
                    alert('Product saved successfully!');
                } catch (error) {
                    alert('Failed to save product: ' + (error.response?.data?.message || error.message));
                }
            },

            async deleteProduct(uuid) {
                if (!confirm('Are you sure you want to delete this product?')) return;

                try {
                    await axios.delete(`/products/${uuid}`);
                    await this.loadProducts();
                    this.updateStats();
                    alert('Product deleted successfully!');
                } catch (error) {
                    alert('Failed to delete product');
                }
            },

            openOrderModal() {
                this.orderForm = {
                    customer_name: '',
                    customer_email: '',
                    customer_phone: '',
                    items: [{product_id: '', quantity: 1}],
                    notes: ''
                };
                this.showOrderModal = true;
            },

            async saveOrder() {
                try {
                    await axios.post('/orders', this.orderForm);
                    this.showOrderModal = false;
                    await this.loadOrders();
                    await this.loadProducts(); // Refresh to update stock
                    this.updateStats();
                    alert('Order created successfully!');
                } catch (error) {
                    alert('Failed to create order: ' + (error.response?.data?.message || error.message));
                }
            },

            async updateOrderStatus(order) {
                const statuses = ['pending', 'processing', 'completed', 'cancelled'];
                const currentIndex = statuses.indexOf(order.status);
                const nextStatus = statuses[(currentIndex + 1) % statuses.length];

                if (!confirm(`Update order status to "${nextStatus}"?`)) return;

                try {
                    await axios.put(`/orders/${order.uuid}`, { status: nextStatus });
                    await this.loadOrders();
                    await this.loadProducts(); // Refresh to update stock if cancelled
                    this.updateStats();
                    alert('Order status updated!');
                } catch (error) {
                    alert('Failed to update order status');
                }
            },

            async deleteOrder(uuid) {
                if (!confirm('Are you sure you want to delete this order? Stock will be restored.')) return;

                try {
                    await axios.delete(`/orders/${uuid}`);
                    await this.loadOrders();
                    await this.loadProducts(); // Refresh to update stock
                    this.updateStats();
                    alert('Order deleted successfully!');
                } catch (error) {
                    alert('Failed to delete order: ' + (error.response?.data?.message || error.message));
                }
            },

            formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            },

            logout() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '{{ route("login") }}';
            }
        }
    }
</script>
@endpush
@endsection
