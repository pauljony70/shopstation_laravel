@if (count($subcategories) > 0)
    <ul>
        @foreach ($subcategories as $subcategory)
            <li>
                <input type="radio" name="selected_category" value="{{ $subcategory->id }}">
                {{ $subcategory->cat_name }}
                @if ($subcategory->children && count($subcategory->children) > 0)
                    @include('admin.partials.subcategoriesoption', ['subcategories' => $subcategory->children])
                @endif
            </li>
        @endforeach
    </ul>
@endif