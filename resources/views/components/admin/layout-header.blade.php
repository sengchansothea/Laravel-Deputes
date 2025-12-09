<div class="page-top-header">
	<div class="app-brand">
		<img class="img-fluid logo-icon" src="{{ rurl('assets/images/logo2.png') }}" />
	</div>
</div>
<div class="page-main-header">
	<div class="main-header-right row">
		<!-- <div class="main-header-left col-auto px-0 d-lg-none">
			<div class="logo-wrapper">
				<a href="index.html">
					SICMS
				</a>
			</div>
		</div> -->

		<div class="vertical-mobile-sidebar col-auto ps-3 d-none">
			<i class="fa fa-bars sidebar-bar"></i>
		</div>

		<div class="d-flex">
			<div class="mobile-sidebar col-auto ps-0 d-block align-self-center">
				<div class="media-body switch-sm">
					<label class="switch switch-sidebar"><a href="#"><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
				</div>
			</div>
			<div class="align-self-center fs-sm-4">
				<div class="text-moul app-name">ប្រព័ន្ធគ្រប់គ្រងបណ្ដឹងនៃនាយកដ្ឋានវិវាទការងារ</div>
			</div>
			<div class="nav-right col p-0 d-flex">
				<ul class="nav-menus">
					<li class="onhover-dropdown d-flex">
						<i class="icofont icofont-user-alt-4 me-1"></i>
						<span>
							@auth
                                @if(Auth::user()->k_team == 0)
								    {{ Auth::user()->k_fullname }}
                                @else
                                    {{ Auth::user()->company->company_name_khmer }}
                                @endif
							@endauth
						</span>

						<ul class="profile-dropdown onhover-show-div p-20">
							<li>
								<a href="#"
								   onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
									<i data-feather="log-out"></i>ចាកចេញ</a>
								</a>
								<form id="form-logout" method="post" action="{{ url('logout') }}"  style="display: none">
									@csrf
								</form>
							</li>
						</ul>
					</li>
				</ul>
				<div class="d-lg-none mobile-toggle pull-right">
					<i data-feather="more-horizontal"></i>
				</div>
			</div>
		</div>

		<script id="result-template" type="text/x-handlebars-template">
			<div class="ProfileCard u-cf">
				<div class="ProfileCard-avatar">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
				</div>
				<div class="ProfileCard-details">
					<div class="ProfileCard-realName"></div>
				</div>
			</div>
		</script>
		<script id="empty-template" type="text/x-handlebars-template">
			<div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
		</script>
	</div>
</div>
