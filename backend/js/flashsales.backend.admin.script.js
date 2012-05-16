var name = 'flashsales';
if(typeof Prestashop == 'undefined')
	var Prestashop = {};
if(typeof Prestashop.flashsales == 'undefined')
	Prestashop.flashsales = {};
if(typeof Prestashop.flashsales.backend == 'undefined')
	Prestashop.flashsales.backend = {};
if(typeof Prestashop.flashsales.backend.admin == 'undefined')
	Prestashop.flashsales.backend.admin = {};

Prestashop.flashsales.backend.admin.script = {};
Prestashop.flashsales.backend.ajaxUrl = '../modules/'+ name +'/ajax.php';
Prestashop.flashsales.backend.dataType = 'json';

if(typeof Prestashop.flashsales.backend.nb_images == 'undefined')
	Prestashop.flashsales.backend.nb_images = 3;

if(typeof Prestashop.flashsales.backend.too_much_images == 'undefined')
	Prestashop.flashsales.backend.too_much_images = 'You can only select ' + Prestashop.flashsales.backend.nb_images + ' images.';

if(typeof Prestashop.flashsales.backend.too_less_images == 'undefined')
	Prestashop.flashsales.backend.too_less_images = 'You have to select ' + Prestashop.flashsales.backend.nb_images + ' images.';

$(document).ready(function() {
	//$('input.flashsales_productBox').click(onCheckFlashSalesProductBox);
	//$('table#flashsales_product input[name="checkme"]').click(onCheckChekme);
	$('input.checkbox_offer_image').click(onCheckFlashSalesImageBox);
	$('input[name="submitAddflashsales_offer"]').click(onClickSubmitAddFlashsaleOffer);
});

// -----------------------------
// ----- EVENTS LISTENERS ------
// -----------------------------
function onCheckFlashSalesProductBox(e) {
	var element = $(this);
	var id_product = element.attr('value');
	var img_src = element.parent().next().next().children('img').attr('src');

	var elementsCheckedLength = $('input.flashsales_productBox:checked').length;
	if(element.is(':checked')) {
		if(elementsCheckedLength <= 1)
			var checked = true;
		else
			var checked = false;
		Prestashop.flashsales.backend.admin.displayOfferImage(id_product, img_src, checked)
	} else {
		Prestashop.flashsales.backend.admin.hideOfferImage(id_product);
	}
}

function onCheckChekme(e) {
	if($(this).is(':checked')) {
		$('input.flashsales_productBox').each(function(i){
			var element = $(this);
			var id_product = element.attr('value');
			var img_src = element.parent().next().next().children('img').attr('src');
			Prestashop.flashsales.backend.admin.displayOfferImage(id_product, img_src, (i == 0) ? true : false);
		});
	} else {
		$('input.flashsales_productBox').each(function(i){
			var id_product = $(this).attr('value');
			Prestashop.flashsales.backend.admin.hideOfferImage(id_product);
		});
	}
}

function onCheckFlashSalesImageBox(e) {
	var elementsCheckedLength = $('input.checkbox_offer_image:checked').length;
	if(elementsCheckedLength > Prestashop.flashsales.backend.nb_images  && Prestashop.flashsales.backend.nb_images != 0) {
		e.preventDefault();
		alert(Prestashop.flashsales.backend.too_much_images);
	}
}

function onClickSubmitAddFlashsaleOffer(e) {
	var elementsCheckedLength = $('input.checkbox_offer_image:checked').length;
	if(elementsCheckedLength != Prestashop.flashsales.backend.nb_images && Prestashop.flashsales.backend.nb_images != 0) {
		e.preventDefault();
		alert(Prestashop.flashsales.backend.too_less_images);
	}
}
// -----------------------------
// --------- FUNCTIONS ---------
// -----------------------------
Prestashop.flashsales.backend.admin.displayOfferImage = function(id_product, img_src, checked) {
	var ul = $('ul#offer_images_container');
	var li = $('<li class="flashsales_offer_image" id="flashsales_offer_image_' + id_product + '"></li>');
	var img = $('<img src="'+ img_src +'" alt="image" />');
	var checkbox = $('<input type="checkbox" class="checkbox_offer_image" name="flashsales_offer_image[]" value="' + id_product + '" />');
	checkbox.attr('checked', checked);
	checkbox.click(onCheckFlashSalesImageBox);
	li.append(img);
	li.append(checkbox);
	ul.append(li);

	if(ul.children('li').length > 0)
		$('.no_items').hide();
}
Prestashop.flashsales.backend.admin.hideOfferImage = function(id_product) {
	$('#flashsales_offer_image_' + id_product).remove();
	if($('ul#offer_images_container').children('li').length == 0)
		$('.no_items').show();
}