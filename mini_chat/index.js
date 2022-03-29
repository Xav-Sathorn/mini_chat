$(document).ready(function() {
    //Search
    $("#searchText").on("input", function(){
        const searchText = $(this).val();
        if(searchText == "") return;
        $.post('ajax/search.php', 
                {
                    key: searchText
                },
            function(data, status){
                $("#chatList").html(data);
            });
    });

    //Search using the button
    $("#searchBtn").on("click", function(){
        const searchText = $("#searchText").val();
        if(searchText == "") return;
        $.post('ajax/search.php', 
                {
                    key: searchText
                },
            function(data, status){
                $("#chatList").html(data);
            });
    });

    // maj automatique des derniers utilisateurs connectés
    let lastSeenUpdate = function() {
        $.get("ajax/update_last_seen.php");
    }
    lastSeenUpdate();
    /**maj auto last_seen toutes les 10secs */
    setInterval(lastSeenUpdate, 10000);
}); 



