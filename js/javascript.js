jQuery(document).ready(function($) {

$("#sbmcf_form_v1").on('submit', function(event) {

var sbmcf_n = $("#sbmcf_name").val();
var sbmcf_e = $("#sbmcf_email").val();
var sbmcf_s = $("#sbmcf_subject").val();
var sbmcf_m = $("#sbmcf_message").val();

var sbmcf_success_t = "Thank You. Your message was sent.";

var sbmcf_success_chng = $("#sbmcf_modal_t").html();


function sbmcf_modal_toggle() {
$("#sbmcf_modal_v1").modal('toggle');
}

function sbmcf_modal_chng() {
$("#sbmcf_modal_t").html(sbmcf_success_chng);	
}

 $.ajax({
  	    type: 'POST',
        url: ajax_object.ajax_url, 
        datatype : 'json', 
                                                                                                                                                                                                                     
        data: {
            'action': 'sbmcf_submit',
             psbmcf_n : sbmcf_n,
             psbmcf_e : sbmcf_e,
             psbmcf_s : sbmcf_s,
             psbmcf_m : sbmcf_m
        },
        success:function(data) {
            console.log(data);
$("#sbmcf_modal_t").html(sbmcf_success_t);

$("#sbmcf_name").val('');
$("#sbmcf_email").val('');
$("#sbmcf_subject").val('');
$("#sbmcf_message").val('');

setTimeout(sbmcf_modal_chng, 1000);

setTimeout(sbmcf_modal_toggle, 2000);

      },
        error: function(error){
            console.log(error);
        }
    }); 

 event.preventDefault();


});


});
