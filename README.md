# Vagrantfile & Puppet manifests for Botangle / Laravel

## Software requirements

You need to install the following to work with our setup:

- [VirtualBox][virtualbox]
- [Vagrant][vagrant]
- vagrant-hostmanager

[virtualbox]: https://www.virtualbox.org/wiki/Downloads
[vagrant]: http://www.vagrantup.com/downloads.html

Once the first two items are installed, then install `vagrant-hostmanager` as follows:

    vagrant plugin install vagrant-hostmanager

## Getting started

- In the Terminal / console, type `vagrant up`.  This will build a VM for you with our settings applied
- Once done, type `vagrant ssh` and then `cd /var/www`
- Run `composer.phar install`, which will load up all our various libraries
- Run `./artisan migrate --env=local`

You should be able to access your Botangle setup now in a web browser: [http://new.botangle.dev](http://new.botangle.dev)

Note: we use migrations (see above) heavily as we make changes to the DB, so if you haven't used them before, please
spend a bit of time learning how they work.

## To start sending in code
Please see the Contributing.md file

## Artisan additions
We've added the following additional tools to `artisan` to make our lives easier.

- Generators ([https://github.com/JeffreyWay/Laravel-4-Generators](https://github.com/JeffreyWay/Laravel-4-Generators))

## MySQL

You need to use an SSH connection.

With the information below you can connect to the MySQL server running on the virtual machine.

## Default information

* SSH Host: 192.168.200.21 (`192.168.200.1` is your machine)
* SSH User: vagrant
* SSH Pass: vagrant
* SSH Port: 2222 (default by vagrant)
* MySQL Host: 127.0.0.1
* MySQL Port: 3306
* MySQL User: botangle
* MySQL Pass: botangle
* MySQL DB:   botangle

### Database setup

The `database.sql.gz` in the laravel/dbdump directory is loaded into the `botangle` on first `up`.  If you need to rebuild your
db and start over, delete the entire db and do a `vagrant provision` and it will be rebuilt for you.

## Virtualhost

The virtualhost is set on the `laravel/public` directory.

## Mail

[MailCatcher][mailcatcher] is installed and configured into the `/etc/php5/apache2/php.ini` file.

### How it works

If mailcatcher is stopped: all emails are lost.

If mailcatcher is started: **all emails are caught**.

If you want to start MailCatcher, simply run this command (with vagrant user) : `mailcatcher --ip 0.0.0.0`

Then go to : http://new.botangle.dev:1080

If you need to stop the mailcatcher daemon : Click on "Quit" on the top right corner of the MailCatcher Web UI.

## Running Migrations for CakePHP
Inside of the vagrant machine (you can use `vagrant ssh` to get in) in the app folder (`/var/www/app`), run this command:

```./artisan migrate --env=local```

## Packages

Are installed:

* apache-mpm-itk (with virtualhost)
* mysql-server (with custom my.cnf)
* mysql-client
* php5 (and the following modules)
    - bcmatch
    - bz2
    - calendar
    - ctype
    - curl
    - date
    - dba
    - dom
    - ereg
    - exif
    - fileinfo
    - filter
    - ftp
    - gd
    - gettext
    - hash
    - iconv
    - intl
    - json
    - libxml
    - mbstring
    - mcrypt
    - mhash
    - mysql
    - mysqli
    - openssl
    - pcre
    - pdo
    - pdo_mysql
    - phar
    - posix
    - readline
    - reflection
    - session
    - shmop
    - simplexml
    - soap
    - sockets
    - spl
    - sysvmsg
    - tidy
    - tokenizer
    - wddx
    - xdebug
    - xml
    - xmlreader
    - xmlwriter
    - zip
    - zlib
* screen (with custom .screenrc for root)
* vim
* wget
* curl
* git
* composer.phar
* mailcatcher (gem)

[mailcatcher]: http://mailcatcher.me
