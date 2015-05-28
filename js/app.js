Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

// function filtersController($scope) {
// 	$scope.change = function() {
// 		//console.log($scope.Rooms);
// 		//$scope.Rooms = 8;
// 	};
// }

var toggleFiltersBool = true;
function toggleFilters(){
	$('.filters-toggle').toggleClass('on');
	//$('.filters-bubbles').toggleClass('on');
	if(toggleFiltersBool){
		$('.filter-panel').addClass('on').removeClass('off');
		$('#filters-toggle-label').text($('#filters-toggle-label').attr('data-busy'));
		toggleFiltersBool = false;
	}else{
		$('.filter-panel').addClass('off').removeClass('on');
		$('#filters-toggle-label').text($('#filters-toggle-label').attr('data-normal'));
		toggleFiltersBool = true;
	}
}

function fakeReload(){
	$('#results-list').empty();
	$('#spinner-results').addClass('loading');
}

function mapLoaded(){
	$('#spinner-map').removeClass('loading');
}

angular.module('turoga.bootstrap', ['ui.bootstrap']);
angular.module('turoga.bootstrap').controller('TypeaheadCtrl', function($scope, $http) {
	$scope.selected = undefined;
	$scope.states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Dakota', 'North Carolina', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'];
});

$(document).ready(function() {
	
	$('.filters-toggle').on('hover', function(){
		console.log('fdsa');
		$('#result-main-image-'+$(this).attr('data-for')).attr('src', $(this).attr('src'))
	});
	
	$('#filters-form input').on('change.filters', function(){
		fakeReload()
	});
	
	$('.price-slider').slider({
		tooltip_split: true,
		tooltip: 'always',
		formatter: function(value) {
			$('.price-slider').val(value);
			angular.element($('.price-slider')).triggerHandler('input');
			if (value === parseInt(value, 10)){
				value = 'Gs. ' + value.formatMoney(0,'.',',');
			}
			return value;
		}
	}).on('slide', function(e){
		document.getElementById("MinPriceToggle").checked = true;
		angular.element($('#MinPriceToggle')).triggerHandler('click');
		document.getElementById("MaxPriceToggle").checked = true;
		angular.element($('#MaxPriceToggle')).triggerHandler('click');
		
		$('#MinPrice').val('Gs. ' + e.value[0].formatMoney(0,'.',','));
		angular.element($('#MinPrice')).triggerHandler('input');
		
		$('#MaxPrice').val('Gs. ' + e.value[1].formatMoney(0,'.',','));
		angular.element($('#MaxPrice')).triggerHandler('input');
	});
	
	$('.rooms-slider').slider({
		tooltip: 'always',
		formatter: function(value) {
			$('.rooms-slider').val(value);
			angular.element($('.rooms-slider')).triggerHandler('input');
			return value;
		}
	}).on('slide', function(e){
		$('.rooms-slider').trigger('change.filters');
		document.getElementById("RoomsToggle").checked = true;
		angular.element($('#RoomsToggle')).triggerHandler('click');
	});
	
	$('.bathrooms-slider').slider({
		tooltip: 'always',
		formatter: function(value) {
			$('.bathrooms-slider').val(value);
			angular.element($('.bathrooms-slider')).triggerHandler('input');
			return value;
		}
	}).on('slide', function(e){
		$('.bathrooms-slider').trigger('change.filters');
		document.getElementById("BathroomsToggle").checked = true;
		angular.element($('#BathroomsToggle')).triggerHandler('click');
	});
});

//var mySlider = new Slider("input.price-slider", {
//	tooltip_split: true,
//	tooltip: 'always',
//	formatter: function(value) {
//		return value;
//	}
//});
//var roomsSlider = new Slider("input.rooms-slider", {
//	tooltip: 'always',
//	formatter: function(value) {
//		return value;
//	},
//	slideStop: function(value){
//		console.log('fdsa');
//		$('.input.rooms-slider').trigger('change');
//	}
//});
//var bathroomsSlider = new Slider("input.bathrooms-slider", {
//	tooltip: 'always',
//	formatter: function(value) {
//		return value;
//	}
//});