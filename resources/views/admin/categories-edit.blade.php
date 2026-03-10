<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Category') }}: {{ $category->name }}
            </h2>
            <a href="{{ route('admin.categories') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                @csrf
                @method('PUT')

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Category Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category Name *</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" value="{{ $category->slug }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            <p class="mt-1 text-xs text-gray-500">Slug is auto-generated from category name</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- None (Root Category) --</option>
                                @foreach($parentCategories ?? [] as $parent)
                                    <option value="{{ $parent->id }}"
                                            data-type="{{ $parent->type }}"
                                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                        @if($parent->type === 'color') 🎨 @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            @if($category->children()->count() > 0)
                                <p class="mt-1 text-xs text-amber-600">⚠ This category has {{ $category->children()->count() }} subcategories</p>
                            @endif
                        </div>

                        {{-- Category Type (root only) --}}
                        <div id="type_field">
                            <label class="block text-sm font-medium text-gray-700">Category Type</label>
                            <select name="type" id="type_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="default" {{ old('type', $category->type ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="color"   {{ old('type', $category->type ?? 'default') === 'color'   ? 'selected' : '' }}>🎨 Color Filter</option>
                                <option value="size"    {{ old('type', $category->type ?? 'default') === 'size'    ? 'selected' : '' }}>📐 Size Filter</option>
                            </select>
                        </div>

                        {{-- Color Code --}}
                        <div id="color_code_field" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700">Color Code (hex or rgb)</label>
                            <div class="mt-1 flex items-center gap-3">
                                <input type="color" id="color_picker"
                                       value="{{ old('color_code', $category->color_code && preg_match('/^#[0-9a-fA-F]{6}$/', $category->color_code) ? $category->color_code : '#000000') }}"
                                       class="h-10 w-14 rounded cursor-pointer border border-gray-300 p-0.5">
                                <input type="text" name="color_code" id="color_code_input"
                                       value="{{ old('color_code', $category->color_code) }}"
                                       placeholder="#2e2e2e or rgb(46,46,46)"
                                       class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">
                                @php
                                    $previewBg = old('color_code', $category->color_code ?? '#cccccc');
                                @endphp
                                <div id="color_preview" class="w-10 h-10 rounded-full border border-gray-300 flex-shrink-0"
                                     @style(['background: ' . $previewBg])></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">The <strong>slug</strong> is used in filter URLs. The color code is only for display.</p>
                            @error('color_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', $category->order) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active (available for product selection)</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="display_on_home" value="1" {{ old('display_on_home', $category->display_on_home) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Display on Home Page (e.g., in "Women" / "Men" sections)</span>
                            </label>
                        </div>

                        <div class="border-t pt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <strong>Total Products:</strong> {{ $category->products()->count() }}
                                </div>
                                <div>
                                    <strong>Subcategories:</strong> {{ $category->children()->count() }}
                                </div>
                                <div>
                                    <strong>Created:</strong> {{ $category->created_at->format('M d, Y') }}
                                </div>
                                <div>
                                    <strong>Path:</strong> {{ $category->path }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="bg-white shadow-sm rounded-lg p-6 mt-4">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.categories') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Update Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
(function () {
    var parentSel   = document.getElementById('parent_id');
    var typeField   = document.getElementById('type_field');
    var typeSel     = document.getElementById('type_select');
    var colorField  = document.getElementById('color_code_field');
    var colorInput  = document.getElementById('color_code_input');
    var colorPicker = document.getElementById('color_picker');
    var colorPreview= document.getElementById('color_preview');

    function syncPreview(val) {
        if (colorPreview && val) colorPreview.style.background = val;
    }

    colorInput.addEventListener('input', function () {
        syncPreview(this.value);
        if (/^#[0-9a-fA-F]{6}$/.test(this.value)) colorPicker.value = this.value;
    });
    colorPicker.addEventListener('input', function () {
        colorInput.value = this.value;
        syncPreview(this.value);
    });

    if (colorInput.value) syncPreview(colorInput.value);

    function updateVisibility() {
        var selectedOption = parentSel.options[parentSel.selectedIndex];
        var parentType = selectedOption ? selectedOption.getAttribute('data-type') : null;
        var hasParent = !!parentSel.value;

        typeField.style.display = hasParent ? 'none' : '';

        var parentIsColor = (parentType === 'color');
        var typeIsColor   = (typeSel.value === 'color');
        colorField.style.display = (parentIsColor || (!hasParent && typeIsColor)) ? '' : 'none';
    }

    parentSel.addEventListener('change', updateVisibility);
    typeSel.addEventListener('change', updateVisibility);
    updateVisibility();
})();
</script>
