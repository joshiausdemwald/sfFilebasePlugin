// This is only to refresh the tree during file upload
swfu_widget.addEventListener(window, 'load', function(ev)
{
  var to = null;
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_success', function(ev)
  {
    if(to === null)
    {
      to = window.setTimeout(function()
      {
        if(typeof Sf_Filebase_Tree_0 !== 'undefined')
          Sf_Filebase_Tree_0.getRootNode().reload();
        to = null;
      }, 2000);
    }
  });
});