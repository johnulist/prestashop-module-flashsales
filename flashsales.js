var name = 'flashsales';
if(typeof Prestashop == 'undefined')
	var Prestashop = {};
if(typeof Prestashop.flashsales == 'undefined')
	Prestashop.flashsales = {};

Prestashop.flashsales.frontend = {};

//global variables
Prestashop.flashsales.frontend.combinations = new Array();
Prestashop.flashsales.frontend.globalQuantity = new Number;
Prestashop.flashsales.frontend.selectedCombination = new Array();

$(document).ready(function() {
  $('#flashsalesoffer a.acheter').click(onClickBuyButton);
	$('.groups select').change(Prestashop.flashsales.frontend.findCombination);
});

// -----------------------------
// ----- EVENTS LISTENERS ------
// -----------------------------
function onClickBuyButton(e) {
	e.preventDefault();

	var id_product = $(this).parent().prev().find('.hidden #product_id').val();
	var id_combination = $(this).parent().prev().find('.hidden #idCombination').val();
	var quantity_wanted = $(this).prev().val();

	// add the picture to the cart
	$('#bigpic').show();
	var $element = $('#bigpic');
	var $picture = $element.clone();
	var pictureOffsetOriginal = $element.offset();

	if ($picture.size())
		$picture.css({'position': 'absolute', 'top': pictureOffsetOriginal.top, 'left': pictureOffsetOriginal.left});

	var pictureOffset = $picture.offset();
	var cartBlockOffset = $('#cart').offset();
	$('#bigpic').hide();
	// Check if the block cart is activated for the animation
	if (cartBlockOffset != undefined && $picture.size())
	{
		$picture.appendTo('body');
		$picture.css({ 'position': 'absolute', 'top': $picture.css('top'), 'left': $picture.css('left') })
		.animate({ 'width': $element.attr('width')*0.66, 'height': $element.attr('height')*0.66, 'opacity': 0.2, 'top': cartBlockOffset.top + 30, 'left': cartBlockOffset.left + 15 }, 1000)
		.fadeOut(100, function() {
			ajaxCart.add(id_product, id_combination, true, null, quantity_wanted, null);
		});
	}

}
// -----------------------------
// --------- FUNCTIONS ---------
// -----------------------------
Prestashop.flashsales.frontend.addCombination = function(idProduct, idCombination, arrayOfIdAttributes, quantity, price, ecotax, id_image, reference, unit_price, minimal_quantity)
{
	Prestashop.flashsales.frontend.globalQuantity += quantity;

	var combination = new Array();
	combination['idCombination'] = idCombination;
	combination['quantity'] = quantity;
	combination['idsAttributes'] = arrayOfIdAttributes;
	combination['price'] = price;
	combination['ecotax'] = ecotax;
	combination['image'] = id_image;
	combination['reference'] = reference;
	combination['unit_price'] = unit_price;
	combination['minimal_quantity'] = minimal_quantity;
	Prestashop.flashsales.frontend.combinations[idProduct].push(combination);

}

Prestashop.flashsales.frontend.findCombination = function()
{
	var idProduct = $(this).prev().val();
	var priceHtml = $(this).parent().parent().prev().prev();

	var choice = new Array();
	$('div.groups select').each(function(){
		choice.push($(this).val());
	});

	//testing every combination to find the conbination's attributes' case of the user
	for (var combination = 0; combination < Prestashop.flashsales.frontend.combinations[idProduct].length; ++combination)
	{
		//verify if this combinaison is the same that the user's choice
		var combinationMatchForm = true;
		$.each(Prestashop.flashsales.frontend.combinations[idProduct][combination]['idsAttributes'], function(key, value)
		{
			if (!in_array(value, choice))
				combinationMatchForm = false;
		});

		if (combinationMatchForm)
		{
			if (Prestashop.flashsales.frontend.combinations[idProduct][combination]['minimal_quantity'] > 1)
				$('#quantity_wanted').val(Prestashop.flashsales.frontend.combinations[combination]['minimal_quantity']);

			//combination of the user has been found in our specifications of Prestashop.flashsales.frontend.combinations (created in back office)
			Prestashop.flashsales.frontend.selectedCombination['unavailable'] = false;
			Prestashop.flashsales.frontend.selectedCombination['reference'] = Prestashop.flashsales.frontend.combinations[idProduct][combination]['reference'];
			$('#idCombination').val(Prestashop.flashsales.frontend.combinations[idProduct][combination]['idCombination']);

			//get the data of product with these attributes
			quantityAvailable = Prestashop.flashsales.frontend.combinations[idProduct][combination]['quantity'];
			Prestashop.flashsales.frontend.selectedCombination['price'] = Prestashop.flashsales.frontend.combinations[idProduct][combination]['price'];

			Prestashop.flashsales.frontend.selectedCombination['unit_price'] = Prestashop.flashsales.frontend.combinations[idProduct][combination]['unit_price'];
			if (Prestashop.flashsales.frontend.combinations[idProduct][combination]['ecotax'])
				Prestashop.flashsales.frontend.selectedCombination['ecotax'] = Prestashop.flashsales.frontend.combinations[idProduct][combination]['ecotax'];
			else
				Prestashop.flashsales.frontend.selectedCombination['ecotax'] = 1;//default_eco_tax;

				Prestashop.flashsales.frontend.updatePrice(idProduct, priceHtml);

			return;
		}
	}
	//this combination doesn't exist (not created in back office)
	Prestashop.flashsales.frontend.selectedCombination['unavailable'] = true;
}

Prestashop.flashsales.frontend.updatePrice = function(idProduct, priceHtml) {
	if (!Prestashop.flashsales.frontend.selectedCombination['unavailable'] && eval('productShowPrice_'+idProduct) == 1)
	{
		if (!displayPrice && !eval('noTaxForThisProduct_' + idProduct))
		{
			var priceTaxExclWithoutGroupReduction = ps_round(eval('productPriceTaxExcluded_' + idProduct) , 6) * (1 / eval('group_reduction_' + idProduct));
		} else {
			var priceTaxExclWithoutGroupReduction = ps_round(eval('productPriceTaxExcluded_' + idProduct), 6) * (1 / eval('group_reduction_' + idProduct));
		}
		var combination_add_price = Prestashop.flashsales.frontend.selectedCombination['price'] * eval('group_reduction_' + idProduct);

		var tax = (eval('taxRate_' + idProduct)  / 100) + 1;
		var taxExclPrice = (eval('specific_price_' + idProduct) ? (eval('specific_currency_' + idProduct) ? eval('specific_price_' + idProduct) : eval('specific_price_' + idProduct) * currencyRate) : priceTaxExclWithoutGroupReduction) + Prestashop.flashsales.frontend.selectedCombination['price'] * currencyRate;

		if (eval('specific_price_' + idProduct))
			var productPriceWithoutReduction = priceTaxExclWithoutGroupReduction + Prestashop.flashsales.frontend.selectedCombination['price'] * currencyRate;

		if (!displayPrice && !eval('noTaxForThisProduct_' + idProduct))
		{
			var productPrice = taxExclPrice * tax;
			if (eval('specific_price_' + idProduct))
				productPriceWithoutReduction = ps_round(productPriceWithoutReduction * tax, 2);
		}
		else
		{
			var productPrice = ps_round(taxExclPrice, 2);
			if (eval('specific_price_' + idProduct))
				productPriceWithoutReduction = ps_round(productPriceWithoutReduction, 2);
		}

		var reduction = 0;
		var reduction_price = eval('reduction_price_' + idProduct);
		if (reduction_price || eval('reduction_percent_' + idProduct))
		{
      reduction_price = (eval('specific_currency_' + idProduct) ? reduction_price : reduction_price * currencyRate);
			reduction = productPrice * (parseFloat(eval('reduction_percent_' + idProduct)) / 100) + reduction_price;
			if (reduction_price && (displayPrice || eval('noTaxForThisProduct_' + idProduct)))
				reduction = ps_round(reduction / tax, 6);
		}

		if (!eval('specific_price_' + idProduct))
			productPriceWithoutReduction = productPrice * eval('group_reduction_' + idProduct);

		productPrice -= reduction;

		var tmp = productPrice * eval('group_reduction_' + idProduct);
		productPrice = ps_round(productPrice * eval('group_reduction_' + idProduct), 2);

		//var ecotaxAmount = !displayPrice ? ps_round(Prestashop.flashsales.frontend.selectedCombination['ecotax'] * (1 + ecotaxTax_rate / 100), 2) : selectedCombination['ecotax'];
		//productPrice += ecotaxAmount;
		//productPriceWithoutReduction += ecotaxAmount;

		//productPrice = ps_round(productPrice * currencyRate, 2);
		
		priceHtml.html();
		if (productPrice > 0)
		{
			var priceText = formatCurrency(productPrice, currencyFormat, currencySign, currencyBlank);
			var pHTML = priceText.split(',');
			priceHtml.html(pHTML[0] + ',<span class="cts-product">' + pHTML[1] + '</span>');
		}
		else
		{
			var priceText = formatCurrency(0, currencyFormat, currencySign, currencyBlank);
			var pHTML = priceText.split(',');
			priceHtml.html(pHTML[0] + ',<span class="cts-product">' + pHTML[1] + '</span>');
		}

		//$('#old_price_display').text(formatCurrency(productPriceWithoutReduction, currencyFormat, currencySign, currencyBlank));
	}
}

function in_array(value, array)
{
	for (var i in array)
		if (array[i] == value)
			return true;
	return false;
}

function formatCurrency(price, currencyFormat, currencySign, currencyBlank)
{
	// if you modified this function, don't forget to modify the PHP function displayPrice (in the Tools.php class)
	blank = '';
	price = parseFloat(price.toFixed(6));
	price = ps_round(price, priceDisplayPrecision);
	if (currencyBlank > 0)
		blank = ' ';
	if (currencyFormat == 1)
		return currencySign + blank + formatNumber(price, priceDisplayPrecision, ',', '.');
	if (currencyFormat == 2)
		return (formatNumber(price, priceDisplayPrecision, ' ', ',') + blank + currencySign);
	if (currencyFormat == 3)
		return (currencySign + blank + formatNumber(price, priceDisplayPrecision, '.', ','));
	if (currencyFormat == 4)
		return (formatNumber(price, priceDisplayPrecision, ',', '.') + blank + currencySign);
	return price;
}

function formatNumber(value, numberOfDecimal, thousenSeparator, virgule)
{
	value = value.toFixed(numberOfDecimal);
	var val_string = value+'';
	var tmp = val_string.split('.');
	var abs_val_string = (tmp.length == 2) ? tmp[0] : val_string;
	var deci_string = ('0.' + (tmp.length == 2 ? tmp[1] : 0)).substr(2);
	var nb = abs_val_string.length;

	for (var i = 1 ; i < 4; i++)
		if (value >= Math.pow(10, (3 * i)))
			abs_val_string = abs_val_string.substring(0, nb - (3 * i)) + thousenSeparator + abs_val_string.substring(nb - (3 * i));

	if (parseInt(numberOfDecimal) == 0)
		return abs_val_string;
	return abs_val_string + virgule + (deci_string > 0 ? deci_string : '00');
}