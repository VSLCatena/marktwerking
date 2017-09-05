// Settings
var updateTime = 10 * 60; // The update time in seconds
var timeOffset = 5; // the amount of seconds we update LATER to avoid update issues
// End settings




var statistics = new Statistics(document.getElementById('statistics'));

function load(){
    $("body").addClass("loading");
    $.getJSON('prices.php', function(data){
        breakingNews().queue(function(){
            drawList(data);
            statistics.draw(data);

            $("body").removeClass("loading");
            $(this).dequeue();
        });
    });
}

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

var timeToUpdate = 0;
function tick(){
    if($("body").hasClass("loading"))
        return;

    if(new Date().getTime() > timeToUpdate){
        load();

        timeToUpdate = (parseInt(new Date().getTime() / (updateTime * 1000)) + 1) * updateTime * 1000;

        // We update a couple of seconds later for possible update issues and such
        timeToUpdate += timeOffset * 1000;
        return;
    }

    // Update timer on top
    var secondsLeft = ((timeToUpdate - new Date().getTime()) / 1000) % updateTime;
    var minutes = parseInt(secondsLeft / 60);
    var seconds = Math.floor(secondsLeft % 60);
    $("#timer").html(pad(minutes, 2) + " min. " + pad(seconds, 2) + " sec.");
}

function drawList(data){
    var html = "";

    data.forEach(function(item){
       html += '<div class="col-xs-9">' + item.name + '</div>' +
           '<div class="col-xs-3 text-right">&euro;' + item.prices[item.prices.length - 1].toFixed(2) +'</div>';
    });

    $("#price-list").html(html);
}

function breakingNews(){
    // Breaking news #1
    return $(".bn-overlay").removeClass("bn-hidden").delay(200).queue(function() {
        $(".bn-text").addClass("animate");
        $(this).dequeue();
    }).delay(3000).queue(function(){
        $(".bn-overlay").addClass("animate bn-hidden");
        $(this).dequeue();
    }).delay(1000).queue(function(){
        $(".bn-text").removeClass("animate");
        $(".bn-overlay").removeClass("animate");
        $(this).dequeue();
    });
}

setInterval(tick, 1000);