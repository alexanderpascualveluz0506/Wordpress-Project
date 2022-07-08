jQuery(document).ready(function($) {
    $(".tab_content_login").hide();
    $("ul.tabs_login li:first").addClass("active_login").show();
    $(".tab_content_login:first").show();
    $("ul.tabs_login li").click(function() {
        $("ul.tabs_login li").removeClass("active_login");
        $(this).addClass("active_login");
        $(".tab_content_login").hide();
        var activeTab = $(this).find("a").attr("href");
        $(activeTab).show();
        return false;
    });
});