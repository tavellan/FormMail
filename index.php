<?php
/*
 * (C) 2015- Tero Avellan
 * Auranmaan aluetietoverkko Oy
 * http://www.aumanet.fi
 */

if (isset($_REQUEST['schema'])) {
  $jsonSchema = stripslashes($_REQUEST['schema']);
} else {
  $jsonSchema = '{"schema": {"subject": {"type": "string","title": "Aihe","enum": [ "yhteydenotto", "palaute", "muu" ],"required": true},"name": {"type": "string","title": "Nimi","required": true},"email": {"type": "string","title": "Sähköpostiosoite","required": true},"phone": {"type": "string","title": "Puhelin","required": true },"message": {"type": "string","title": "Viesti","required": true },"submit": {"type": "action","title": "Lähetä"}},"form": [{"key":"subject","titleMap": {"yhteydenotto": "Yhteydenottopyyntö","palaute": "Palaute","muu": "Muu viesti"}},{"key":"name","type": "text"},{"key":"email", "type": "text"},{"key":"phone","type": "text"},{"key":"message","type": "textarea"},{"key":"submit","type": "submit"}],"mailconf": {"MIME-Version": "","Content-type": "text/html","To": "","From": "","Cc": "","Bcc": "","Reply-To": "","Response-OK": "Kiitos yhteydenotosta!","Response-Error": "","Subject": "Yhteydenottopyyntö"}}';
}
?>
<!DOCTYPE html>
<html>
<head>
  <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <META http-equiv="Content-Language" content="fi">
  <title></title>
</head> 
<body>
   <script type="text/javascript" src="form.js"></script>
   <script type="text/javascript">
   initForm('<?php echo $jsonSchema; ?>');
   </script>
</body>
</html>
