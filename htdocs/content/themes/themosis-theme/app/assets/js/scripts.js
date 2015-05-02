$ = jQuery;

function resizeSection($e) {
    var $c = $($e.find('.container')[0]);
    console.log([$c,$c.height()]);
    $e.css('height',$c.height());
}

function resizeSections() {
    $('.section').each(function(i,e) {
        resizeSection($(e));
    });
}

function onPopState(event) {
    console.log(event);
    Quickling.followLink(event.state);
}

function pretty(event,$data) {
    console.log(['pretty',$data]);
    var $pres = $data.find('pre.prettyprint');
    $pres.each(function (i, e) {
        $e = $(e);
        $e.html(PR.prettyPrintOne($e.html()));
    });
}

$(function() {
    Quickling.init({debug:true,root:quickling_root});
    Quickling.$.on('data.postLoad',pretty);
    Quickling.getEm($('body'));

    window.onpopstate = onPopState;
    //resizeSections();
    $(window).resize(resizeSections);
});

$(window).load(function(){
    resizeSections();
})