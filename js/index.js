$(document).ready(
    function() {
		"use strict";		
        $("#socialuserimage").click(function() {
            $("#socialusermenu").fadeToggle();
			$("#usermessagemenu").hide();
			 			
        });
    });
$(document).ready(
    function() {
		"use strict";		
        $("#socialprofile").click(function() {
            $("#socialusermenu").fadeToggle();
			$("#usermessagemenu").hide();
			 			
        });
    });


$(document).ready(
    function() {
		"use strict";	
        $("#usermessages").click(function() {
            $("#usermessagemenu").fadeToggle();
			$("#socialusermenu").hide();
        });
    });
$(document).ready(
    function() {
		"use strict";
        $("#maincontent").click(function() {
            $("#usermessagemenu").hide();
			$("#socialusermenu").hide();
        });
    });