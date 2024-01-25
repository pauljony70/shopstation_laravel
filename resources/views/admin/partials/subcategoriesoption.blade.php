@if (count($subcategories) > 0)
    <ul>
        @foreach ($subcategories as $subcategory)
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="selected_category"
                        id="selected_category{{ $subcategory->id }}" value="{{ $subcategory->id }}">
                    <label class="form-check-label"
                        for="selected_category{{ $subcategory->id }}">{{ $subcategory->cat_name }}</label>
                </div>
                @if ($subcategory->children && count($subcategory->children) > 0)
                    @include('admin.partials.subcategoriesoption', [
                        'subcategories' => $subcategory->children,
                    ])
                @endif
            </li>
        @endforeach
    </ul>
@endif
