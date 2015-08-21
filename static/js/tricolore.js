+function registerBootstrapUtils()
{
  "use strict";

  $('[data-toggle="tooltip"]').tooltip({container: 'body'});
}();

+function destroyAllFlashMessages()
{
  "use strict";

  $('div.flash-message').not('.alert-important').delay(2000).slideUp(100);
}();

+function setUpNotificationPopover()
{
  "use strict";

  $('#notification-popover').popover({
    container: 'body',
    html : true,
    placement: 'bottom',
    trigger: 'focus',
    content: $('#notification-popover-content').html()
  });
}();