angular.module('barApp', [])
    .controller('barController', function($scope, $http) {
        var marktwerking = this;
        marktwerking.categories = [];
        marktwerking.items = [];
        marktwerking.order = [];

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

        marktwerking.update = function(){
            $http({
                method: 'GET',
                url: '../sql.php'
            }).then(function successCallback(response) {
                // Fill in the items
                marktwerking.items = response.data.drinks_start;
                console.debug(marktwerking.items);

                // TODO fix this to a correct category system
                for(var key in response.data.settings){
                    var val = response.data.settings[key];
                    if(key.startsWith('Cat_') && key !== 'Cat_amount' && val !== ""){
                        marktwerking.categories.push(response.data.settings[key]);
                    }
                }
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        };

        marktwerking.update();



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
            $scope.bar.items.splice(index, 1);
        };

        $scope.settingsItemAdd = function() {
            $scope.bar.items.push({ url: ''});
        };

    });

