angular.module('barApp', [])
    .controller('barController', function($scope, $http) {
        var marktwerking = this;
        marktwerking.categories = [];
        marktwerking.items = [];
        marktwerking.order = [];
        marktwerking.settings = [];

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
                marktwerking.items.push({id:$scope.bar.items.length.toString(), round_id:'0'});
                for(var key in marktwerking.items){
                    if (marktwerking.items[key].active==="1"){marktwerking.items[key].active=true;}
                    else {marktwerking.items[key].active=false;}};

                marktwerking.settings = response.data.settings;
                console.debug(marktwerking);

                // TODO fix this to a correct category system
                for(var key in marktwerking.settings){
                    var val = marktwerking.settings[key];
                    if(key.startsWith('cat')  && val !== ""){
                        var cat = val.split(",");
                        for(i in cat) {
                            marktwerking.categories.push(cat[i]);
                        }
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
            var len = $scope.bar.items.length-1;
            if (len===index){$scope.bar.items.splice(index, 1);}
            else {$scope.bar.items.splice(index, 1,{round_id:'0',id:index.toString()});}

        };

        $scope.settingsItemAdd = function() {
            var d = new Date();
            var len = $scope.bar.items.length;
            $scope.bar.items.push({id:len.toString(),round_id:'0',datetime:d });
            console.log(marktwerking.items);
        };

        $scope.submitSettings = function(marktwerking) {

        console.debug(marktwerking);
        }



    });



