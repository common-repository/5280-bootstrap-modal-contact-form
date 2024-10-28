jQuery(document).ready(function($) {

var rad_selected = $("input[type='radio'][name='sbmcf_modal_group_name[sbmcf_modal_choice_1]']:checked").val();

if (rad_selected == 2) {
$("#sbmcf_input_ft").prop('readonly', true);
$("#sbmcf_input_btnclr").prop('readonly', true);
$("#sbmcf_input_txclr").prop('readonly', true);
}

if (rad_selected == 1) {
$("#sbmcf_textarea_cust").prop('readonly', true);
}

$("#sbmcf_radio2").click(function() {

var sbmcf_html = '<button class="btn btn-primary" id="sbmcf_modal_pop" href="#" role="button" data-toggle="modal" data-target="#sbmcf_modal_v1">Contact Us</button>' +
'<div class="modal fade" id="sbmcf_modal_v1" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">' +
'<div class="modal-dialog" role="document">' +
'<div class="modal-content">' +
'<div class="modal-header">' +
'<h5 class="modal-title" id="sbmcf_modal_t">Contact Us</h5>' +
'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
'<span aria-hidden="true">&times;</span>' +
'</button>' +
'</div>' +
'<div class="modal-body" id="sbmcf_modal_body">' +
'<form method="post" id="sbmcf_form_v1">' +
'<div class="form-group">' +
'<label for="sbmcf_name">Name</label>' +
'<input type="text" class="form-control" id="sbmcf_name" name="sbmcf_name" maxlength="50">' +
'</div>' +
'<div class="form-group">' +
'<label for="sbmcf_email">Email</label>' +
'<input type="email" class="form-control" id="sbmcf_email" name="sbmcf_email" maxlength="50" required>' +
'</div>' +
'<div class="form-group">' +
'<label for="sbmcf_subject">Subject</label>' +
'<input type="text" class="form-control" id="sbmcf_subject" name="sbmcf_subject" maxlength="100">' +
'</div>' +
'<div class="form-group">' +
'<label for="sbmcf_message">Enter your message here.</label>' +
'<textarea class="form-control" id="sbmcf_message" name="sbmcf_message" rows="3" maxlength="750"></textarea>' +
'</div>' +
'<button type="submit" class="btn btn-primary" id="sbmcf_submit_btn">Submit</button>  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
'</form>' +
'</div>' +
'</div>' +
'</div>' +
'</div>';

$("#sbmcf_textarea_cust").val(sbmcf_html);

$("#sbmcf_input_ft").val('');
$("#sbmcf_input_btnclr").val('');
$("#sbmcf_input_txclr").val('');

$("#sbmcf_input_ft").prop('readonly', true);
$("#sbmcf_input_btnclr").prop('readonly', true);
$("#sbmcf_input_txclr").prop('readonly', true);

$("#sbmcf_textarea_cust").prop('readonly', false);

});

$("#sbmcf_radio1").click(function() {
$("#sbmcf_textarea_cust").val('');
$("#sbmcf_textarea_cust").prop('readonly', true);
$("#sbmcf_input_ft").prop('readonly', false);
$("#sbmcf_input_btnclr").prop('readonly', false);
$("#sbmcf_input_txclr").prop('readonly', false);
});


});