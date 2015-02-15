console.log('QuickLing');

$ = jQuery;
$.fn.redraw = function(){ return $(this).each(function(){ var redraw = this.offsetHeight; }); };

QL_cache = {};


function addTransIfNeeded($data) {
    if ($data.length === 1 && $($data[0]).hasClass("ql-trans-container")) {
        return $data;
    } else {
        return $('<div class="ql-trans-container">').append($data);
    }
}

function prepareData($data,$target)
{   if($target.ql_exact) {
        var $node = $data.find($target.ql_exact);
        if($node.length) {
            $data = $node;
            console.log(['exact data',$data]);
        }
    }
    var encapsulated = [];
    $data.each(function(i,e) {
        //console.log(e);
        var $e = $(e);
        if($e.data('ql-target')) {
            encapsulated.push($e);
        };
    });
    if(!encapsulated.length) {
        encapsulated.push($data);
    }
    return encapsulated;
}

function applyIt($target,$data) {
    console.log(['apply it',$target,$data]);
    if(!$target.data('ql-transition') ) {
        tr_in = 'fadeIn';
        tr_out = 'fadeOut';
    } else {
        tr_in = $target.data('ql-transition').replace('%i%','In');
        tr_out = $target.data('ql-transition').replace('%i%','Out');
    }

    var $anchors = prepareData($data,$target); /* TODO: handle more anchor points */
    console.log($anchors);
    $.each($anchors,function(i,$new) {

        var $wrap = $target.wrapAll('<div class="transition-wrap">').parent();

        $target.addClass('animated ' + tr_out);
        $target.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $target.remove();
            $wrap.append($new);
            getEm($wrap);
            $new.addClass('animated '+tr_in);
            $new.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $new.removeClass('animated');
                $new.unwrap();
            });
        });
    });
}

function addLoader($target) {
    console.log('add loader',$target);
    if(!$target.ql_loader) {
        var $bar = $('<div class="bar"></div>');
        var $loader = $('<div class="ql-loader"></div>');
        $bar.width('0%');
        $target.prepend($loader.append($bar))
        $target.ql_loader = $loader;
        $target.ql_loader.bar = $bar;
    }
}

function updateLoader($target) {
    //console.log('update loader');
    var progress = parseInt($target.ql_loader.bar[0].style.width);
    var add = (100 - progress) / 5;
    //console.log([progress,add]);
    moveLoader($target,progress+add);
    //$target.ql_loader.bar.css('width',progress+add+'%');
}

function moveLoader($target,percent) {
    $target.ql_loader.bar.css('width',percent+'%');
}

function startLoader($target) {
    //console.log('start loader');
    setTimeout(function() { updateLoader($target) }, 1);
    $target.ql_loader.interval = setInterval(function() {
        updateLoader($target);
    },300);
}

function removeLoader($target) {
    //console.log('remove loader');
    $target.ql_loader.bar.css('height','0');
    setTimeout(function() {
        $target.ql_loader.remove();
        $target.ql_loader = null;
    },350);
}

function finishLoader($target) {
    //console.log('finish loader');
    clearInterval($target.ql_loader.interval);
    setTimeout(function() { $target.ql_loader.bar.css('width','100%'); },1 );
    setTimeout(function() {
        //removeLoader($target);
    },350);
}

function loadIt(href,cont)
{   var $target = $(cont);
    var instead;
    if(!$target || !$target.length) {
        $target=$('.ql-container');
        instead='.ql-container';
        if(!$target.length) {
            $target=$('body');
            instead='body';
        }
        console.log('QL: No element ('+cont+') found. Using ('+instead+')');
        $target.ql_exact = false;
    } else {
        /*if($target.length>1) {
            $target = $($target[0]);
        }*/
        $target.ql_exact = cont;
        console.log('target '+cont);
    }

    //console.log($target);

    if(QL_cache[href])
    {	console.log('using cache for: '+href);
        applyIt($target, $(QL_cache[href]));
    }
    else
    {	console.log('getting: '+href);
        addLoader($target);
        startLoader($target);
        $.get(href, function(data) {
            finishLoader($target);
            QL_cache[href]=data;
            applyIt($target, $(QL_cache[href]));
        });
    }
}

function followLink(href,$tgt) {
    var cont;
    if($tgt && (cont = $tgt.data('ql-container'))) {
        loadIt(href,cont);
    } else {
        loadIt(href);
    }
    return false;
}

function getEm($scope) {
    console.log('getEm');
    $as = $scope.find('a');
    $as.each(function() {
        //console.log([$scope,this,$scope.selector]);
        if(!$(this).data('ql-container')) {
            var cls=$scope.attr('class');
            if(cls) {
                $(this).data('ql-container', '.' + cls.split(' ').join('.'));
            } else {
                console.log(['scope without class',$scope]);
            }
        }
    });

    prepareMenu($scope);

    $as.on('click', function(e) {
        var $tgt = $(e.target);
        var href = $tgt.attr('href')+'?update=1';
        if(href.search('http')===0) {
            return true;
        }
        e.preventDefault();
        window.history.pushState(href,'ignored', $tgt.attr('href'));
        return followLink(href,$tgt);
	});
}

function prepareMenu($scope,sel) {
    //console.log(window.location);
    if(!sel) sel = 'nav';
    var nav = $scope.find(sel);
    if (nav.length) {
        nav.each(function () {
            var $nav = $(this);
            var $as = $nav.find('a');
            $as.on('click', function (e) {
                e.preventDefault();
                var $tgt = $(e.target);
                $nav.find('a').removeClass('active');
                $tgt.addClass('active');
            });
            $as.each(function(i,e) {
                var $e = $(e);
                var href=$e.attr('href');
                if(href==window.location.href || href==window.location.pathname || (href+'/')==window.location.pathname) {
                    $e.addClass('active');
                }
            });
        });
    }
}

function onPopState(event) {
    console.log(event);
    followLink(event.state);
}
		
$(function() {
	getEm($('body'));
    window.onpopstate = onPopState;
});