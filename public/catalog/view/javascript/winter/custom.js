/* responsive menu */
function openNav() {
    $('body').addClass("active");
    document.getElementById("mySidenav").style.width = "280px";
    jquery('#mySidenav').addClass("dblock");
}
function closeNav() {
    $('body').removeClass("active");
    document.getElementById("mySidenav").style.width = "0";
    jquery('#mySidenav').addClass("dnone");
}

 /* loader */
/* Slider Loader*/
$(window).load(function myFunction() {
    $(".s-panel .loader").removeClass("wrloader");
});

//go to top
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
});


$(document).ready(function () {
    if ($(window).width() <= 991) {
        $('.xsla').appendTo('.haccount');
        $('.xscu').appendTo('.haccount');
    }
});



$(document).ready(function () {
$("#ratep,#ratecount").click(function() {
    $('body,html').animate({
        scrollTop: $(".product-tab").offset().top 
    }, 1500);
});
});

$(document).ready(function () {
$('.dropdown button.test').on("click", function(e)  {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
});
});


/* dropdown effect of account */
$(document).ready(function () {
    if ($(window).width() <= 767) {
    $('.catfilter').appendTo('.appres');

    $('.dropdown a.account').on("click", function(e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
}
$('.imgbanner').appendTo('.img-banner-ap');
});
/* dropdown */

/* sticky header */
  if ($(window).width() > 992) {
 $(document).ready(function(){
      $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.menuwidth').addClass('fixed fadeInDown animated');
        } else {
            $('.menuwidth').removeClass('fixed fadeInDown animated');
        }
      });
});
};


$(document).ready(function(){
$("#common-home").parent().addClass("home-page");
});


$(document).ready(function(){
    if ($(window).width() >= 992) {
var number_blocks =6;
    var count_block = $('#menu .m-menu');
    var moremenu = count_block.slice(number_blocks, count_block.length);
    moremenu.wrapAll('<li class="view_cat_menu tab-menu"><div class="more-menu sub-menu">');
    $('.view_cat_menu').prepend('<a href="#" class="submenu-title">More</a>');
    $('.view_cat_menu').mouseover(function(){
    $(this).children('div').show();
    })
    $('.view_cat_menu').mouseout(function(){
    $(this).children('div').hide();
    });
    };
});