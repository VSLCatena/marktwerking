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
        marktwerking.settings = [];
        marktwerking.interval;

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

        marktwerking.submitOrder = function() {
            // send current order to the server
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
                    if (marktwerking.items[key].active==="1"){marktwerking.items[key].active=true;}
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
        marktwerking.update();

        // TODO make this sync up with the update timer
        marktwerking.interval = $interval(marktwerking.update, 5000);



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
            var len = $scope.bar.items.length-1;
            if (len===index){$scope.bar.items.splice(index, 1);}
            else {$scope.bar.items.splice(index, 1,{round_id:'0',id:index.toString()});}

        };

        $scope.settingsItemAdd = function() {
            var len = $scope.bar.items.length;
            $scope.bar.items.push({});
            console.log(marktwerking.items);
        };

        $scope.submitSettings = function(marktwerking) {

        console.debug(marktwerking);
        };

        $scope.updateItemCategories = function(item){
            item.categories = [];
            $("#settingsItem"+item.id).find("option:selected").each(function() {
                if($)
                item.categories.push($(this).val());
            });
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
        });

    });

