@if(!isset($is_update) || !$is_update)
	
	@include('head')

    @include('menu')

@endif
<div class="ql-container section light" data-ql-target="true" data-ql-name="section">
    <div class="container">
        <div class="ql-cont col col-70 txt" data-ql-target="true" data-ql-name="cont">

            @yield('main')

        </div>


        <div class="ql-sidebar col col-30 txt" data-ql-target="true" data-ql-name="sidebar" data-ql-transition="fadeInLeft/fadeOutRight">

            @yield('sidebar')

        </div>

    </div>
</div>
@if(!isset($is_update) || !$is_update)

    @include('footer')
	@include('foot')
	
@endif
