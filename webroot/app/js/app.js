(function(angular) {
  'use strict;'
  var app = angular.module('todoManager', ['ngRoute', 'todoManager.controllers', 'todoManager.directives'])

    /**
    * Module configuration
    */
    app.config(function($routeProvider) {

      // modify route provider
      $routeProvider.when('/tickets', {
        templateUrl : 'templates/tickets.html',
        controller : 'TicketListController'
      });
      $routeProvider.when('/new-ticket', {
        templateUrl : 'templates/edit-ticket.html',
        controller : 'EditTicketController'
      });
      $routeProvider.when('/edit-ticket/:id', {
        templateUrl : 'templates/edit-ticket.html',
        controller : 'EditTicketController'
      });
      $routeProvider.otherwise({
        redirectTo : '/tickets'
      });

    });

}(window.angular))
