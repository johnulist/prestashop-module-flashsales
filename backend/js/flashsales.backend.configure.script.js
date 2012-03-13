var name = 'module';
if(typeof Prestashop == 'undefined')
	var Prestashop = {};
if(typeof Prestashop.module == 'undefined')
	Prestashop.module = {};
if(typeof Prestashop.module.backend == 'undefined')
	Prestashop.module.backend = {};
if(typeof Prestashop.module.backend.configure == 'undefined')
	Prestashop.module.backend.configure = {};

Prestashop.module.backend.configure.script = {};
Prestashop.module.backend.ajaxUrl = '../modules/'+ name +'/ajax.php';
Prestashop.module.backend.dataType = 'json';

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

Prestashop.module.backend.configure.fct = function(variable, callback) {
	
	return callback;
}