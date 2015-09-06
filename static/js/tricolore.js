+function registerBootstrapUtils()
{
  $('[data-toggle="tooltip"]').tooltip({container: 'body'});
}();

+function destroyAllFlashMessages()
{
  $('div.flash-message').not('.alert-important').delay(2000).slideUp(100);
}();

+function setUpNotificationPopover()
{
  $('#notification-popover').popover({
    container: 'body',
    html : true,
    placement: 'bottom',
    trigger: 'focus',
    content: $('#notification-popover-content').html()
  });
}();