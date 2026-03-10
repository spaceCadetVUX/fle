<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Category') }}
            </h2>
            <a href="{{ route('admin.categories') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Category Information</h3>

                    <div class="space-y-6">
                        {{-- Category Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Parent Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select name="parent_id" id="parent_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- None (Root Category) --</option>
                                @foreach($parentCategories ?? [] as $parent)
                                    @php /** @var \App\Models\Category $parent */ @endphp
                                    <option value="{{ $parent->id }}"
                                            data-type="{{ $parent->type }}"
                                            {{ old('parent_id', $parentId ?? '') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                        @if($parent->type === 'color') 🎨 @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave empty to create a root category, or select a parent for subcategory</p>
                        </div>

                        {{-- Category Type — only shown for root (no parent) --}}
                        <div id="type_field">
                            <label class="block text-sm font-medium text-gray-700">Category Type</label>
                            <select name="type" id="type_select"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="default" {{ old('type', request('type', 'default')) === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="color"   {{ old('type', request('type', 'default')) === 'color'   ? 'selected' : '' }}>🎨 Color Filter (children have color swatches)</option>
                                <option value="size"    {{ old('type', request('type', 'default')) === 'size'    ? 'selected' : '' }}>📐 Size Filter (children render as size buttons)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Only applies to root categories.</p>
                        </div>

                        {{-- Color Code — shown when parent is a color category, OR when creating a color root --}}
                        <div id="color_code_field" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700">Color Code (hex or rgb)</label>
                            <div class="mt-1 flex items-center gap-3">
                                <input type="color" id="color_picker" value="#000000"
                                       class="h-10 w-14 rounded cursor-pointer border border-gray-300 p-0.5"
                                       title="Pick a color">
                                <input type="text" name="color_code" id="color_code_input"
                                       value="{{ old('color_code') }}"
                                       placeholder="#2e2e2e or rgb(46,46,46)"
                                       class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">
                                @php $previewBg = old('color_code', '#cccccc'); @endphp
                                <div id="color_preview" class="w-10 h-10 rounded-full border border-gray-300 flex-shrink-0"
                                     @style(['background: ' . $previewBg])></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                The <strong>slug</strong> (auto-generated from the name) is used in filter URLs. The color code is only for display.
                            </p>
                            @error('color_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Display Order --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="status" value="1" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active (available for product selection)</span>
                            </label>
                        </div>

                        {{-- Display on Home --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="display_on_home" value="1"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Display on Home Page</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="bg-white shadow-sm rounded-lg p-6 mt-4">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.categories') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Create Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- JS to toggle color_code field and sync color picker --}}
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

        // Sync text → picker → preview
        colorInput.addEventListener('input', function () {
            syncPreview(this.value);
            // Try to set picker value (only works for 6-digit hex)
            if (/^#[0-9a-fA-F]{6}$/.test(this.value)) colorPicker.value = this.value;
        });
        colorPicker.addEventListener('input', function () {
            colorInput.value = this.value;
            syncPreview(this.value);
        });

        // Prefill if old value exists
        if (colorInput.value) syncPreview(colorInput.value);

        function updateVisibility() {
            var selectedOption = parentSel.options[parentSel.selectedIndex];
            var parentType = selectedOption ? selectedOption.getAttribute('data-type') : null;
            var hasParent = !!parentSel.value;

            // Type selector only makes sense for root categories
            typeField.style.display = hasParent ? 'none' : '';

            // Show color_code input when:
            //   (a) parent is a color-type category, OR
            //   (b) no parent AND type selector = color
            var parentIsColor = (parentType === 'color');
            var typeIsColor   = (typeSel.value === 'color');

            colorField.style.display = (parentIsColor || (!hasParent && typeIsColor)) ? '' : 'none';
        }

        parentSel.addEventListener('change', updateVisibility);
        typeSel.addEventListener('change', updateVisibility);

        // Run on load (handles old() values after validation fail)
        updateVisibility();
    })();
    </script>
</x-app-layout>
