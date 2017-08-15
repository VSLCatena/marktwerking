//define global variable here
data = [];
tempOrder=[]

// end 

$(document).ready(function(){
		
        $.ajax({
		type: "GET",
		url: "../sql.php",
		dataType: "json",
		success: function (result) {
			data = eval(result);
			//console.log(data);
			console.log(data);
			
			createCatButton();
			createProdButton();
			


			
			
			
		},
        error: function(){
            alert('ERROR');
            }
        });
		return false;
		
		
		
    });

	
//find correct product for each category 	
// function getProdCat() {
	// ProdCat = {};
	// for(var i=0; i < data['settings']['Cat_amount']; i++){
		// var catid = "Cat_"; catid +=i;
		// var cat = data['settings'][catid]; //cat = "bier"
		// ProdCat[i] = [];
		// var k = 0;
		// for(var j=0; j < data['settings']['Prod_amount']; j++){
			// if (data['drinks_start'][j]['category'] == cat){
				// ProdCat[i][k] = data['drinks_start'][j]['id'];
				// k++;
			// }
		// }
	// }
	// console.log(ProdCat);
// }	
	


function getProdCat() {
	ProdCat = {};
	for(var i=0; i < data['settings']['Prod_amount']; i++){
		ProdCat[i]=[];
		for (var j=0; j < data['settings']['Cat_amount']; j++) {
			var catid = "Cat_"; catid +=j;
			var isin = data['drinks_start'][i]['category'] == data['settings'][catid];
			ProdCat[i][j]=isin;

		}	
	
	
	}
	console.log(ProdCat);
	
}	 

	
	
	
function createCatButton() {
	
    var catMenu = document.getElementById("btn_cat_menu");
	for (var i = 0; i<data['settings']['Cat_amount']; i++) {
		//create cat button in menu
        var cat_button = document.createElement('a');
		cat_button.id ='btn_cat_'+i;
		cat_button.className = "btn btn-primary btn-sq";
		cat_button.textContent ="Cat_"+i
		catMenu.appendChild(cat_button);

    }
   	
	//fill in correct label
	getCatLabel();
}

function createProdButton() {
	getProdCat();
	
	//for 
    var prodList = document.getElementById("btn_prod_list");
	for (var i = 0; i<data['settings']['Prod_amount']; i++) {
        var prod_button = document.createElement('button');
		prod_button.id ='btn_prod_'+i;
		prod_button.type="button"
		prod_button.className = "btn-sq-prod";
		prod_button.setAttribute("onclick","calcAdd(this)")
		prodList.appendChild(prod_button);
    }
	
	//fill in correct label
	getProdLabel();
}


	
function getCatLabel() {
	for(var i=0; i < data['settings']['Cat_amount']; i++){
		var id = "btn_cat_";
		var catid = "Cat_";
		id +=i;
		catid +=i; 
		document.getElementById(id).innerHTML = data['settings'][catid];
		//document.getElementById(id).setAttribute('ng-click',"myFilter = {category: '"+data['settings'][catid]+"'}");
		}
	} 

	
function getProdLabel() {
	for(var i=0; i < data['settings']['Prod_amount']; i++){
		var id = "btn_prod_";
		id +=i;
		document.getElementById(id).innerHTML = "<div class='prod_price'>"+data['drinks_start'][i]['price']+"</div>"+"<br>"+"<br>"+"<div class='prod_name'>"+data['drinks_start'][i]['name']+"</div>";
	} 
} 	

function calcAdd(id) {
	var drinkid
	drinkid = id.id.replace('btn_prod_','');
	calculate(drinkid)		
}		

function calculate(id) {
	name = data['drinks_start'][id].name
	price = parseInt(data['drinks_start'][id].price)
	div_id="checkoutDrink_"+id;
	
	//if type of drink has not been ordered 
	if (tempOrder[id] == undefined) {
		tempOrder[id]={}
		tempOrder[id]['name']=name;
		tempOrder[id]['price']=parseInt(price);
		tempOrder[id]['amount']=1;
		tempOrder[id]['total']=parseInt(price)
	}	
	//if type of drink has been ordered
	if (tempOrder[id]) {
		tempOrder[id]['amount']+=1;
		tempOrder[id]['total']+=parseInt(price);
	}
	console.log(tempOrder); 
	

	//if div_id exist
	if ($("#"+div_id).length > 0) {
		$('#'+div_id+'-amount').text(tempOrder[id]['amount'])
		$('#'+div_id+'-total').text(tempOrder[id]['total']);
		
	}
	
	//if div_id does not exist		
    if($("#"+div_id).length == 0){
        $('#checkout-list').append($('<div class="checkout-item" id='+div_id+'></div>'));
			$('#'+div_id).append($('<div class="checkout-item-el col-md-7" id='+div_id+'-name>'+tempOrder[id]['name']+'</div>'));
			$('#'+div_id).append($('<div class="checkout-item-el col-md-1" id='+div_id+'-price>'+tempOrder[id]['price']+'</div>'));
			$('#'+div_id).append($('<div class="checkout-item-el col-md-2" id='+div_id+'-amount>1</div>'));
			$('#'+div_id).append($('<div class="checkout-item-el col-md-2" id='+div_id+'-total>'+tempOrder[id]['total']+'</div>'));
   	}

		//show price total after #submit
	amount_total=0;
	price_total=0;
	tempOrder.forEach(function(el, index){
		amount_total += el['amount'];
		price_total += el['total'];
	});

	$("#checkoutDrink_all-amount").text(amount_total);
	$("#checkoutDrink_all-total").text(price_total);
	
}	