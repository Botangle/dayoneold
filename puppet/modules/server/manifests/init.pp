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

    include server::cakeenv
}
