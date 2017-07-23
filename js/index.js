var statistics = new Statistics(document.getElementById('statistics'));

function load(){
    $.getJSON('prices.php', function(data){
        statistics.draw(data);
    });
}

load();