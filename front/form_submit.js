$(document).ready(function(){
    $(".my_form").submit(function(e){
        e.preventDefault();
    
        var dataString = $(this).serialize();
    
        $.ajax({
            type: "POST",
            url: "/php/script.php",
            data: dataString,
            success: function(data) {
                $('.response').html(function(){
                    return data;
                    }
                )
            }
        })
    });
})