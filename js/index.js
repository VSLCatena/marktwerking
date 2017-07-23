// Settings
var updateTime = 120; // The update time in seconds
var timeOffset = 5; // the amount of seconds we update LATER to avoid update issues=
// End settings




var statistics = new Statistics(document.getElementById('statistics'));

function load(){
    $.getJSON('prices.php', function(data){
        statistics.draw(data);
    });
}

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

var timeToUpdate = 0;
function tick(){

    if(new Date().getTime() > timeToUpdate){
        load();

        timeToUpdate = (parseInt(new Date().getTime() / (updateTime * 1000)) + 1) * updateTime * 1000;

        // We update a couple of seconds later for possible update issues and such
        timeToUpdate += timeOffset * 1000;
    }

    // Update timer on top
    var secondsLeft = ((timeToUpdate - new Date().getTime()) / 1000) % updateTime;
    var minutes = parseInt(secondsLeft / 60);
    var seconds = Math.floor(secondsLeft % 60);
    $("#timer").html(pad(minutes, 2) + " min. " + pad(seconds, 2) + " sec.");

    // Update a second later
    setTimeout(tick, 1000);
}

tick();