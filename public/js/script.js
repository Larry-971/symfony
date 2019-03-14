$().ready(function(){
    $("h1").click(function(){
        $("ul").fadeToggle();
        $("ul").fadeToggle("slow");
        $("ul").fadeToggle("3000");
    });
});