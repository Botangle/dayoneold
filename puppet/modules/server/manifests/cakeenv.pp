class server::cakeenv {

    # BashRC file for Vagrant
    file { ".bashrc for vagrant":
        ensure  => file,
        path    => '/home/vagrant/.bashrc',
        source  => 'puppet:///modules/server/.bashrc',
    }

}
