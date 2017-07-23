var Statistics = function(element){
    this.el = element;
    this.data = null;
    this.amount = 1;
    this.maxPrice = 2;
    this.graphWidth = 0;
    this.graphHeight = 0;

    this.fakeImage = null;
    this.init();

    $(window).resize($.proxy(function() {
        this.init();
    }, this));
};

Statistics.prototype.init = function(){
    this.el.width = this.el.parentNode.offsetWidth - 30;
    this.el.height = this.el.width/4*1.5;
    if(this.data != null)
        this.draw(this.data);
};

Statistics.prototype.draw = function(items){
    var fakeCtx = document.createElement("CANVAS").getContext("2d");
    fakeCtx.canvas.width = this.el.width;
    fakeCtx.canvas.height = this.el.height;
    this.drawCtx(fakeCtx, items);

    var imageData = fakeCtx.getImageData(0, 0, fakeCtx.canvas.width, fakeCtx.canvas.height);
    this.el.getContext("2d").putImageData(imageData, 0, 0);
};

Statistics.prototype.drawCtx = function(ctx, items){
    this.data = items;

    this.graphWidth = ctx.canvas.width * 0.7;
    this.graphHeight = ctx.canvas.height * 0.75;

    // Fill rectangle
    // PushMatrix
    ctx.save();
    ctx.fillStyle = "#FFFFFF";
    ctx.fillRect(0,0,ctx.canvas.width,ctx.canvas.height);


    ctx.fillStyle = "#777777";
    ctx.strokeStyle = "#777777";

    // Initialize stuff
    var maxPrice = 0;
    items.forEach(function(item){
        item.prices.forEach(function(price){
            if(price > maxPrice)
                maxPrice = price;
        });
    });
    this.maxPrice = (parseInt(maxPrice/2)+1)*2;
    this.amount = items[0].prices.length;

    ctx.save();
    ctx.transform(1, 0, 0, 0.8, 50, ctx.canvas.height-this.graphHeight);
    this.drawGraph(ctx);
    ctx.restore();

    ctx.save();
    ctx.translate(this.graphWidth + 65, ctx.canvas.height-this.graphHeight+10);
    this.drawItemNames(ctx);
    ctx.restore();

    // PopMatrix
    ctx.restore();
};

Statistics.prototype.drawItemNames = function(ctx){
    var items = this.data;

    var itemIndex = 0;
    ctx.font = "12pt Open Sans";
    ctx.textAlign = "left";
    ctx.fillStyle = "#000000";
    ctx.lineWidth = 2.5;
    items.forEach(function(item){
        ctx.strokeStyle = 'hsl(' + (360 / items.length * itemIndex) + ', 50%, 50%)';
        ctx.beginPath();
        ctx.moveTo(0,itemIndex * 20 - 6);
        ctx.lineTo(25,itemIndex * 20 - 6);
        ctx.stroke();

        ctx.fillText(item.name, 30, itemIndex * 20); // Draw text here
        itemIndex++;
    });
};

Statistics.prototype.drawGraph = function(ctx){
    // Draw items
    var maxPrice = this.maxPrice;
    var amount = this.amount;
    var items = this.data;

    var graphWidth = this.graphWidth;
    var graphHeight = this.graphHeight;

    ctx.save();
    ctx.transform(1, 0, 0, -1, 0, graphHeight);

    this.drawGraphLayout(ctx);

    ctx.lineWidth = 2.5;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';

    // Draw graph lines
    var itemIndex = 0;
    items.forEach(function(item){
        var index = 0;
        ctx.beginPath();
        ctx.strokeStyle = 'hsl(' + (360 / items.length * itemIndex) + ', 50%, 50%)';
        ctx.moveTo(0,0);
        item.prices.forEach(function(price) {
            if (index == 0)
                ctx.moveTo((index / (amount-1)) * graphWidth, price / maxPrice * graphHeight);
            else
                ctx.lineTo((index / (amount-1)) * graphWidth, price / maxPrice * graphHeight);
            index++;
        });
        ctx.stroke();
        itemIndex++;
    });

    ctx.restore();
};

Statistics.prototype.drawGraphLayout = function(ctx){

    var maxPrice = this.maxPrice;
    var amount = this.amount;

    var graphWidth = this.graphWidth;
    var graphHeight = this.graphHeight;



    ctx.save();
    ctx.translate(graphWidth / 2 + 50, graphHeight + 40);
    ctx.transform(1, 0, 0, -1, 0, 0);
    ctx.font = "bold 20pt Open Sans";
    ctx.textAlign = "center";
    ctx.text
    ctx.fillText("Prijsverloop", 0, 0); // Draw text here
    ctx.restore();

    // faint lines for price indication
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#999";
    ctx.beginPath();
    for(var i = 2; i <= maxPrice; i += 2){
        ctx.moveTo(0, graphHeight/maxPrice * i);
        ctx.lineTo(graphWidth, graphHeight/maxPrice * i);
    }
    ctx.stroke();

    ctx.font = "10pt Open Sans";
    ctx.textAlign = "center";
    ctx.fillStyle = "#000";
    ctx.strokeStyle = "#000";

    // Draw the graph points and text Horizontal
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(graphWidth, 0);
    for(i = 0; i < amount; i++){
        ctx.moveTo(i/(amount-1) * graphWidth, -5);
        ctx.lineTo(i/(amount-1) * graphWidth, 10);

        ctx.save();
        ctx.translate(i/(amount-1) * graphWidth, -18);
        ctx.transform(1, 0, 0, -1, 0, 0);
        ctx.fillText(i, 0, 0); // Draw text here
        ctx.restore();
    }
    ctx.stroke();

    // Draw the graph points and text Vertical
    for(i = 2; i <= maxPrice; i += 2){
        ctx.save();
        ctx.translate(-8, graphHeight/maxPrice * i-5);
        ctx.transform(1, 0, 0, -1, 0, 0);
        ctx.fillText(i, 0, 0); // Draw text here
        ctx.restore();
    }

    ctx.save();
    ctx.translate(-20, graphHeight/2);
    ctx.transform(1, 0, 0, -1, 0, 0);
    ctx.rotate(-90*Math.PI/180);
    ctx.font = "15pt Open Sans";
    ctx.fillText("Prijs (\u20AC)", 0, 0); // Draw text here
    ctx.restore();
};