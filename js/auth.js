
function addNumber(e){
    //document.getElementById('PINbox').value = document.getElementById('PINbox').value+element.value;
    var v = $( "#PINbox" ).val();
    $( "#PINbox" ).val( v + e.value );
}
function clearForm(e){
    //document.getElementById('PINbox').value = "";
    $( "#PINbox" ).val( "" );
}
function submitForm(e,f,g) {
    if (e.value == "") {
        alert("Enter a PIN");
    } else {
        /*alert( "Your PIN has been sent! - " + e.value );*/
        data = {
            pin: e.value
        }


        apiCall( data, function( r ) {
            ;
            /*( "#logo" ).attr( "src", r.site_logo );
                $( ".title-msg" ).text( r.site_msg );
                accent = r.accent;
                $( ".accent-bg" ).css( "background-color", accent );*/
        });


        //document.getElementById('PINbox').value = "";

    };
};


function apiCall( post, callback ) {
    $.ajax({
        type: "POST",
        url: "./api.php",
        data: ( post ),
        dataType: "JSON",
        success: function (response) {
            var decodedJson = response;
            console.log(decodedJson[0]);
        },
        error: function ( response ) {
            console.log( response );
            clearForm();
        },
    });
}


