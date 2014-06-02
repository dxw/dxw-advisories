jQuery(function ($) {
  'use strict';

  $('.email_button').click(function(e) {

    if(!confirm('Are you sure?')) {
      return;
    }

    var data = {
      action: 'send_email',
      target: $(e.target).attr('id'),
      subject: $(e.target).data('subject'),
      body: $($(e.target).data('body')).val(),
    };

     console.log(data);

    $('.email_results').removeClass('alert-danger');

    $.post('/wp-admin/admin-ajax.php', data, function(data, status, xhr) {
      $('.email_results').html(data);

      if(status != 'success') {
        $('.email_results').addClass('alert-danger');
      }

      $('.email_results').removeClass('hidden');
    });
  });
});
