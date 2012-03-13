var name = 'module';
if(typeof Prestashop == 'undefined')
	var Prestashop = {};
if(typeof Prestashop.module == 'undefined')
	Prestashop.module = {};

Prestashop.module.frontend = {};

$(document).ready(function() {
  $("").click();
});

// -----------------------------
// ----- EVENTS LISTENERS ------
// -----------------------------
function onClickListener(e) {
	e.preventDefault();

	var element = $(this);
}

// -----------------------------
// --------- FUNCTIONS ---------
// -----------------------------

Prestashop.module.frontend.fct = function(variable, callback) {
	
	return callback;
}