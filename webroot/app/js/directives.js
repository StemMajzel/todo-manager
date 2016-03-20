(function(angular) {

  'use strict;'
  var directives = angular.module('todoManager.directives', []);
  
  directives.directive('ngConfirmClick', function(){
    return {
      link : function(scope, element, attr) {
        var msg = attr.ngConfirmClick;
        var clickAction = attr.confirmedClick;
        element.bind('click', function(event) {
          if (window.confirm(msg)) {
            scope.$apply(clickAction)
          }
        });
      }
    };
  });

}(window.angular))
