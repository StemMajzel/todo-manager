# define paths
Exec {path => ['/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/']}

# set global packages params
Package {ensure => installed}

# mysql root password
$mysql_password = "IAmGroot6"

# create mysql database
define mysqldb($user, $password) {
  exec {"create-${name}-db":
    unless => "mysql -u${user} -p${password} ${name}",
    command => "mysql -uroot -p${mysql_password} -e \"create database ${name}; grant all on ${name}.* to ${user}@localhost identified by '${password}';\""
  }
}

# add line to file
define add_line_to_file($line) {
  exec {"add-line-to-${name}":
    command => "echo \"${$line}\" >> ${$name}"
  }
}

# restart apache
class restart_apache {
  exec {'service apache2 restart':
    require => Class['install_apache']
  }
}

# initialize - install prerequisites
class initialize {
  exec {'apt_get_update':
    command => 'apt-get update'
  }
}

# set symling to project folder
class set_symlink {
  exec {'clear_www_folder':
    command => 'rm -rf /var/www/html',
    require => Class['install_apache']
  }

  file {'link':
    path => '/var/www/html',
    ensure => link,
    target => '/vagrant',
    require => Exec['clear_www_folder']
  }
}

# install and run apache
class install_apache {
  package {'apache2':
    require => Class['initialize']
  }

  service {'apache2':
    ensure => running,
    require => Package['apache2']
  }

  file {'configure_mod_rewrite':
    path => '/etc/apache2/sites-available/000-default.conf',
    ensure => present,
    content => '<VirtualHost *:80>
  <Directory /var/www/html>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    allow from all
  </Directory>
  DocumentRoot /var/www/html
  ErrorLog ${APACHE_LOG_DIR}/error.log
  CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>',
    require => Service['apache2']
  }

  exec {'a2enmod rewrite':
    require => File['configure_mod_rewrite'],
    before => Class['restart_apache']
  }
}

# install and configure mysql
class install_mysql {
  package {'mysql-server':
    require => Class['install_apache']
  }

  package {'mysql-client':
    require => Package['mysql-server']
  }

  package {'php5-mysql':
    require => Package['mysql-client']
  }

  service {'mysql':
    enable => true,
    ensure => running,
    require => Package['php5-mysql']
  }

  exec { "set-mysql-password":
    unless => "mysqladmin -uroot -p$mysql_password status",
    command => "mysqladmin -uroot password $mysql_password",
    require => Service['mysql']
  }
}

# create user and database
class create_user_and_database {
  mysqldb {'todo_manager':
    user => 'todomanager',
    password => '5todo6manager',
    require => Class['install_mysql']
  }
}

# install and configure php
class install_php {
  package {'php5':
    require => Class['install_apache']
  }

  package {'libapache2-mod-php5':
    require => Package['php5']
  }

  package {'php5-mcrypt':
    require => Package['libapache2-mod-php5']
  }

  exec {'apache_enable_mcrypt':
    command => 'php5enmod mcrypt',
    require => Package['php5-mcrypt']
  }

  exec {'apache_enable_php':
    command => 'a2enmod php5',
    require => Package['php5-mcrypt']
  }
}

class install_phpmyadmin {
  exec {'configure_phpmyadmin':
    command => "echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | debconf-set-selections &&
      echo 'phpmyadmin phpmyadmin/mysql/admin-pass password $mysql_password' | debconf-set-selections &&
      echo 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2' | debconf-set-selections",
    require => [
      Class['install_mysql'],
      Class['install_php']
    ]
  }

  package {'phpmyadmin':
    require => Exec['configure_phpmyadmin']
  }

  add_line_to_file {'/etc/apache2/apache2.conf':
    line => 'include /etc/phpmyadmin/apache.conf',
    require => Package['phpmyadmin'],
    before => Class['restart_apache']
  }
}

include initialize
include install_apache
include install_mysql
include create_user_and_database
include install_php
include install_phpmyadmin
include set_symlink
include restart_apache
