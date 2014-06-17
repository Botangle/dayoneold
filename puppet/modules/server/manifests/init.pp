class server ( $hostname ) {

    exec { "update":
        path    => "/bin:/usr/bin",
        command => "apt-get update",
    }

    # Few packages
    $packages = ["curl", "tidy", "screen", "vim", "htop", "telnet"]
    package { $packages:
        ensure  => latest,
        require => Exec['update'],
    }

    # ScreenRC
    file { ".screenrc":
        ensure  => file,
        path    => '/root/.screenrc',
        source  => 'puppet:///modules/server/.screenrc',
    }
    file { ".screenrc for vagrant":
        ensure  => file,
        path    => '/home/vagrant/.screenrc',
        source  => 'puppet:///modules/server/.screenrc',
    }
    # Leaves out rdoc / ri files for Gems: http://stackoverflow.com/questions/1381725/how-to-make-no-ri-no-rdoc-the-default-for-gem-install
    file { ".gemrc":
        ensure  => file,
        path    => '/home/vagrant/.gemrc',
        source  => 'puppet:///modules/server/.gemrc',
    }

    include server::cakeenv
}
