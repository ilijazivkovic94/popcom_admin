{{-- Aside --}}

@php
    $kt_logo_image = 'logo.png';
@endphp

<div class="aside aside-left {{ Metronic::printClasses('aside', false) }} d-flex flex-column flex-row-auto" id="kt_aside">

    {{-- Brand --}}
    <div class="brand flex-column-auto {{ Metronic::printClasses('brand', false) }}" id="kt_brand">
        <div class="brand-logo">
            <a href="{{ url('/home') }}">
                <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/'.$kt_logo_image) }}"/>
            </a>
        </div>

        @if (config('layout.aside.self.minimize.toggle'))
            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                {{ Metronic::getSVG("media/svg/icons/Navigation/Angle-double-left.svg", "svg-icon-xl") }}
            </button>
        @endif        

    </div>

    {{-- <div class="user-info pt-2 px-4 mx-3">
        <div class="d-flex flex-column">
            <div>
                <span class="text-white font-size-base d-none d-md-inline mr-1">Hi,</span>
                <span class="text-white font-size-base d-none d-md-inline">{{Auth::user()->user_fname}} {{Auth::user()->user_lname}}</span>
            </div>
            
            @if(Session::has('showAdminBtn') && Session::get("showAdminBtn") == 'Yes')
            <div class="mt-1">
                <a href="{{url('access-login/1')}}" class="text-white"><i class="fa fa-chevron-left text-white" aria-hidden="true" style="top: 2px;position: relative;"></i> Back to Admin</a>
            </div>
            @endif
        </div>
    </div> --}}
  

    {{-- Aside menu --}}
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        @if (config('layout.aside.self.display') === false)
            <div class="header-logo">
                <a href="{{ url('/home') }}">
                    <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/'.$kt_logo_image) }}"/>
                </a>
            </div>
        @endif

        <div id="kt_aside_menu" class="aside-menu my-4 {{ Metronic::printClasses('aside_menu', false) }}"  data-menu-vertical="1" {{ Metronic::printAttrs('aside_menu') }}>

            <ul class="menu-nav {{ Metronic::printClasses('aside_menu_nav', false) }}">
                
                @if(Session::has('showAdminBtn') && Session::get("showAdminBtn") == 'Yes')
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{url('access-login/1')}}" class="menu-link "><i class="menu-icon fa fa-chevron-left"></i><span class="menu-text">Back to Admin</span></a>
                </li>
                @endif

                <li class="menu-item" aria-haspopup="true">
                    @if (Auth::user()->user_admin_yn == 'Y')
                        <span class="menu-link text-white hoveClassRemove" style="padding: 0px 25px 10px;"><span class="menu-text">Hi, {{Auth::user()->email}}!</span></span>
                    @else
                        <span class="menu-link text-white hoveClassRemove" style="padding: 0px 25px 10px;"><span class="menu-text">Hi, {{Auth::user()->user_fname}} {{Auth::user()->user_lname}}!</span></span>
                    @endif                    
                </li>

                @if (Auth::user()->user_admin_yn == 'Y')
                    {{ Menu::renderVerMenu(config('menu_aside.itemsAdmin')) }}  
                @else
                    @if ($settingFlag == 1)
                        @if (Auth::user()->accountDetails->account_type == 'std')
                            {{ Menu::renderVerMenu(config('menu_aside.itemsStandard')) }}     
                        @else
                            @if (Auth::user()->accountDetails->account_type == 'ent')
                                {{ Menu::renderVerMenu(config('menu_aside.itemsParent')) }}
                            @else
                                {{ Menu::renderVerMenu(config('menu_aside.itemsSubParent')) }}
                            @endif                            
                        @endif                                            
                    @else
                        @if($subscriptionFlag == 1)
                            {{ Menu::renderVerMenu(config('menu_aside.itemsAccount')) }}  
                        @else
                            {{ Menu::renderVerMenu(config('menu_aside.itemsSetting')) }}  
                        @endif 
                    @endif

                @endif                
            </ul>

            <div class="custom-left-sidebar-box">
                {{-- Copyright --}}
                <div class="text-dark-f order-2 order-md-1">
                    <span>{{ config('constants.APP_VERSION') }}</span>
                </div>
            </div>
        </div>

    </div>

</div>