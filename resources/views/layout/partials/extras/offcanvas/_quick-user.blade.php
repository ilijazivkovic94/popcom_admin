@php
	$direction = config('layout.extras.user.offcanvas.direction', 'right');
@endphp
 {{-- User Panel --}}
<div id="kt_quick_user" class="offcanvas offcanvas-{{ $direction }} p-10">
	{{-- Header --}}
	<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
		<h3 class="font-weight-bold m-0">
			User Profile
			<!-- <small class="text-muted font-size-sm ml-2">12 messages</small> -->
		</h3>
		<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
			<i class="ki ki-close icon-xs text-muted"></i>
		</a>
	</div>

	{{-- Content --}}
    <div class="offcanvas-content pr-5 mr-n5">
		{{-- Header --}}
        <div class="d-flex align-items-center mt-5">
            <!-- <div class="symbol symbol-100 mr-5">
                <div class="symbol-label" style="background-image:url('{{ asset('media/users/300_21.jpg') }}')"></div>
				<i class="symbol-badge bg-success"></i>
            </div> -->
            <div class="d-flex flex-column">
                <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
					{{Auth::user()->user_firstname}} {{Auth::user()->user_lastname}}
				</a>
                <!-- <div class="text-muted mt-1">
                    Application Developer
                </div> -->
                <div class="navi mt-2">
                    <a href="#" class="navi-item">
                        <span class="navi-link p-0 pb-2">
                            <span class="navi-icon mr-1">
								{{ Metronic::getSVG("media/svg/icons/Communication/Mail-notification.svg", "svg-icon-lg svg-icon-primary") }}
							</span>
                            <span class="navi-text text-muted text-hover-primary">{{Auth::user()->email}}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>

		{{-- Separator --}}
		<div class="separator separator-dashed mt-8 mb-5"></div>

        {{-- Nav --}}
        <div class="navi navi-spacer-x-0 p-0">
            {{-- Item --}}
            <a href="{{url('account')}}" class="navi-item">
                <div class="navi-link">
                    <div class="symbol symbol-40 bg-light mr-3">
                        <div class="symbol-label">
                            <i class="menu-icon far fa-user-circle color-white"></i>
                        </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            My Account
                        </div>
                        <div class="text-muted">
                            Change Password
                            <!-- <span class="label label-light-danger label-inline font-weight-bold">update</span> -->
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{url('logout')}}" class="navi-item cursor-pointer" id="logout">
                <div class="navi-link">
                    <div class="symbol symbol-40 bg-light mr-3">
                        <div class="symbol-label">
                           <i class="menu-icon fas fa-lock color-white"></i>
                       </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            Logout
                        </div>
                        <div class="text-muted">
                            <!-- Inbox and tasks -->
                        </div>
                    </div>
                </div>
            </a>
        {{-- Separator --}}
        <div class="separator separator-dashed my-7"></div>
    </div>
</div>
