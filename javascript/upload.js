(function() {
  var fieldsets, steps;
  
  function showStep(step) {
    fieldsets.hide();
    $('#step-' + step.attr('data-step')).show();
  
    steps.removeClass('active');
    step.addClass('active');
    
    $('#upload-previous').attr('disabled', step[0] == steps.filter(':first-child')[0]);
    $('#upload-next').attr('disabled', step[0] == steps.filter(':last-child')[0]);
  }
  
  $(function() {
    fieldsets = $('.step-fieldset');
    steps = $('.upload-header .step');
    
    fieldsets.slice(1).hide();
  
    steps.click(function(event) {
      showStep($(event.target));
    });
    
    $('.upload-nav').show();
    $('#upload-next').attr('disabled', false);
    
    $('#upload-previous').click(function(event) {
      showStep(steps.filter('.active').prev());
      event.preventDefault();
    });
    $('#upload-next').click(function(event) {
      showStep(steps.filter('.active').next());
      event.preventDefault();
    });
    
    $('.step-fieldset h2').hide();
  });
})();