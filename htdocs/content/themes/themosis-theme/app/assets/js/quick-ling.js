console.log('QuickLing');

var Quickling = {

    defaults: {
        menu_selector: 'nav',
        loader_bar: 'relative',
        debug: false
    },

    init: function(opts) {
        Quickling.$ = $(Quickling);
        Quickling.cache = {};
        Quickling.opts = {};
        $.extend(Quickling.opts,Quickling.defaults,opts);
    },

    prepareData: function ($data, $target) {
        if(Quickling.opts.debug) console.log(['exact', $target.ql_exact]);
        if ($target.ql_exact) {
            //console.log(['data',$data]);
            var $node = $data.find($target.ql_exact);
            //console.log(['node',$node]);
            if ($node.length) {
                var $siblings = $node.siblings();
                //console.log(['siblings',$siblings]);
                $data = $siblings.add($node);
                //console.log(['exact data',$data]);
            }
        }
        var encapsulated = [];
        $data.each(function (i, e) {
            //console.log(e);
            var $e = $(e);
            if ($e.data('ql-target')) {
                encapsulated.push($e);
            }
            ;
        });
        if (!encapsulated.length) {
            encapsulated.push($data);
        }
        return encapsulated;
    },

    applyIt: function ($target, $data) {
        if(Quickling.opts.debug) console.log(['apply it', $target, $data]);

        var $anchors = Quickling.prepareData($data, $target);

        $.each($anchors, function (i, $new) {
            var tmp;
            var tr_in,tr_out;
            if ($new.data('ql-name')) {
                tmp = $('[data-ql-name="' + $new.data('ql-name') + '"]');
            }
            if (tmp.length) {
                var $targ = $(tmp[0]);
                //console.log(['target',$targ]);
            } else {
                $targ = $target;
            }

            if($new.html()==$targ.html()) return;

            if (!$targ.data('ql-transition')) {
                if(Quickling.opts.debug) console.log(['normal transition',$targ.attr('class')]);
                tr_in = 'fadeIn';
                tr_out = 'fadeOut';
            } else {
                if(Quickling.opts.debug) console.log(['custom transition',$targ.attr('class')]);
                var parts = $targ.data('ql-transition').split('/');
                tr_in = parts[0];
                if(parts.length>1) tr_out = parts[1];
                else tr_out = parts[0];
                tr_in = tr_in.replace('%i%', 'In');
                tr_out = tr_out.replace('%i%', 'Out');
            }

            var $wr = $targ.clone();
            $wr.children().remove();
            $wr.addClass('transition-wrap');
            var $wrap = $targ.wrapAll($wr).parent();
            $wrap.css('height', $targ.outerHeight());

            $targ.addClass('animated ' + tr_out);
            $targ.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (e) {
                $targ.remove();
                $wrap.append($new);
                Quickling.getEm($wrap);
                $new.addClass('animated ' + tr_in);
                $wrap.css('height', $new.css('height'));
                resizeSection($new.closest('.section'));
                $new.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (e) {
                        /* TODO: investigate why this is firing twice even if bound as $.one */
                        if($new.hasClass('animated'))
                        {
                            $new.removeClass('animated');
                            $new.unwrap();
                        }
                });
            });
        });
        Quickling.$.trigger('data.postApply',[$target,$data]);
    },

    addLoader: function ($target) {
        //console.log('add loader',$target);
        if (!$target.ql_loader) {
            var $bar = $('<div class="bar"></div>');
            var $loader = $('<div class="ql-loader"></div>');
            $bar.width('0%');
            $target.prepend($loader.append($bar))
            $target.ql_loader = $loader;
            $target.ql_loader.bar = $bar;
        }
    },

    updateLoader: function ($target) {
        //console.log('update loader');
        var progress = parseInt($target.ql_loader.bar[0].style.width);
        var add = (100 - progress) / 5;
        //console.log([progress,add]);
        Quickling.moveLoader($target, progress + add);
        //$target.ql_loader.bar.css('width',progress+add+'%');
    },

    moveLoader: function ($target, percent) {
        $target.ql_loader.bar.css('width', percent + '%');
    },

    startLoader: function ($target) {
        //console.log('start loader');
        setTimeout(function () {
            Quickling.updateLoader($target)
        }, 1);
        $target.ql_loader.interval = setInterval(function () {
            Quickling.updateLoader($target);
        }, 300);
    },

    removeLoader: function ($target) {
        //console.log('remove loader');
        $target.ql_loader.bar.css('height', '0');
        setTimeout(function () {
            $target.ql_loader.remove();
            $target.ql_loader = null;
        }, 350);
    },

    finishLoader: function ($target) {
        //console.log('finish loader');
        clearInterval($target.ql_loader.interval);
        setTimeout(function () {
            $target.ql_loader.bar.css('width', '100%');
        }, 1);
        setTimeout(function () {
            //removeLoader($target);
        }, 350);
    },

    loadIt: function (href, cont) {
        var $target = $(cont);
        var instead;
        if (!$target || !$target.length) {
            $target = $('.ql-container');
            instead = '.ql-container';
            if (!$target.length) {
                $target = $('body');
                instead = 'body';
            }
            if(Quickling.opts.debug) console.log('QL: No element (' + cont + ') found. Using (' + instead + ')');
            $target.ql_exact = false;
        } else {
            /*if($target.length>1) {
             $target = $($target[0]);
             }*/
            $target.ql_exact = cont;
            if(Quickling.opts.debug) console.log('target ' + cont);
        }

        //console.log($target);

        if (Quickling.cache[href]) {
            if(Quickling.opts.debug) console.log('using cache for: ' + href);
            Quickling.applyIt($target, $(Quickling.cache[href]));
        }
        else {
            if(Quickling.opts.debug) console.log('getting: ' + href);
            Quickling.addLoader($target);
            Quickling.startLoader($target);

            $.ajax({
                type: 'GET',
                url: href,
                headers: {"X-QL-Update": "1"},
                success: function (data) {
                    Quickling.finishLoader($target);
                    if(Quickling.opts.debug) console.log(['ajax', data]);
                    var $data = $(data);
                    Quickling.$.trigger('data.postLoad',[$data]);
                    if(Quickling.opts.debug) console.log($data.prop('outerHTML'));
                    Quickling.cache[href] = $data.prop('outerHTML');
                    Quickling.applyIt($target, $(Quickling.cache[href]));
                }

            });
        }
    },

    followLink: function (href, $tgt) {
        if ($tgt && $tgt.data('ql-target')) {
            if(Quickling.opts.debug) console.log(['follow link', $tgt.data('ql-target')]);
            Quickling.loadIt(href, $tgt.data('ql-target'));
        } else {
            Quickling.loadIt(href);
        }
        return false;
    },

    getEm: function ($scope) {
        if(Quickling.opts.debug) console.log('getEm');
        var $as = $scope.find('a');
        $as.each(function () {
            //console.log([$scope,this,$scope.selector]);
            if (!$(this).data('ql-target')) {
                var cls = $scope.attr('class');
                if (cls) {
                    $(this).data('ql-target', '.' + cls.split(' ').join('.'));
                } else {
                    if(Quickling.opts.debug) console.log(['scope without class', $scope]);
                }
            }
        });

        Quickling.prepareMenu($scope);

        $as.on('click', function (e) {
            var $tgt = $(e.target);
            var href = $tgt.attr('href');
            if($tgt.data('ql-external')) {
                if(Quickling.opts.debug) console.log('forced external link');
                return true;
            }
            if(href.search('http') === 0 && !Quickling.opts.root
                || href.search(Quickling.opts.root)===-1) {
                if(Quickling.opts.debug) console.log('detected external link');
                return true;
            }
            if(Quickling.opts.debug) console.log('click');
            e.preventDefault();
            window.history.pushState(href, 'ignored', $tgt.attr('href'));
            return Quickling.followLink(href, $tgt);
        });
    },

    prepareMenu: function ($scope, sel) {
        //console.log(window.location);
        if (!sel) sel = Quickling.opts.menu_selector;
        var nav = $scope.find(sel);
        if (nav.length) {
            nav.each(function () {
                var $nav = $(this);
                var $as = $nav.find('a');
                $as.on('click', function (e) {
                    //e.preventDefault();
                    var $tgt = $(e.target);
                    $nav.find('a').removeClass('active');
                    $tgt.addClass('active');
                });
                $as.each(function (i, e) {
                    var $e = $(e);
                    var href = $e.attr('href');
                    if (href == window.location.href || href == window.location.pathname || (href + '/') == window.location.pathname) {
                        $e.addClass('active');
                    }
                });
            });
        }
    }
}
