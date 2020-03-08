// assets/js/registration.js
document.getElementById("registration_form_firstName").focus();

var accountType = document.getElementById('registration_form_accountType');
var uniqueName = document.getElementById('registration_form_uniqueName');

var accountTypeValue = accountType.options[accountType.selectedIndex].value;

if (accountTypeValue == "INDIVIDUAL") {
	uniqueName.disabled = true;
} else {
	uniqueName.disabled = false;
}

accountType.addEventListener('change', function() {
	var accountTypeValue = accountType.options[accountType.selectedIndex].value;
	if (accountTypeValue == "INDIVIDUAL") {
		uniqueName.value = "";
		uniqueName.disabled = true;
	} else {
		uniqueName.disabled = false;
	}
});
