<?php echo $header ?>
<h2>Поиск по сайту</h2>
<hr>
<div class="poisk-mane">
    <form id="searchForm" method="POST">
        <div class="poisk-group-2">
            <input type="text" class="forma" name="Search_text" id="Search_text" placeholder="Поиск по сайту..">
            <button class="fa fa-search" id="but"></button>
        </div>
    </form>
</div>

<div id="showtitle"></div>
<div style="display: table; width: 100%; margin-top: 20px;" id="showres">
    
</div>
<script>
    var mytext = decodeURIComponent(location.search.substr(1)).split('&');
    
    mytext.splice(0, 1);
    var result = mytext[0];
    if (result != undefined) {
        var searchpole = document.getElementById("Search_text");
        searchpole.value = result;
    }
    
    $('#searchForm').ajaxForm({
        url: '/search/mane/ajax',
        dataType: 'json',
        success: function(data) {
            switch (data.status) {
                case 'error':
                    $("#showres").html("");
                    $("#showtitle").html("<div style='display: table; width: 100%; padding: 10px; font-size: 18px;'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#showres").html("");
                    var total = data.success[0]['total'];
                    $("#showtitle").html("<div style='display: table; width: 100%; padding: 10px; font-size: 18px;'>" + "По запросу '" + data.success[0]['stroka'] + "', было найдено " + data.success[0]['total'] + " похожих результатов:" + "</div>");
                    for (var i = 0; i <= total; i++) {
                        $("#showres").append("<div id='po"+ (i+1) + "' style='display: table; width: 100%; padding: 10px;'>"+ (i+1) + ". "+ data.success[i]['fullstr'] + "<div class='news-about'><a href='/"+ data.success[i]['silks'] + "' target='_blank'>Подробнее</a></div>" + "</div>");
                    }
                    break;
            }
        },
        beforeSubmit: function(arr, $form, options) {
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>
<?php echo $footer ?>
