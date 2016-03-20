(function(angular) {

  'use strict;'
  var controllers = angular.module('todoManager.controllers', []);

  /**
  * Main controller
  */
  controllers.controller('TodoManagerController', function($scope, $rootScope, $location) {

    // Return active when path is the same as the param
    $scope.activeWhenPath = function(check_path) {
      return (check_path == $location.url()) ? 'active' : '';
    };

    // Set appUrl
    $rootScope.appUrl = "http://127.0.0.1";
  });

  /**
  * Ticket list controller
  */
  controllers.controller('TicketListController', function($scope, $rootScope, $http, $location) {

    // set defaults
    $scope.sort_field = 'Ticket.priority';
    $scope.sort_order = 'asc';
    $scope.items_start = 0;
    $scope.items_per_page = 5;
    $scope.current_page = 1;
    $scope.number_of_pages = 1;

    // clamp helper function
    var clamp = function(num, min, max) {
      return Math.min(Math.max(num, min), max);
    }

    // get pages
    var getPages = function(max_pages_radius) {
      var pages = [];
      if ($scope.number_of_pages > 0) {
        var min = clamp($scope.current_page - max_pages_radius, 1, $scope.number_of_pages);
        var max = clamp($scope.current_page + max_pages_radius, 1, $scope.number_of_pages);

        if (min > 1) {
          pages.push({
            page : 1,
            text : '<<'
          })
        }

        for (var i = min; i <= max; i++) {
          pages.push({
            page : i,
            text : i
          });
        }

        if (max < $scope.number_of_pages) {
          pages.push({
            page : $scope.number_of_pages,
            text : '>>'
          })
        }
      }
      return pages;
    }

    // Get all tickets
    var getTickets = function() {
      $http.get($rootScope.appUrl + '/tickets/range/' + $scope.items_start + '/' + $scope.items_per_page + '/' + ($scope.sort_field ? $scope.sort_field : 'Ticket.priority') + '/' + ($scope.sort_order ? 'asc' : 'desc') + '.json')
        .success(function(data, status, headers, config) {
          $scope.tickets = data.tickets.tickets;
          $scope.tickets_count = data.tickets.count;
          $scope.number_of_pages = Math.ceil($scope.tickets_count / $scope.items_per_page);
          $scope.pages = getPages(2);
        });
    }
    getTickets();

    // edit ticket - link
    $scope.editTicket = function(index) {
      $location.path('/edit-ticket/' + index);
    }

    // table sorting
    $scope.sort = function(field_name) {
      $scope.sort_field = field_name;
      $scope.sort_order = !$scope.sort_order;
      getTickets();
    }

    // change page
    $scope.changePage = function(page_number) {
      $scope.current_page = page_number;
      $scope.items_start = (page_number - 1) * $scope.items_per_page;
      getTickets();
    }

    // check which pagination page is active
    $scope.activePage = function(page_number) {
      return (page_number == $scope.current_page) ? 'active' : '';
    }
  });

  /**
  * Edit ticket controller
  */
  controllers.controller('EditTicketController', function($scope, $rootScope, $http, $location, $routeParams) {

    // Create empty object, so we dont get undefined error
    $scope.ticket = {};

    // Get all users
    var getUsers = function() {
      $http.get($rootScope.appUrl + '/users.json').success(function(data, status, headers, config) {
        $scope.users = data.users;
      });
    }
    getUsers();

    // decide if we are creating or editing a ticket
    if ($routeParams.id) {
      $scope.title = "Edit ticket";
      $scope.action_name = 'Save';
      $scope.ticket_status = 'open';
      // get the ticket
      $http.get($rootScope.appUrl + '/tickets/view/' + $routeParams.id + '.json').success(function(data, status, headers, config) {
        $scope.ticket = data.ticket.Ticket;
        $scope.ticket_status = (data.ticket.Ticket.closed) ? 'closed' : 'open';
      });
    } else {
      $scope.title = "Create new ticket";
      $scope.action_name = 'Create';
      $scope.ticket_status = 'new';
    }

    // Save ticket
    $scope.saveTicket = function() {
      if ($scope.editTicketForm.$invalid === false) {
        $http.post($rootScope.appUrl + '/tickets/save.json', $scope.ticket)
          .success(function(data, status, headers, config) {
            if (data.message.type == 'success') {
              $location.path('/tickets');
            }
          })
      }
    }

    // close ticket
    $scope.closeTicket = function(id) {
      if ($scope.editTicketForm.$invalid === false) {
        $http.post($rootScope.appUrl + '/tickets/close/' + id + '.json', $scope.ticket)
          .success(function(data, status, headers, config) {
            if (data.message.type == 'success') {
              $location.path('/tickets');
            }
          })
      }
    }

    // open ticket
    $scope.openTicket = function(id) {
      if ($scope.editTicketForm.$invalid === false) {
        $http.post($rootScope.appUrl + '/tickets/open/' + id + '.json', $scope.ticket)
          .success(function(data, status, headers, config) {
            if (data.message.type == 'success') {
              $location.path('/tickets');
            }
          })
      }
    }

    // delete ticket
    $scope.deleteTicket = function(id) {
      $http.post($rootScope.appUrl + '/tickets/delete/' + id + '.json', $scope.ticket)
        .success(function(data, status, headers, config) {
          if (data.message.type == 'success') {
            $location.path('/tickets');
          }
        })
    }
  });

  /**
  * Edit user controller
  */
  controllers.controller('EditUserController', function($scope, $rootScope, $http, $location, $routeParams) {

    // Create empty object, so we dont get undefined error
    $scope.user = {};

    // decide if we are creating or editing use
    if ($routeParams.id) {
      $scope.title = "Edit user";
      $scope.action_name = 'Save';
      // get the user
      $http.get($rootScope.appUrl + '/users/view/' + $routeParams.id + '.json').success(function(data, status, headers, config) {
        $scope.user = data.user.User;
      });
    } else {
      $scope.title = "Create new user";
      $scope.action_name = 'Create';
    }

    // Save user
    $scope.saveUser = function() {
      if ($scope.editUserForm.$invalid === false) {
        $http.post($rootScope.appUrl + '/users/save.json', $scope.user)
          .success(function(data, status, headers, config) {
            if (data.message.type == 'success') {
              $location.path('/tickets');
            }
          })
      }
    }

  });

}(window.angular))
