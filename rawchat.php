<?php
session_start();

function sanitize($text) {
    return htmlspecialchars(stripslashes(trim($text)));
}

if(isset($_POST['text'], $_POST['name'])) {
    $name = sanitize($_POST['name']);
    $text = sanitize($_POST['text']);
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$name."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}
?>

<html>
<head>
<title>Chat Room</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#submitmsg").click(function(){   
        var clientmsg = $("#text").val();
        var clientname = $("#name").val();
        $.post("rawchat.php", {text: clientmsg, name: clientname});              
        $("#text").attr("value", "");
        return false;
    });

    function loadLog(){     
        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
        $.ajax({
            url: "log.html",
            cache: false,
            success: function(html){        
                $("#chatbox").html(html); 
                
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }               
            },
        });
    }

    setInterval (loadLog, 2500);
});
</script>
</head>
<body>
    <h1>RawChat</h1>
<div id="chatbox"><?php
if(file_exists("log.html") && filesize("log.html") > 0){
    $handle = fopen("log.html", "r");
    $contents = fread($handle, filesize("log.html"));
    fclose($handle);
    echo $contents;
}
?></div>

<form name="message" action="">
    <input name="name" type="text" id="name" size="15" placeholder="username"/>
    <input name="text" type="text" id="text" size="63" placeholder="message"/>
    <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
</form>
<style>
   body {
    font: 12px arial;
    color: #222;
    text-align: center;
    padding: 35px; 
}

div#chatbox {
    text-align: left;
    margin: 0 auto;
    margin-bottom: 25px;
    padding: 10px;
    background: #fff;
    height: 270px;
    width: 430px;
    border: 1px solid #ACD8F0;
    overflow: auto;
}

input#text { 
    width: 310px; 
}

input#name { 
    width: 110px; 
}

    </style>
</body>
</html>
