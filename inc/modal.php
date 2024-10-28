<button style="color:<?php echo $my_t_color; ?>;background-color:<?php echo $my_color; ?>;border-color:<?php echo $my_color; ?>;" class="btn" id="sbmcf_modal_pop" href="#" role="button" data-toggle="modal" data-target="#sbmcf_modal_v1">Contact Us</button>
<div class="modal fade" id="sbmcf_modal_v1" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sbmcf_modal_t"><?php echo $my_title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="sbmcf_modal_body">
      <?php echo '<img style="max-width:70px;margin-bottom:10px;" class="form-person img-fluid" src="' . plugins_url('images/form-person.svg', dirname(__FILE__ )) . '">'; ?>
      <form method="post" id="sbmcf_form_v1">
  <div class="form-group">
    <label for="sbmcf_name">Name</label>
    <input type="text" class="form-control" id="sbmcf_name" name="sbmcf_name" maxlength="50">
  </div>
  <div class="form-group">
    <label for="sbmcf_email">Email</label>
    <input type="email" class="form-control" id="sbmcf_email" name="sbmcf_email" maxlength="50" required>
  </div>
   <div class="form-group">
    <label for="sbmcf_subject">Subject</label>
    <input type="text" class="form-control" id="sbmcf_subject" name="sbmcf_subject" maxlength="100">
  </div>
  <div class="form-group">
    <label for="sbmcf_message">Enter your message here.</label>
    <textarea class="form-control" id="sbmcf_message" name="sbmcf_message" rows="3" maxlength="750"></textarea>
  </div>
  <button type="submit" style="color:<?php echo $my_t_color; ?>;background-color:<?php echo $my_color; ?>;border-color:<?php echo $my_color; ?>;" class="btn" id="sbmcf_submit_btn">Submit</button>  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</form>
</div>
     </div>
  </div>
</div>