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
      $routeProvider.when('/new-user', {
        templateUrl : 'templates/edit-user.html',
        controller : 'EditUserController'
      });
      $routeProvider.when('/edit-user/:id', {
        templateUrl : 'templates/edit-user.html',
        controller : 'EditUserController'
      });
      $routeProvider.otherwise({
        redirectTo : '/tickets'
      });

    });

}(window.angular))
