<ul class="menu-responsive-list">
    <li class="menu-responsive-item">
        <a class = "menu-responsive-link" href="{{ url('/') }}">{{ $list_name_menu_one->name_menu }}</a>
    </li>
    <li class="menu-responsive-item">
        <a class = "menu-responsive-link" href="{{ url('danh-sach-san-pham') }}">{{ $list_name_menu_two->name_menu }}</a>
    </li>
    @foreach($list_menus as $item)
    <li class="menu-responsive-item">
        <a class = "menu-responsive-link" href="{{ route('page', $item->slug) }}">{{ $item->title }}</a>
    </li>
    @endforeach
    <li class="menu-responsive-item">
        <a class = "menu-responsive-link" href="{{ url('bai-viet')}}">{{ $list_name_menu_tree->name_menu }}</a>
    </li>
</ul>
