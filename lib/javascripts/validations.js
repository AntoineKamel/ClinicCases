//Functions to validate forms

//Validate case note submitted from the Home page quick add widget
function validQuickCaseNote (cseVals)
{
    var errors = [];

    //check description
    if (cseVals[4].value === '')
        {errors.push('<p>Please provide a description of what you did.</p>');}

    //check if the time entered is greater than 0 hours and 0 minutes
    if (cseVals[2].value == '0' && cseVals[3].value == '0')
    {errors.push('<p>Please indicate the amount of time for this activity.</p>');}

    errString = errors.join(' ');

    return errString;
}


//Validate case note submitted from the Cases page
function validCaseNote(cseVals)
{

	var errors = [];

	//check if a description has been put in the textarea
	if (cseVals[6].value === 'Describe what you did...' || cseVals[6].value === '')
	{errors.push('<p>Please provide a description of what you did.</p>');}

	//check if the time entered is greater than 0 hours and 0 minutes
	if (cseVals[1].value == '0' && cseVals[2].value == '0')
	{errors.push('<p>Please indicate the amount of time for this activity.</p>');}

	errString = errors.join(' ');

	return errString;

}

function validContact(contactVals)
{
    var errors = [];

    //check to see if the contact has a name or organization
    if (contactVals[0].value === '' && contactVals[1].value === '' && contactVals[2].value === '')
        {errors.push('<p>Please provide a name or organization for this contact.</p>');}

    errString = errors.join(' ');

    return errString;

}

function validEvent(eventVals)
{
    var errors = [];

    if ($.isEmptyObject(eventVals[0]))
        {errors.push('<p>You must select at least one responsible person.</p>');}

    if (eventVals[1].value === '')
        {errors.push('<p>Please provide a name ("What") for this event.</p>');}

    if (eventVals[3].value === '')
        {errors.push('<p>Please provide a start time for this event.</p>');}

    errString = errors.join(' ');

    return errString;

}

function validUser(formVals)
{
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var email = formVals.find('input[name="email"]');
    var phone = formVals.find('input[name*="phone"]');
    var group = formVals.find('select[name="grp"]');

    if (fname.val() === '')
    {
        errors.push('<p>Please provide a first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '')
    {
        errors.push('<p>Please provide a last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '')
    {
        errors.push('<p>An email account is required for each user.</p>');
        email.addClass('ui-state-error');
    }
    else
    {
        var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
        var illegalChars= /[\(\)\\<\>\,\;\:\\\"\[\]]/ ;

        if (!emailFilter.test(email.val()))
        {
            errors.push('<p>Email appears invalid</p>');
            email.addClass('ui-state-error');

        }
        else if (email.val().match(illegalChars))
        {
            errors.push('<p>Email contains invalid characters</p>');
            email.addClass('ui-state-error');
        }
    }

    phone.each(function(){
        if ($(this).val !=='')
        {
            if (/[^0-9-]/i.test($(this).val()) === true)
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone numbers can contain only numbers and dashes</p>');
            }
        }
    });

    if (group.val() === '')
    {
        errors.push('<p>You must assign the user to a group.</p>');
        group.next().addClass('ui-state-error');
    }

    errString = errors.join(' ');

    return errString;
}

function validProfile(formVals)
{
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var phone = formVals.find('input[name*="phone"]');
    var email = formVals.find('input[name="email"]');

    if (fname.val() === '')
    {
        errors.push('<p>You must provide your first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '')
    {
        errors.push('<p>You must provide your last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '')
    {
        errors.push('<p>You must provide an email address.</p>');
        email.addClass('ui-state-error');
    }
    else
    {
        var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
        var illegalChars= /[\(\)\\<\>\,\;\:\\\"\[\]]/ ;

        if (!emailFilter.test(email.val()))
        {
            errors.push('<p>Email appears invalid</p>');
            email.addClass('ui-state-error');

        }
        else if (email.val().match(illegalChars))
        {
            errors.push('<p>Email contains invalid characters</p>');
            email.addClass('ui-state-error');
        }

    }

    phone.each(function(){
        if ($(this).val !=='')
        {
            if (/[^0-9-]/i.test($(this).val()) === true)
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone numbers can contain only numbers and dashes</p>');
            }
        }
    });

    errString = errors.join(' ');

    return errString;
}

function validNewAccount(formVals)
{
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var phone = formVals.find('input[name*="phone"]');
    var mobile = formVals.find('input[name="mobile_phone"]');
    var email = formVals.find('input[name="email"]');
    var password = formVals.find('input[name="password"]');
    var passwordConfirm = formVals.find('input[name="confirm_password"]');

    if (password.val() === '')
    {
        errors.push('<p>You must provide a password .</p>');
        password.addClass('ui-state-error');
    }

    if (password.val() !== passwordConfirm.val())
    {
        errors.push('<p>The passwords you entered do not match.</p>');
        password.addClass('ui-state-error');
        passwordConfirm.addClass('ui-state-error');

    }

    if (fname.val() === '')
    {
        errors.push('<p>You must provide your first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '')
    {
        errors.push('<p>You must provide your last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '')
    {
        errors.push('<p>You must provide an email address.</p>');
        email.addClass('ui-state-error');
    }
    else
    {
        var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
        var illegalChars= /[\(\)\\<\>\,\;\:\\\"\[\]]/ ;

        if (!emailFilter.test(email.val()))
        {
            errors.push('<p>Email appears invalid</p>');
            email.addClass('ui-state-error');

        }
        else if (email.val().match(illegalChars))
        {
            errors.push('<p>Email contains invalid characters</p>');
            email.addClass('ui-state-error');
        }

    }

    if (mobile.val() === '')
    {
        errors.push('<p>You must provide a mobile phone number.</p>');
        fname.addClass('ui-state-error');
    }

    phone.each(function(){
        if ($(this).val !=='')
        {
            if (/[^0-9-]/i.test($(this).val()) === true)
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone numbers can contain only numbers and dashes</p>');
            }

            if ($(this).val().length < 10 && $(this).val().length > 1)
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Please ensure that the phone number contains an area code.</p>');
            }
        }
    });

    if (password.val().length < 8)
    {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password.val()) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password.val()) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password.val()) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }

    errString = errors.join(' ');

    return errString;
}


//Validations for add document by url
function isUrl(s) //used in cc7
{
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}


function isTitle(title) //used in cc7
{

    if (title === '')
    {
        $('title').style.background='yellow';
        alert('You must supply a title.');
        return false;}
    else
    {return true;}


}

function validPassword(password)
{
    var errors = [];
    if (password.length < 8)
    {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }

    errString = errors.join(' ');

    return errString;

}

function validPasswordChange(formVals)
{
    var errors = [];
    var password = formVals.find('input[name="new_pword"]').val();
    var passwordEnter = formVals.find('input[name="new_pword"]');
    var passwordConfirm = formVals.find('input[name="new_pword_confirm"]');

    //Check if all fields are filled in
    var missingField = null;

    formVals.find('input[name*="pword"]').each(function(){
        if ($(this).val() === '')
        {
            missingField = true;
        }
    });

    if (missingField === true)
    {errors.push('<p>You must complete all three fields.</p>');}

    //Check if passwords match
    if (passwordEnter.val() !== passwordConfirm.val())
        {
            errors.push('<p>The new passwords do not match.  Please try again.</p>');

            formVals.find('input[name*="new"]').addClass('ui-state-error');

            formVals.find('input[name="new_pword"]').click(function(){
                formVals.find('input[name*="new"]').removeClass('ui-state-error').val('');
            });
        }

    if (password.length < 8)
    {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }

    errString = errors.join(' ');

    return errString;

}

function newCaseValidate(formVals) //cc7
{
    var errors = [];
    //These fields are required for CC to work, marked 1 in cm_columns
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var org  = formVals.find('input[name="organization"]');
    var adv = formVals.find('input[name="adverse_parties"]');

    if (fname.val() === '' && lname.val() === '' && org.val() === '') {
        errors.push('<p>Please provide either a client name or organization name.</p>');
        fname.addClass('ui-state-error');
        lname.addClass('ui-state-error');
        org.addClass('ui-state-error');
    }

    //This is a crude attempt to stop users from putting
    //any data in adverse parties field that is not a name
    adv.each(function(){
        if ($(this).val() !== '')
        {
            String.prototype.countWords = function(){
            return this.split(/\s+/).length;
            };

            var numberOfWords = $(this).val().countWords();

            if (numberOfWords > 5)//because nobody has a name with more than five words, right?
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Please ensure that the adverse parties field has only proper names</p>');
            }
        }
    });

    //These fields are not required. May be removed by user
    if (formVals.find('input[name="ssn"]').length > 0){
        var ssn = formVals.find('input[name="ssn"]');
        if (ssn.val() !== '') {
            var stripped = ssn.val().replace(/[\(\)\.\-\ ]/g, '');
            var isNum = /^\d+$/.test(stripped);

            if (isNum === false) {
                errors.push('<p>Social Security Numbers must contain numbers only.</p>');
                ssn.addClass('ui-state-error');

            }
            else if (stripped.length !== 9) {
                errors.push('<p>This SSN appears to be invalid.  Please check it.</p>');
                ssn.addClass('ui-state-error');
            }
        }
    }

    if (formVals.find('input[name="dob"]').length > 0){
        var dob = formVals.find('input[name="dob"]');
        if(dob.val() !== '') {
            var dobVal = formVals.find('input[name="dob"]').val();
            var currentDate = new Date();
            var year = currentDate.getFullYear();
            if (dobVal.charAt(2) !== '/' || dobVal.charAt(5) !== '/') {
                errors.push('<p>DOB must be in the mm/dd/yyyy format.</p>');
                dob.addClass('ui-state-error');
            }

            var parts = [];
            parts = dobVal.split('/');

            if (parseInt(parts[1]) > 31 || parseInt(parts[1] < 1))
            {
                errors.push('<p>DOB Day must be between 1 and 31.</p>');
                dob.addClass('ui-state-error');
            }

            if (parts[2] >  year || parseInt(parts[2]) < 1899)
            {
                errors.push('<p>DOB Year must be between 1899 and ' + year + '.</p>');
                dob.addClass('ui-state-error');
            }

            var okMonths = new Array('01','02','03','04','05','06','07','08','09','10','11','12');

            var checker = false;
            for(var i=0;i < okMonths.length;i++){
                if(parts[0] == okMonths[i]){
                    checker = true;
                    break;
                }
            }

            if (checker === false) {
                errors.push('<p>DOB Months must be in the mm format, e.g 01,02,03 etc.</p>');
                dob.addClass('ui-state-error');
            }

        }
    }

    if (formVals.find('input[name="phone"]').length > 0){
        var phone = formVals.find('input[name="phone"]');
        phone.each(function(){

            if (/[^0-9-]/i.test($(this).val()) === true)
            {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone numbers can contain only numbers and dashes</p>');
            }
        });
    }

    if (formVals.find('input[name="email"]').length > 0){
        var email = formVals.find('input[name="email"]');
        email.each(function(){

            if ($(this).val() !== '') {
                var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
                var illegalChars= /[\(\)\\<\>\,\;\:\\\"\[\]]/ ;

                if (!emailFilter.test($(this).val())) {
                    $(this).addClass('ui-state-error');
                    errors.push('<p>Email appears invalid</p>');
                }
                else if ($(this).val().match(illegalChars)) {
                    $(this).addClass('ui-state-error');
                    errors.push('<p>Email contains invalid characters</p>');
                }
            }

        });
    }

    errString = errors.join(' ');

    return errString;
}


//Below:  cc6 validations
//This is to ensure that at least one person is resp. for an event
function checkEvent(valu)

{

var collector = document.getElementById('collect');
var date = document.getElementById(valu);
var task = document.getElementById('tasker');
if (collector.value == '')
{
alert('You must select at least one responsible party.');
return false;

}



if (date.value == '')
{
alert('Please select a date.');
return false;
}


if (task.value == '')
{
alert('What is to be done?');
return false;
}
return true;



}


// This is to check that a to: has been specified in new_message.php, forcing user to pick from drop-down list //
function checkTo()
{
var to = document.getElementById('to');
var toFull = document.getElementById('to_full');

if (toFull.value == 'All Students' || toFull.value== 'All Professors' || toFull.value == 'All Your Students' || toFull.value == 'All Users' || toFull.value == 'All on this Case' || toFull.value == 'All on a Case')
{
return true;
}

else
{
if (to.value == '' )
{alert ('The \"to\" line contains an invalid name. Try typing the first three letters of the recipient\'s first or last name in the \"to\" field and then choosing from the drop-down menu.');return false;}
}
return true;

}

//This is for events, allows only person who set it to modify or delete it //

function checkAuth(username,group,setby)
{
var tgt = 'prof';
if (setby !== username  && group !== tgt)
{alert('Sorry. Events can only be deleted or modified by the person who created them or by a professor.');return false;}
else
{return true;}

}

function forceDate(item)
{

var date = document.getElementById(item);
if (date.value == '')
{alert('You must select a date.  Click on the calendar icon to select the date.');return false;}
return true;
}

function allWarn()
{
chooser = document.getElementById('group');
if (chooser.value == 'All Users')
{
alert ('This will send a message to every user on the system.  If this is not what you want to do, please select a different group.');
return false;


}


}

function newUserValidate()
{
if (document.getElementById('first_name').value == '')
{alert ('First Name is required.');return false;}

if (document.getElementById('last_name').value == '')
{alert ('Last Name is required.');return false;}

if (document.getElementById('email').value == '')
{alert ('Email is required.');return false;}

if (document.getElementById('class').value == '')
{alert ('You must select a User Type');return false;}

return true;


}

function valAcct()
{
    if (document.getElementById('first_name').value == '' || document.getElementById('last_name').value == '' || document.getElementById('email').value == '')
        {alert('One of the required fields is blank.  Please review the form and add the necessary information.');return false;}

    if (document.getElementById('password').value.length < 6)
        {alert('Your password must be at least six characters in length');return false;}

        return true;



}

function validatePhone(fld) {
    var error = "";
    var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');

   if (fld.value == "") {
        alert("You didn't enter a phone number.\n");
        fld.style.background = 'Yellow';
    } else if (isNaN(parseInt(stripped))) {
        alert("The phone number contains illegal characters.\n");
        fld.style.background = 'Yellow';
    } else if (!(stripped.length == 10)) {
        alert("The phone number is the wrong length. Make sure you included an area code.\n");
        fld.style.background = 'Yellow';
    }

}


function trim(s)
{
  return s.replace(/^\s+|\s+$/, '');
}

function validateEmail(fld) {

    var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;

    if (fld.value == "") {
        fld.style.background = 'Yellow';
        alert('You didn\'t enter an email address.\n');
    } else if (!emailFilter.test(tfld)) {              //test email for illegal characters
        fld.style.background = 'Yellow';
        alert('Please enter a valid email address.\n');fld.value='';
    } else if (fld.value.match(illegalChars)) {
        fld.style.background = 'Yellow';
        alert('The email address contains illegal characters.\n');
    } else {
        fld.style.background = 'White';
    }

}





function isAlphaNum(str)
{

	if (str.match(/^[a-zA-Z0-9_\s]+$/))
	{
		return true;
	}
	else
	{
		alert('Folder name may only contain letters,numbers,spaces, and underscores.');return false;
	}


}

