@if(!isset($is_update) || !$is_update)

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
@if(!isset($is_update) || !$is_update)

    @include('footer')
    @include('foot')

@endif
