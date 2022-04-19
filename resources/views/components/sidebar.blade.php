<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Rukada</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @if(Session::has('menu'))
            @foreach(Session::get('menu') as $menu)
                @if($menu->children->isEmpty())
                    @if($menu->type == 1)
                    <li class="menu-label">{{ $menu->divider_title }}</li>
                    @else
                    <li {{ request()->is($menu->url) ? 'class="mm-active"' : '' }}>
                        <a href="{{ $menu->url ? url($menu->url) : 'javascript.void();' }}">
                            <div class="parent-icon"><i class='{{ $menu->icon_class }}' style="font-size: 16px;"></i>
                            </div>
                            <div class="menu-title">{{ $menu->module_name }}</div>
                        </a>
                    </li>
                    @endif
                @else
                <li class="@foreach($menu->children as $submenu)
                    {{ request()->is($submenu_url) ? 'mm-active' : '' }}
                    @endforeach">
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='{{ $menu->icon_class }}' style="font-size: 16px;"></i>
                        </div>
                        <div class="menu-title">{{ $menu->module_name }}</div>
                    </a>
                    <ul class="
                    @foreach($menu->children as $submenu)
                    {{ request()->is($submenu_url) ? 'mm-show mm-collapse' : '' }}
                    @endforeach
                    ">
                        @foreach($menu->children as $submenu)
                        <li class="@foreach($menu->children as $submenu)
                            {{ request()->is($submenu_url) ? 'mm-active' : '' }}
                            @endforeach"> <a href="{{ $submenu->url ? url($submenu->url) : '' }}"><i class="{{ $submenu->icon_class }}" style="font-size: 16px;"></i>{{ $submenu->module_name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
            @endforeach
        @endif
    </ul>
    <!--end navigation-->
</div>