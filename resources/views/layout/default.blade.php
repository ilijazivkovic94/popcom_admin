<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }} {{ Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('media/logos/logo.png') }}?v={{ config('constants.WEB_VERSION') }}" />

        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}?v={{ config('constants.WEB_VERSION') }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (Metronic::initThemes() as $theme)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}?v={{ config('constants.WEB_VERSION') }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Includable CSS --}}
        @yield('styles')
        @toastr_css

        <script type="text/javascript">
            var baseurl = "<?php echo url('/'); ?>";
        </script>
    </head>

    <body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @if (config('layout.page-loader.type') != '') 
            @include('layout.partials._page-loader')
        @endif

        @include('layout.base._layout')

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
        @endforeach

        <!-- Common Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="common_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="modal_content">
                </div>
            </div>
        </div>

        {{-- Includable JS --}}
        @yield('scripts')
        
        @toastr_js
        @toastr_render

        <script src="{{url('plugins/jquery-validation/jquery.validate.js')}}" type="text/javascript"></script>
        <script src="{{url('plugins/jquery-validation/additional-methods.js')}}" type="text/javascript"></script>
        
        <script type="text/javascript">
            jQuery("#kt_header_mobile button#kt_header_mobile_topbar_toggle1").click(function(e){
                e.preventDefault();
                // jQuery(".topbar-mobile-on.header-mobile-fixed .topbar").css('margin-top','0');
                jQuery("#kt_quick_user_toggle").trigger('click');
                jQuery(this).removeClass('active');
            });
        </script>
        
        <script src="{{url('js/custom.js')}}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    </body>
</html>

