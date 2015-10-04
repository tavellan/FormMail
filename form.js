/*
 * (C) 2015- Tero Avellan
 * Auranmaan aluetietoverkko Oy
 * http://www.aumanet.fi
 */

var appURL = '/tools/FormMail/';
var formSchema;
var head  = document.getElementsByTagName('head')[0];
var link  = document.createElement('link');
link.rel  = 'stylesheet';
link.type = 'text/css';
link.href = appURL+'form.css';
link.media = 'all';
head.appendChild(link);

function sendForm() {
  var formArray = {};
  var fieldsArray = {};
  if(this.formSchema!='') {
    var formObj = JSON.parse(this.formSchema);
    // Form fields
    for (var field in formObj.form) {
        if ("type" in formObj.form[field]) {
          if (formObj.form[field].type == "text" || formObj.form[field].type == "textarea") {
            fieldObj = document.getElementById('frm'+formObj.form[field].key); 
          }
          if (formObj.form[field].type != "submit") {
           fieldsArray[formObj.schema[formObj.form[field].key].title] = fieldObj.value;
          }
        } else if ("titleMap" in formObj.form[field]) {
            fieldObj = document.getElementById('frm'+formObj.form[field].key);
            fieldsArray[formObj.schema[formObj.form[field].key].title] = fieldObj.value;
        }
    }
    formArray['fields'] = fieldsArray;

    // Mail configuration
    for (var field in formObj.mailconf) {
      formArray['mailconf'] = formObj.mailconf;
    }
    var strJSON = JSON.stringify(formArray);
    request = new XMLHttpRequest();
    request.open("POST",this.appURL+'sendForm.php',true);
    request.setRequestHeader("Content-type", "application/json");
    request.send(strJSON);
    request.onreadystatechange = function() {
      if(request.readyState == 4 && request.status == 200) {
        var responseStatusObj = JSON.parse(request.responseText);
        console.log(responseStatusObj.response['status']+' '+responseStatusObj.response['message']);
        document.getElementById('formMailContainer').classList.add('responseOK');
        if ("Response-OK" in formObj.mailconf) {
          document.getElementById('formMailContainer').innerHTML = formObj.mailconf['Response-OK'];
        } else {
          document.getElementById('formMailContainer').innerHTML = responseStatusObj.response['status']+' '+responseStatusObj.response['message'];
        }
      }
    }
  }
}

function makeForm() {
  if(this.formSchema!='') {
    document.writeln('<div id="formMailContainer">');
    document.writeln('<form action="javascript:void(0);" method="post">');
    var formObj = JSON.parse(this.formSchema);
    for (var field in formObj.form) {
        if ("type" in formObj.form[field]) {
          if (formObj.form[field].type != "submit") {
            document.writeln('<label>'+formObj.schema[formObj.form[field].key].title+':</label>');
          }
          if (formObj.form[field].type == "text") {
            document.writeln('<input type="text" id="frm'+formObj.form[field].key+'" name="'+formObj.form[field].key+'"/>');
          }
          else if (formObj.form[field].type == "textarea") {
            document.writeln('<textarea id="frm'+formObj.form[field].key+'" name="'+formObj.form[field].key+'"></textarea>');
          }
          else if (formObj.form[field].type == "submit") {
            document.writeln('<button onClick="sendForm();">'+formObj.schema[formObj.form[field].key].title+'</button>');
          }
        } else if ("titleMap" in formObj.form[field]) {
            document.writeln('<label>'+formObj.schema[formObj.form[field].key].title+':</label>');
            document.writeln('<select id="frm'+formObj.form[field].key+'" name="'+formObj.form[field].key+'">');
            for (key in formObj.form[field].titleMap) {
              document.writeln('<option value="'+key+'">'+formObj.form[field].titleMap[key]+'</option>');
            }
            document.writeln('</select>');
        }
    }
    document.writeln('</form>');
    document.writeln('</div>');
  }
}

function initForm(formSchema) {
  this.formSchema = formSchema;
  makeForm();
}