@if($first_load)

    @include('head')

    @include('menu')

@endif
    <div class="ql-container section gold" data-ql-name="gold">
        <div class="container">
            <div class="col-100 txt">
                    <h1>{{$post->post_title}}</h1>
                    <p>{{$post->post_content}}</p>
            </div>
        </div>
    </div>
@if($first_load)

    @include('footer')
    @include('foot')

@endif
