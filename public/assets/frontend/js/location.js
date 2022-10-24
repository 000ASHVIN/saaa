Skip to content
This repository
Search
Pull requests
Issues
Marketplace
Explore
@SAIBA0011
Sign out
10
30 45 hiiamrohit/php_ajax_country_state_city_dropdown
Code  Issues 3  Pull requests 1  Projects 0  Wiki  Insights
php_ajax_country_state_city_dropdown/js/location.js
96f159f  on Jan 13, 2017
@hiiamrohit hiiamrohit Update location.js
@hiiamrohit @PatelUtkarsh

Executable File  123 lines (111 sloc)  3.63 KB

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            console.log(e);
            alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

function locationInfo() {
    var rootUrl = "api.php";
    var call = new ajaxCall();
    this.getCities = function(id) {
        $(".cities option:gt(0)").remove();
        var url = rootUrl+'?type=getCities&stateId=' + id;
        var method = "post";
        var data = {};
        $('.cities').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.cities').find("option:eq(0)").html("Select City");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.cities').append(option);
                });
                $(".cities").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        });
    };

    this.getStates = function(id) {
        $(".states option:gt(0)").remove();
        $(".cities option:gt(0)").remove();
        var url = rootUrl+'?type=getStates&countryId=' + id;
        var method = "post";
        var data = {};
        $('.states').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.states').find("option:eq(0)").html("Select State");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.states').append(option);
                });
                $(".states").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        });
    };

    this.getCountries = function() {
        var url = rootUrl+'?type=getCountries';
        var method = "post";
        var data = {};
        $('.countries').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.countries').find("option:eq(0)").html("Select Country");
            console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('.countries').append(option);
                });
                $(".countries").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        });
    };

}

$(function() {
    var loc = new locationInfo();
    loc.getCountries();
    $(".countries").on("change", function(ev) {
        var countryId = $(this).val();
        if(countryId != ''){
            loc.getStates(countryId);
        }
        else{
            $(".states option:gt(0)").remove();
        }
    });
    $(".states").on("change", function(ev) {
        var stateId = $(this).val();
        if(stateId != ''){
            loc.getCities(stateId);
        }
        else{
            $(".cities option:gt(0)").remove();
        }
    });
});


© 2018 GitHub, Inc.
    Terms
Privacy
Security
Status
Help
Contact GitHub
API
Training
Shop
Blog
About
Press h to open a hovercard with more details.