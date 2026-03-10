<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Root Categories') }}
            </h2>
            <div class="flex items-center gap-2">
                {{-- Standard root category --}}
                <a href="{{ route('admin.categories.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Root Category
                </a>
                {{-- Color root category — pre-fills type=color in create form --}}
                <a href="{{ route('admin.categories.create', ['type' => 'color']) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    🎨 Add Color Category
                </a>
                <button type="button" onclick="document.getElementById('reorderModal').classList.remove('hidden')"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    Reorder Roots
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600">Total Categories</div>
                    <div class="text-2xl font-bold">{{ \App\Models\Category::count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600">Active Categories</div>
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\Category::where('status', 1)->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600">Root Categories</div>
                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Category::whereNull('parent_id')->count() }}</div>
                </div>
            </div>

            <!-- Root Categories List -->
            @forelse($categories as $rootCategory)
                @php
                    $isColor = $rootCategory->type === 'color';
                    $isSize  = $rootCategory->type === 'size';
                @endphp
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <!-- Root Category Header -->
                    <div class="p-6 border-b {{ $isColor ? 'border-pink-200 bg-pink-50' : 'border-blue-100' }}">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        @if($isColor)
                                            <i class="fas fa-palette text-pink-500"></i>
                                        @else
                                            <i class="fas fa-folder text-blue-600"></i>
                                        @endif
                                        {{ $rootCategory->name }}
                                        @if($isColor)
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-pink-100 text-pink-700">Color Filter</span>
                                        @elseif($isSize)
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-purple-100 text-purple-700">Size Filter</span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $rootCategory->description }}</p>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Order: {{ $rootCategory->order }} |
                                        Subcategories: {{ $rootCategory->children->count() }} |
                                        Products: {{ $rootCategory->products()->count() }} |
                                        On Home: {!! $rootCategory->display_on_home ? '<span class="text-blue-600 font-semibold">Yes</span>' : 'No' !!}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($rootCategory->status)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                                <a href="{{ route('admin.categories.edit', $rootCategory->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.categories.delete', $rootCategory->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete this root category and all its subcategories?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Child Categories -->
                    <div class="p-6 {{ $isColor ? 'bg-pink-50' : 'bg-blue-50' }}">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-semibold text-gray-700">
                                {{ $isColor ? '🎨 Color Swatches' : 'Subcategories' }}
                            </h4>
                            <a href="{{ route('admin.categories.create', ['parent' => $rootCategory->id]) }}"
                               class="inline-flex items-center px-3 py-1 {{ $isColor ? 'bg-pink-600 hover:bg-pink-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ $isColor ? 'Add Color Swatch' : 'Add Subcategory' }}
                            </a>
                        </div>

                        @if($rootCategory->children->count() > 0)
                            @if($isColor)
                                {{-- Color swatches preview row --}}
                                <div class="flex flex-wrap gap-3 mb-4">
                                    @foreach($rootCategory->children->sortBy('order') as $child)
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="w-8 h-8 rounded-full border-2 border-white shadow"
                                                 @style(['background-color: ' . ($child->color_code ?: '#ccc')])
                                                 title="{{ $child->name }} ({{ $child->color_code }})"></div>
                                            <span class="text-xs text-gray-600">{{ $child->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-blue-90">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                            @if($isColor)
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Color</th>
                                            @endif
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">On Home</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($rootCategory->children->sortBy('order') as $child)
                                            <tr class="hover:bg-gray-50 bg-blue-50">
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                                        @if($isColor && $child->color_code)
                                                            <span class="inline-block w-4 h-4 rounded-full border border-gray-200 flex-shrink-0"
                                                                  @style(['background-color: ' . $child->color_code])></span>
                                                        @else
                                                            <i class="fas fa-angle-right text-gray-400"></i>
                                                        @endif
                                                        {{ $child->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $child->slug }}</div>
                                                </td>
                                                @if($isColor)
                                                    <td class="px-4 py-3">
                                                        @if($child->color_code)
                                                            <div class="flex items-center gap-2">
                                                                <span class="inline-block w-6 h-6 rounded-full border border-gray-300"
                                                                      @style(['background-color: ' . $child->color_code])></span>
                                                                <code class="text-xs text-gray-600">{{ $child->color_code }}</code>
                                                            </div>
                                                        @else
                                                            <span class="text-xs text-gray-400 italic">no color</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $child->products()->count() }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $child->order }}</td>
                                                <td class="px-4 py-3">
                                                    @if($child->status)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($child->display_on_home)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Yes</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm font-medium">
                                                    <a href="{{ route('admin.categories.edit', $child->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                    <form action="{{ route('admin.categories.delete', $child->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this subcategory?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">
                                No {{ $isColor ? 'color swatches' : 'subcategories' }} yet.
                                Click "{{ $isColor ? 'Add Color Swatch' : 'Add Subcategory' }}" to create one.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-center text-gray-500">
                        No root categories found. <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:text-blue-800">Create your first root category</a>
                    </p>
                </div>
            @endforelse
        </div>
    </div>
    </div>

    <!-- Reorder Modal -->
    <div id="reorderModal" class="fixed inset-0 z-50 hidden overflow-y-auto h-full w-full flex items-center justify-center" style="background-color: rgba(0,0,0,0.5)">
        <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Drag to Reorder Root Categories</h3>
                <div class="mt-4 text-left p-2 rounded max-h-64 overflow-y-auto bg-gray-50 border border-gray-200" id="sortableList">
                    @foreach($categories as $root)
                        <div data-id="{{ $root->id }}" class="cursor-move p-3 border-b border-gray-100 bg-white mb-1 rounded shadow-sm flex items-center gap-3 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <span class="text-sm font-medium text-gray-700">{{ $root->name }}</span>
                        </div>
                    @endforeach
                </div>
                <form id="reorderForm" action="{{ route('admin.categories.reorder') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ordered_ids" id="orderedIdsInput">
                    <div class="items-center px-4 py-3 mt-4 flex gap-2 justify-center">
                        <button type="button" onclick="document.getElementById('reorderModal').classList.add('hidden')"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 text-sm font-medium rounded-md w-full shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Save Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Load SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('sortableList');
            if(el) {
                var sortable = Sortable.create(el, {
                    animation: 150,
                    ghostClass: 'bg-indigo-50'
                });

                var form = document.getElementById('reorderForm');
                form.addEventListener('submit', function(e) {
                    var order = sortable.toArray();
                    document.getElementById('orderedIdsInput').value = order.join(',');
                });
            }
        });
    </script>
</x-app-layout>
