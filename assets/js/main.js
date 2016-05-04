$(function () {
  $('[data-toggle="tooltip"]').tooltip()
  
  var condensedViewStorageKey = 'view.condensed';
  
  var toggleView = function () {
      $('.small-box').toggleClass('condensedView');
  };
  
  $('#toggleView').click(function() {
      if(localStorage)
      {
          var view = localStorage.getItem(condensedViewStorageKey);
          if(view === "enabled")
          {
              localStorage.setItem(condensedViewStorageKey, "disabled");
          }
          else
          {
              localStorage.setItem(condensedViewStorageKey, "enabled");
          }
      }
      
      toggleView();
  });
  
  if(localStorage)
  {
      var view = localStorage.getItem(condensedViewStorageKey);
      if(view === "enabled")
      {
          toggleView();
      }
  }
})
