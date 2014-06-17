# -*- mode: ruby -*-
# vi: set ft=ruby :
# Some info on how to adjust this file: http://garylarizza.com/blog/2013/02/01/repeatable-puppet-development-with-vagrant/

hostname = "app.botangle.dev"

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty64"

  # VMWare Fusion customization
  config.vm.provider :vmware_fusion do |vmware, override|

    # Customize VM
    vmware.vmx["memsize"] = "1024"
    vmware.vmx["numvcpus"] = "1"

  end

  # Virtualbox customization
  config.vm.provider :virtualbox do |virtualbox, override|

    # Customize VM
    virtualbox.customize ["modifyvm", :id, "--memory", "1024", "--cpus", "1", "--pae", "on", "--hwvirtex", "on", "--ioapic", "on"]

  end

  # Network
  config.vm.network :private_network, ip: "192.168.200.20"
  config.vm.hostname                    = hostname

  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true

  # Synced folders
  config.vm.synced_folder "www", "/var/www"
  # config.vm.synced_folder "htdocs", "/var/www/magento", nfs: true,
  #                                   mount_options: ["nolock", "async"],
  #                                   bsd__nfs_options: ["alldirs","async","nolock"]

  # "Provision" with hostmanager
  config.vm.provision :hostmanager

  # Puppet!
  config.vm.provision :puppet do |puppet|
    puppet.manifests_path   = "puppet/manifests"
    puppet.module_path      = "puppet/modules"
    puppet.manifest_file    = "init.pp"

    # Factors
    puppet.facter = {
        "vagrant"           => "1",
        "hostname"          => hostname,
        "db_root_password"  => "mysql",
        "db_user"           => "botangle",
        "db_password"       => "botangle",
        "db_name"           => "botangle",
        "db_name_tests"     => "botangle_tests",
        "document_root"     => "/var/www",
        "logs_dir"          => "/var/www/logs",
    }
  end

end
