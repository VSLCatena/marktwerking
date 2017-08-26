<?php include("./password_protect.php"); ?>
<!DOCTYPE html>
<html lang="en" ng-app="barApp">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/bar.css" rel="stylesheet">

		<title></title>
	</head>
	<body>
		<div class="container-fluid" ng-controller="barController as bar">
			<div class="row">
				<div class="col-md-1 view">
					<div class="btn-group-vertical">
                        <a class="btn btn-primary btn-sq" href="../beamer.php"  type="button">Beamer</a>
						<button class="btn btn-primary btn-sq" data-target="#financial" data-toggle="modal" type="button">Financial</button>
						<button class="btn btn-primary btn-sq" data-target="#settings" data-toggle="modal" type="button">Settings</button>
					</div>
				</div>
				<div class="col-md-4 orderlist">
                    <div class="row checkout-header">
                        <div class="col-xs-5">Naam:</div>
                        <div class="col-xs-2">Aantal</div>
                        <div class="col-xs-3">Prijs</div>
                    </div>
                    <div class="row checkout-item" ng-repeat="item in bar.order">
                        <div class="col-xs-5">{{ item.name }}</div>
                        <div class="col-xs-2">{{ item.times }}</div>
                        <div class="col-xs-2">{{ (item.times * item.price) | currency:"&euro;" }}</div>
                        <div class="col-xs-3 ">
                            <div class="btn btn-warning checkout-item-mod" ng-click="subtractFromOrder(item)">-</div>
                            <div class="btn btn-danger checkout-item-mod" ng-click="deleteFromOrder(item)">X</div>
                        </div>
                    </div>
					<div class="row checkout-total">
                        <!-- list of total drinks to be payed -->
                        <div class="col-xs-offset-5 col-xs-2">{{ getTotalItems() }}</div>
                        <div class="col-xs-3">{{ getTotalPrice() | currency:"&euro;" }}</div>
					    <div class="col-xs-1 btn btn-success btn-group-justified checkout-order-pay">Betalen</div>
                    </div>
				</div>

				<div class="col-md-7">

					<div class="row category">
						<div class="col-md-12">
							<div class="btn-group btn-group-justified cat">
								<a class="btn btn-primary btn-sq" ng-click="bar.itemFilter = ''">All</a>
                                <a class="btn btn-primary btn-sq" ng-repeat="cat in bar.categories" ng-click="bar.itemFilter = cat.id">{{ cat.name }}</a>
								<!-- using JS more cat buttons will be created-->
							</div>
						</div>
					</div>

					<div  class="row products ">
						<div class="col-md-12">
                            <button ng-repeat="item in bar.items | orderBy:'name' | matchCategory: bar.itemFilter" ng-click="addToOrder(item)"  ng-style="{'background-image': 'linear-gradient( rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7) ),url('+'../images/drinks/' + item.name+'.png' + ')'}" class="btn-sq-prod">
                                {{ item.name }}
                                <div class="btn-sq-prod-price">{{item.price | currency:"€":0}}</div>
                            </button>
                            <!-- using JS buttons will be created-->
                        </div>
					</div>
				</div>
			</div>
		</div><!-- Modal -->
		<div class="modal fade" id="financial" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal" type="button">&times;</button>
						<h4 class="modal-title">
							Total financial data
						</h4>
					</div>
					<div class="modal-body">
						<p>
							$
						</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
					</div>
				</div>
			</div>
		</div><!-- Modal-->
		<div class="modal fade " id="settings" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal" type="button">&times;</button>
						<h4 class="modal-title">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#general">Algemeen</a></li>
                                <li><a data-toggle="tab" href="#drinks">Artikelen</a></li>
                                <li><a data-toggle="tab" href="#drinks-mix">Gemixte artikelen</a></li>
                                <li><a data-toggle="tab" href="#rounds">Marktwerking/Ronden</a></li>
                            </ul>
						</h4>
					</div>
					<div class="modal-body">
						<p>


                            <div class="tab-content">
                                <div id="general" class="tab-pane fade in active">
                        <p>

                        <div class="radio">
                            <label><input type="radio" name="settings-type-bar" ng-checked="">Normale Bar</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="settings-type-bar" ng-checked="">Marktwerking</label>
                        </div>
                        </p>
                    </div>

                    <div id="rounds" class="tab-pane fade">
                        <p>

                        <form>
                            <div class="input-group">
                                <span class="input-group-addon">Tijd ronde (min):</span>
                                <input id="time-round" type="text" class="form-control" name="time-round" placeholder="" ng-model="settings.time_round">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Tijd totaal (uur):</span>
                                <input id="time-total" type="text" class="form-control" name="time-total" placeholder="" ng-model="settings.time_total">
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">Aantal ronden:</span>
                                <input id="round-total" type="text" class="form-control" name="round-total" placeholder="" value="{{settings.time_total*60/settings.time_round}}" disabled>
                            </div>

                        </form>

                        </p>
                    </div>

                    <div id="drinks" class="tab-pane fade " ng-controller="barController as bar">
                        <p>Naam - Prijs - Minimum - Categorie - Actief - Afbeelding - Verwijderen</p>
                        <div class="input-append" ng-repeat="items in bar.items">
                            {{items.id}}
                            <input type="text" ng-model="items.name">
                            <input type="text" class="settings-item-price" ng-model="items.price">
                            <input type="text" class="settings-item-price" ng-model="items.price_min">
                            <input type="text" ng-model="items.category">
                            <label><input type="checkbox" ng-model="items.active" >Actief</label>
                            <button class="btn">Plaatje Upload</button>
                            <a href='../images/drinks/{{items.name}}.png' />Foto</a>
                            <button class="btn" ng-click="settingsItemRemove($index)">X</button>
                        </div>
                        <button class="btn btn-small" ng-click="settingsItemAdd(items.name,items.price,items.price_min,items.category,items.active )">Data toevoegen + nieuwe rij</button>




                    </div>


                    <div id="drinks-mix" class="tab-pane fade">




                    </div>

                </div>




					</div>
					<div class="modal-footer">

                        <input type="submit" ng-click="submitSettings(marktwerking)" class="btn btn-info" value="Aanpassen">
						<button class="btn btn-default" data-dismiss="modal" type="button">Sluiten</button>
					</div>
				</div>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.32/angular.js"></script>
        <script src="../js/bar.js"></script>
	</body>
</html>