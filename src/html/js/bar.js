angular.module('barApp', [])
    .filter('matchCategory', function() {
        return function(items, category) {
            var filtered = [];
            if(category === ''){
                return items;
            }

            angular.forEach(items, function(item) {
                if(item.categories !== null && item.categories.indexOf(category) !== -1){
                    filtered.push(item);
                }
            });

            return filtered;
        };
    })
    .controller('barController', function($scope, $http, $interval) {
        var marktwerking = this;
        marktwerking.categories = [];
        marktwerking.items = [];
        marktwerking.order = [];
        marktwerking.order_prev =[];
        marktwerking.settings = [];
        marktwerking.round = 0;
        marktwerking.interval;
        marktwerking.timeToUpdate = 0;
        marktwerking.updateTime = 10 * 60;
        marktwerking.timeOffset = 5;

        marktwerking.itemFilter = '';

        $scope.addToOrder = function(item) {
            var found = false;
            marktwerking.order.forEach(function(el){
                if(el.id === item.id){
                    el.times++;
                    found = true;
                }
            });
            if(found) return;

            marktwerking.order.push({
                id: item.id,
                name: item.name,
                price: item.price,
                times: 1
            });
        };

        $scope.subtractFromOrder = function(item){
            marktwerking.order.forEach(function(el, index, object){
                if(el.id === item.id) {
                    if(--el.times <= 0){
                        object.splice(index, 1);
                    }
                }
            });
        };

        $scope.deleteFromOrder = function(item){
            marktwerking.order.forEach(function(el, index, object){
                if(el.id === item.id) {
                    object.splice(index, 1);
                }
            });
        };

        $scope.submitOrder = function() {
            console.log(marktwerking.order);
            if(marktwerking.order.length <= 0)
                return;

            //to be shown under the submit button for reference
            marktwerking.order_prev.amount=0;
            marktwerking.order_prev.total=0;
            marktwerking.order.forEach(function(el){
                marktwerking.order_prev.amount += el.times;
                marktwerking.order_prev.total += el.times * el.price;
            });
            console.log(marktwerking.order_prev);

            sendData={orders: marktwerking.order};
            $http.post(
                './submitOrder.php',
                $.param(sendData),
                { headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;' }}
            ).success(function(response) {
                // sueccs

            }).error(function(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
            marktwerking.order = []
        };

        marktwerking.setup = function(){
            $http({
                method: 'GET',
                url: './info.php'
            }).then(function successCallback(response) {
                console.debug(response.data);
                marktwerking.categories = response.data.categories;
                marktwerking.items = response.data.drinks;
                for(var key in marktwerking.items){
                    if (marktwerking.items[key].active==true){marktwerking.items[key].active=true;}
                    else {marktwerking.items[key].active=false;}};
                marktwerking.settings = response.data.settings;
                console.debug(marktwerking.settings);
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        };
        marktwerking.setup();


        marktwerking.update = function() {
            $http({
                method: 'GET',
                url: '../prices.php'
            }).then(function successCallback(response) {
                console.debug(response.data);
                // Loop over all items and associate each current price to the correct item
                marktwerking.round = response.data[0].prices.length;
                response.data.forEach(function(dataItem){
                    marktwerking.items.forEach(function(item, index, object){
                        if(dataItem.id === item.id){
                            item.price = dataItem.prices[dataItem.prices.length-1];
                        }
                    });
                });
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        };

        marktwerking.pad = function(n, width, z) {
            z = z || '0';
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
        }

        marktwerking.tick = function() {
            if(new Date().getTime() > marktwerking.timeToUpdate){
                marktwerking.update();

                marktwerking.timeToUpdate = (parseInt(new Date().getTime() / (marktwerking.updateTime * 1000)) + 1) * marktwerking.updateTime * 1000;

                // We update a couple of seconds later for possible update issues and such
                marktwerking.timeToUpdate += marktwerking.timeOffset * 1000;
                return;
            }

            // Update timer on top
            var secondsLeft = ((marktwerking.timeToUpdate - new Date().getTime()) / 1000) % marktwerking.updateTime;
            var minutes = parseInt(secondsLeft / 60);
            var seconds = Math.floor(secondsLeft % 60);
            $("#timer").html(marktwerking.pad(minutes, 2) + " min. " + marktwerking.pad(seconds, 2) + " sec.");

        };

        marktwerking.interval = $interval(marktwerking.tick, 1000);



        $scope.getTotalPrice = function(){
            var total = 0;
            marktwerking.order.forEach(function(el){
                total += el.times * el.price;
            });
            return total;
        };

        $scope.getTotalItems = function(){
            var total = 0;
            marktwerking.order.forEach(function(el){
                total += el.times;
            });
            return total;
        };

        $scope.settingsItemRemove = function(index) {
            marktwerking.items.splice(index, 1);

        };

        $scope.settingsItemAdd = function() {
            var len = marktwerking.items.length;
            marktwerking.items.push({name:"",start_price:0,minimum_price:0,active:true});
            console.log(marktwerking.items);
        };


        $scope.settingsCategoryRemove = function(index) {
            marktwerking.categories.splice(index, 1);

        };

        $scope.settingsCategoryAdd = function() {
            marktwerking.categories.push({name:"".toString()});
        };

        $scope.updateItemCategories = function(item){
            item.categories = [];
            $("#settingsItem"+item.id).find("option:selected").each(function() {
                if($)
                item.categories.push($(this).val());
            });
        };

        $scope.settingsMarktwerkingReset = function() {

            if (confirm("Reset Marktwerking?")) {
                sendData = {reset: true};
                $http.post(
                    './resetMarktwerking.php',
                    $.param(sendData),
                    {headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
                ).success(function (response) {
                    marktwerking.order = []

                }).error(function (response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });

                location.reload(true)
            }
        };

        marktwerking.updateSQL = function(){
            sendData={categories: marktwerking.categories, items: marktwerking.items, settings :marktwerking.settings };

            $http.post(
                './updateSettings.php',
                $.param(sendData),
                { headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;' }}
            ).success(function(response) {
                // sueccs

            }).error(function(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        };

        $("#settings").on("hide.bs.modal", function () {
            // put your default event here
            marktwerking.updateSQL();
            //uncomment dit hieronder nadat hij af is.
            //location.reload(true)
        });

    });

