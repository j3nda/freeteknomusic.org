// @todo: kdyz nebude .js existovat, tak jej .php vygeneruje do cache! (~obdobne jako /img/ z PR)
// @todo: zahodit bile znaky a minimalizovat js!
// @todo: idealne nacist externi JS knihovny, at je to v 1x js souboru! (~idealne vcetne historie cacheovani, at v pripade absence soubrou lze pouzit posl. verzi!)
// #include <stdio.h ~ local .js (relative vs absolute path!)>
// #include "http://code.jquery.com/jquery-1.6.2.min.js"
// #include "http://www.rockechris.com/jquery/jquery.random.js"
$(document).ready(function() {

    $(".btn-slide").click(function(){
            $("#panel").slideToggle("slow");
                    $(this).toggleClass("active"); return false;
                        });


    __a23.init('z23_', {
           dir: { min:   1, max:  50, },
            ms: { min:   8, max:  67, },
          loop: { min:   9, max: 129, },
         class: { min:   1, max:  36, init: 1, },
    });
    __ahead.init('h34_', {
           dir: { min:   1, max:  50, },
            ms: { min:  11, max:  49, },
          loop: { min:  22, max:  99, },
         class: { min:   1, max:  10, init: 1, },
      callback: function() {
        $('#plynmaska1anim').css('background-color', '#818380');
        $('#plynmaska1anim').css('visibility', 'visible');
        reanimPlynmaska(6666, 2222);
      }
    });


    bodlinaDOWN(1867);
//$("#features").fadeIn()
//.css({top:1000,position:'absolute'})
//.animate({top:275}, 800, function() {
//    //callback
//    });

});


function bodlinaUP(t) {
    $('#bodlina').animate({'top': $(window).scrollTop()}, t, function() {
        bodlinaDOWN(t);
    });
}
function bodlinaDOWN(t) {
    $('#bodlina').animate({'top': $(window).height()-300+$(window).scrollTop()}, t, function() {
//    $('#bodlina').animate({'top': $(document).height()-300}, t, function() {
        bodlinaUP(t);
    });
}


function circle1820(x,y) {
    if (x == undefined || x < 0.1) { x = 1; }
    if (y == undefined || y < 0.1) { y = 1; }

    var points = [];
    var dx     =  10;
    var dy     = -20;

    points.push([(   0*x)+dx, (   0*y)+dy]);
    points.push([(  35*x)+dx, (  25*y)+dy]);
    points.push([(  40*x)+dx, (  50*y)+dy]);
    points.push([(  -5*x)+dx, (  55*y)+dy]);
    points.push([( -12*x)+dx, (  29*y)+dy]);
    points.push([(   0*x)+dx, (   0*y)+dy]);

    return points;
}


function reanimPlynmaska(t, tt) {
    var a  = (30+Math.floor((Math.random()*100)+1))/100;
    var b  = (30+Math.floor((Math.random()*100)+1))/100;
    var ct = t;

    if (tt != undefined && tt > t) {
        ct = t+Math.floor((Math.random()*tt)+1);
    }

    var points = circle1820(a, b);
    var spline = $.crSpline.buildSequence(points, 'right', 'bottom');
    $("#plynmaska").animate({crSpline: spline}, ct, function() {
        reanimPlynmaska(t, tt);
    });
}


// ===========================================================================
//
var __anim = {
    config: {
          dir: { min:   1, max:  1,  },
           ms: { min: 333, max: 888, },
         loop: { min:   5, max:  23, },
        class: { min:   1, max:  36, init: 1, name: undefined },
          log: false,
    },
    actual: {
           ms: undefined,
          dir: undefined,
         loop: undefined,
        class: undefined,
           el: undefined,
           ia: 0,
    },


    animateLogic: function() {
        this.actual.el.attr('class', this.config.class.name+this.actual.class);
        if (this.config.log) {
            console.log(
                '@todo(animateLogic): '
               +'ms='+this.actual.ms
               +', dir='+this.actual.dir
               +', loop='+this.actual.loop
               +', frame/class='+this.actual.class
            );
        }
    },


    animate: function() {
        this.actual.ia--;
        if (this.actual.ia > 0) {
            console.log(this.actual.ia);
            return;
        }

        this.actual.class = this.actual.class + this.actual.dir;
        if (this.actual.class < this.config.class.min) {
            this.actual.class = this.config.class.max;
        }
        if (this.actual.class > this.config.class.max) {
            this.actual.class = this.config.class.min;
        }

        this.animateLogic();
        this.actual.loop--;
        if (this.actual.loop < 0) {
            this.actual.dir  = undefined;
            this.actual.ms   = undefined;
            this.actual.loop = undefined;

            this.init(false);
        }

        // @todo: zmena rychlosti animovani v prubehu, tj. zkracovani animovaciho intervalu. pri spicce (v 1/2 animace je nejrychlejsi - stejne jako kyvadlo!

        var _this = this;
        setTimeout(function() { _this.actual.ia++; _this.animate(); }, this.actual.ms);
    },


     initLogic_dir: function(i) { return (i%2 == 0 ? -1 : +1); },
      initLogic_ms: function(i) { return i; },
    initLogic_loop: function(i) { return i; },

    init: function(name, config) {
        if (config != undefined && config != false) {
            var tmp = this.config;

            this.config = config;
            if (this.config.dir   == undefined) { this.config.dir   = tmp.dir;   }
            if (this.config.ms    == undefined) { this.config.ms    = tmp.ms;    }
            if (this.config.loop  == undefined) { this.config.loop  = tmp.loop;  }
            if (this.config.class == undefined) { this.config.class = tmp.class; }
            if (this.config.log   == undefined) { this.config.log   = tmp.log;   }
        }

        if (name != undefined && name != false) {
            this.config.class.name = name;
        }

        if (this.config.class.name == undefined || this.config.class.name == '') {
            alert('@fixme: config.class.name is empty!');
            return;
        }

        if (this.actual.el == undefined) {
            this.actual.el = $('#'+this.config.class.name);
        }


        var tmp;

        if (this.actual.class == undefined) {
            this.actual.class = this.config.class.init;
        }

        if (this.actual.dir == undefined) {
            tmp = this.config.dir.min;
            if (this.config.dir.min != this.config.dir.max) {
                tmp = $.randomBetween(this.config.dir.min, this.config.dir.max);
            }
            this.actual.dir = this.initLogic_dir(tmp);
        }

        if (this.actual.ms == undefined) {
            tmp = this.config.ms.min;
            if (this.config.ms.min != this.config.ms.max) {
                tmp = $.randomBetween(this.config.ms.min, this.config.ms.max);
            }
            this.actual.ms = this.initLogic_ms(tmp);
        }

        if (this.actual.loop == undefined) {
            tmp = this.config.loop.min;
            if (this.config.loop.min != this.config.loop.max) {
                tmp = $.randomBetween(this.config.loop.min, this.config.loop.max);
            }
            this.actual.loop = this.initLogic_loop(tmp);
        }

        if (name == undefined || name != false) {
            this.actual.ia++;
            this.animate();
        }

        if (this.config.callback != undefined) {
            this.config.callback();
            this.config.callback = undefined;
        }
    }
};


var __a23   = {};
var __ahead = {};
$.extend(true, __a23,   __anim);
$.extend(true, __ahead, __anim);
//__ahead.animateLogic = function() {
//    console.log('@todo');
    // @todo: myslenka: bude tak trochu poletovat ala 'menu ze hry no one lives forever!'

    // @todo: hlava by se mela pohybovat po trajektorii a mirne kolem ni "plavat"
    // @todo: pod hlavou by se mel objevovat stin...
    // @todo: hlava by se mela naklanet zleva/doprava - tj. menit uhel naklaneni
//}

