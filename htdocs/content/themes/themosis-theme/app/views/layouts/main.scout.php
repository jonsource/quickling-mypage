@if($first_load)
	
	@include('head')

    @include('menu')

@endif
<div class="ql-container section">
    <div class="container">
        <div class="ql-cont col-70 txt">

            @yield('main')

        </div>


        <div class="ql-sidebar col-30 txt">

            @yield('sidebar')

        </div>

    </div>
</div>
@if($first_load)

    @include('footer')
	@include('foot')
	
@endif
